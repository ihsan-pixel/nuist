import 'dart:math' as math;

import 'package:camera/camera.dart';
import 'package:flutter/services.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:image/image.dart' as img;

class FaceCameraImageException implements Exception {
  FaceCameraImageException(this.code, [this.message = '']);

  final String code;
  final String message;

  @override
  String toString() => message.isEmpty ? code : '$code: $message';
}

class FaceCameraImageConverter {
  const FaceCameraImageConverter();

  FaceAlignmentResult extractAlignedFaceCropFromRgb(
    img.Image rgb,
    Face face, {
    double padding = 0.18,
  }) {
    final rotated = _rotateForLandmarks(rgb, face);
    final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
    final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
    if (leftEye == null || rightEye == null) {
      throw FaceCameraImageException(
        'FACE_ALIGNMENT_INSUFFICIENT_LANDMARKS',
        'Left and right eye landmarks are required for alignment.',
      );
    }

    final target = _canonicalTargetPoints();
    final transform = _buildSimilarityTransform(
      sourceLeftEye: leftEye,
      sourceRightEye: rightEye,
      targetLeftEye: target.leftEye,
      targetRightEye: target.rightEye,
    );
    final crop = _warpSimilarity(
      rotated,
      transform,
      outputWidth: 112,
      outputHeight: 112,
    );
    if (crop.width <= 0 || crop.height <= 0) {
      throw FaceCameraImageException(
        'CAMERA_IMAGE_CONVERSION_FAILED',
        'Crop is empty after alignment.',
      );
    }
    final normalized = _normalizeLandmarks(
      transform,
      leftEye: leftEye,
      rightEye: rightEye,
      sourceWidth: rotated.width,
      sourceHeight: rotated.height,
    );
    return FaceAlignmentResult(
      sourceWidth: rgb.width,
      sourceHeight: rgb.height,
      rotationDegrees: _rotationDegreesForLandmarks(face),
      cropBeforeWidth: rotated.width,
      cropBeforeHeight: rotated.height,
      cropAfterWidth: crop.width,
      cropAfterHeight: crop.height,
      clamped: transform.boundaryPadding,
      square: crop.width == crop.height,
      method: 'eye_similarity',
      scale: transform.scale,
      translationX: transform.translationX,
      translationY: transform.translationY,
      targetEyeDistance: transform.targetEyeDistance,
      sourceEyeDistance: transform.sourceEyeDistance,
      leftEyeX: normalized.leftEyeX,
      leftEyeY: normalized.leftEyeY,
      rightEyeX: normalized.rightEyeX,
      rightEyeY: normalized.rightEyeY,
      faceCenterX: normalized.faceCenterX,
      faceCenterY: normalized.faceCenterY,
      cropLeft: 0,
      cropTop: 0,
      cropWidth: crop.width,
      cropHeight: crop.height,
      crop: crop,
    );
  }

  InputImage toInputImage(
    CameraImage image,
    CameraDescription camera,
    DeviceOrientation orientation,
  ) {
    final bytes = convertToNv21Bytes(image);
    final rotation = rotationFromOrientation(camera, orientation);

    return InputImage.fromBytes(
      bytes: bytes,
      metadata: InputImageMetadata(
        size: Size(image.width.toDouble(), image.height.toDouble()),
        rotation: rotation,
        format: InputImageFormat.nv21,
        bytesPerRow: image.planes.first.bytesPerRow,
      ),
    );
  }

  Uint8List convertToNv21Bytes(CameraImage image) {
    if (image.planes.length < 3) {
      throw FaceCameraImageException(
        'CAMERA_IMAGE_FORMAT_UNSUPPORTED',
        'Expected at least 3 planes for YUV420/NV21 conversion.',
      );
    }

    switch (image.format.group) {
      case ImageFormatGroup.yuv420:
        return _convertYuv420ToNv21(image);
      case ImageFormatGroup.bgra8888:
        throw FaceCameraImageException(
          'CAMERA_IMAGE_FORMAT_UNSUPPORTED',
          'BGRA8888 requires RGB extraction path, not NV21 bytes.',
        );
      default:
        throw FaceCameraImageException(
          'CAMERA_IMAGE_FORMAT_UNSUPPORTED',
          'Unsupported camera image format: ${image.format.group}.',
        );
    }
  }

  img.Image convertToRgbImage(CameraImage image) {
    switch (image.format.group) {
      case ImageFormatGroup.yuv420:
        return _convertYuv420ToRgb(image);
      case ImageFormatGroup.bgra8888:
        return _convertBgraToRgb(image);
      default:
        throw FaceCameraImageException(
          'CAMERA_IMAGE_FORMAT_UNSUPPORTED',
          'Unsupported camera image format: ${image.format.group}.',
        );
    }
  }

  img.Image extractAlignedFaceCrop(
    CameraImage image,
    Face face, {
    DeviceOrientation orientation = DeviceOrientation.portraitUp,
    double padding = 0.18,
  }) {
    final rgb = convertToRgbImage(image);
    return extractAlignedFaceCropFromRgb(rgb, face, padding: padding).crop;
  }

  InputImageRotation rotationFromOrientation(
    CameraDescription camera,
    DeviceOrientation orientation,
  ) {
    final deviceRotation = switch (orientation) {
      DeviceOrientation.portraitUp => 0,
      DeviceOrientation.landscapeLeft => 90,
      DeviceOrientation.portraitDown => 180,
      DeviceOrientation.landscapeRight => 270,
    };
    final sensorRotation = camera.sensorOrientation;
    final rotationCompensation = camera.lensDirection == CameraLensDirection.front
        ? (sensorRotation + deviceRotation) % 360
        : (sensorRotation - deviceRotation + 360) % 360;

    return InputImageRotationValue.fromRawValue(rotationCompensation) ??
        InputImageRotation.rotation0deg;
  }

  Uint8List _convertYuv420ToNv21(CameraImage image) {
    final yPlane = image.planes[0];
    final uPlane = image.planes[1];
    final vPlane = image.planes[2];
    final ySize = image.width * image.height;
    final uvSize = image.width * image.height ~/ 2;
    final bytes = Uint8List(ySize + uvSize);

    var offset = 0;
    for (var row = 0; row < image.height; row++) {
      final rowStart = row * yPlane.bytesPerRow;
      bytes.setRange(offset, offset + image.width, yPlane.bytes, rowStart);
      offset += image.width;
    }

    final chromaHeight = image.height ~/ 2;
    final chromaWidth = image.width ~/ 2;
    for (var row = 0; row < chromaHeight; row++) {
      for (var col = 0; col < chromaWidth; col++) {
        final vIndex = row * vPlane.bytesPerRow + col * (vPlane.bytesPerPixel ?? 1);
        final uIndex = row * uPlane.bytesPerRow + col * (uPlane.bytesPerPixel ?? 1);
        bytes[offset++] = vPlane.bytes[vIndex];
        bytes[offset++] = uPlane.bytes[uIndex];
      }
    }

    return bytes;
  }

  img.Image _convertYuv420ToRgb(CameraImage image) {
    final bytes = convertToNv21Bytes(image);
    return _nv21ToRgb(bytes, image.width, image.height);
  }

  img.Image _convertBgraToRgb(CameraImage image) {
    final plane = image.planes.first;
    if (plane.bytesPerPixel != null && plane.bytesPerPixel != 4) {
      throw FaceCameraImageException(
        'CAMERA_IMAGE_CONVERSION_FAILED',
        'BGRA8888 plane must have 4 bytes per pixel.',
      );
    }

    final result = img.Image(width: image.width, height: image.height);
    final bytes = plane.bytes;
    var index = 0;
    for (var y = 0; y < image.height; y++) {
      for (var x = 0; x < image.width; x++) {
        final b = bytes[index++];
        final g = bytes[index++];
        final r = bytes[index++];
        index++; // alpha
        result.setPixelRgb(x, y, r, g, b);
      }
    }
    return result;
  }

  img.Image _nv21ToRgb(Uint8List bytes, int width, int height) {
    final frameSize = width * height;
    final result = img.Image(width: width, height: height);

    for (var y = 0; y < height; y++) {
      final uvp = frameSize + (y >> 1) * width;
      for (var x = 0; x < width; x++) {
        final yValue = bytes[y * width + x] & 0xff;
        final v = bytes[uvp + (x & ~1)] & 0xff;
        final u = bytes[uvp + (x & ~1) + 1] & 0xff;

        final y1 = math.max(0, yValue - 16);
        final u1 = u - 128;
        final v1 = v - 128;

        var r = (1.164 * y1 + 1.596 * v1).round();
        var g = (1.164 * y1 - 0.392 * u1 - 0.813 * v1).round();
        var b = (1.164 * y1 + 2.017 * u1).round();

        r = r.clamp(0, 255);
        g = g.clamp(0, 255);
        b = b.clamp(0, 255);
        result.setPixelRgb(x, y, r, g, b);
      }
    }

    return result;
  }

  img.Image _rotateForLandmarks(
    img.Image source,
    Face face,
  ) {
    final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
    final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
    if (leftEye == null || rightEye == null) {
      throw FaceCameraImageException(
        'FACE_ALIGNMENT_INSUFFICIENT_LANDMARKS',
        'Left and right eye landmarks are required for alignment.',
      );
    }

    final dx = rightEye.x.toDouble() - leftEye.x.toDouble();
    final dy = rightEye.y.toDouble() - leftEye.y.toDouble();
    final rollDegrees = math.atan2(dy, dx) * 180 / math.pi;
    if (rollDegrees.abs() < 0.01) {
      return source;
    }

    return img.copyRotate(source, angle: -rollDegrees);
  }

  double _rotationDegreesForLandmarks(Face face) {
    final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
    final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
    if (leftEye == null || rightEye == null) {
      throw FaceCameraImageException(
        'FACE_ALIGNMENT_INSUFFICIENT_LANDMARKS',
        'Left and right eye landmarks are required for alignment.',
      );
    }
    final dx = rightEye.x.toDouble() - leftEye.x.toDouble();
    final dy = rightEye.y.toDouble() - leftEye.y.toDouble();
    return math.atan2(dy, dx) * 180 / math.pi;
  }

  _CanonicalTargetPoints _canonicalTargetPoints() {
    return const _CanonicalTargetPoints(
      leftEye: OffsetPoint(38.0, 42.0),
      rightEye: OffsetPoint(74.0, 42.0),
    );
  }

  _SimilarityTransform _buildSimilarityTransform({
    required math.Point<int> sourceLeftEye,
    required math.Point<int> sourceRightEye,
    required OffsetPoint targetLeftEye,
    required OffsetPoint targetRightEye,
  }) {
    final sourceDx = sourceRightEye.x.toDouble() - sourceLeftEye.x.toDouble();
    final sourceDy = sourceRightEye.y.toDouble() - sourceLeftEye.y.toDouble();
    final sourceDistance = math.sqrt((sourceDx * sourceDx) + (sourceDy * sourceDy));
    if (sourceDistance <= 0 || !sourceDistance.isFinite) {
      throw FaceCameraImageException(
        'FACE_ALIGNMENT_INVALID_GEOMETRY',
        'Source eye distance invalid.',
      );
    }

    final targetDx = targetRightEye.x - targetLeftEye.x;
    final targetDy = targetRightEye.y - targetLeftEye.y;
    final targetDistance = math.sqrt((targetDx * targetDx) + (targetDy * targetDy));
    final sourceAngle = math.atan2(sourceDy, sourceDx);
    final targetAngle = math.atan2(targetDy, targetDx);
    final angle = targetAngle - sourceAngle;
    final scale = targetDistance / sourceDistance;
    final cosA = math.cos(angle);
    final sinA = math.sin(angle);

    final rotatedLeftX = (sourceLeftEye.x.toDouble() * scale * cosA) -
        (sourceLeftEye.y.toDouble() * scale * sinA);
    final rotatedLeftY = (sourceLeftEye.x.toDouble() * scale * sinA) +
        (sourceLeftEye.y.toDouble() * scale * cosA);
    final translationX = targetLeftEye.x - rotatedLeftX;
    final translationY = targetLeftEye.y - rotatedLeftY;

    return _SimilarityTransform(
      scale: scale,
      angle: angle,
      translationX: translationX,
      translationY: translationY,
      sourceEyeDistance: sourceDistance,
      targetEyeDistance: targetDistance,
    );
  }

  img.Image _warpSimilarity(
    img.Image source,
    _SimilarityTransform transform, {
    required int outputWidth,
    required int outputHeight,
  }) {
    final result = img.Image(width: outputWidth, height: outputHeight);
    final cosA = math.cos(transform.angle);
    final sinA = math.sin(transform.angle);
    var boundaryPadding = false;
    for (var y = 0; y < outputHeight; y++) {
      for (var x = 0; x < outputWidth; x++) {
        final tx = x.toDouble();
        final ty = y.toDouble();
        final sx = ((tx - transform.translationX) * cosA + (ty - transform.translationY) * sinA) / transform.scale;
        final sy = (-(tx - transform.translationX) * sinA + (ty - transform.translationY) * cosA) / transform.scale;
        if (sx < 0 || sy < 0 || sx >= source.width - 1 || sy >= source.height - 1) {
          boundaryPadding = true;
          result.setPixelRgb(x, y, 0, 0, 0);
          continue;
        }
        final px = sx.round();
        final py = sy.round();
        final color = source.getPixel(px, py);
        result.setPixel(x, y, color);
      }
    }
    transform.boundaryPadding = boundaryPadding;
    return result;
  }

  _NormalizedLandmarks _normalizeLandmarks(
    _SimilarityTransform transform, {
    required math.Point<int> leftEye,
    required math.Point<int> rightEye,
    required int sourceWidth,
    required int sourceHeight,
  }) {
    final cosA = math.cos(transform.angle);
    final sinA = math.sin(transform.angle);
    OffsetPoint mapPoint(math.Point<int> point) {
      final x = point.x.toDouble() * transform.scale;
      final y = point.y.toDouble() * transform.scale;
      final rx = (x * cosA) - (y * sinA) + transform.translationX;
      final ry = (x * sinA) + (y * cosA) + transform.translationY;
      return OffsetPoint(
        rx / 112.0,
        ry / 112.0,
      );
    }

    final left = mapPoint(leftEye);
    final right = mapPoint(rightEye);
    final faceCenter = OffsetPoint(
      ((left.x + right.x) / 2.0).clamp(0.0, 1.0),
      ((left.y + right.y) / 2.0).clamp(0.0, 1.0),
    );
    return _NormalizedLandmarks(
      leftEyeX: left.x,
      leftEyeY: left.y,
      rightEyeX: right.x,
      rightEyeY: right.y,
      faceCenterX: faceCenter.x,
      faceCenterY: faceCenter.y,
    );
  }
}

class FaceAlignmentResult {
  const FaceAlignmentResult({
    required this.sourceWidth,
    required this.sourceHeight,
    required this.rotationDegrees,
    required this.cropBeforeWidth,
    required this.cropBeforeHeight,
    required this.cropAfterWidth,
    required this.cropAfterHeight,
    required this.clamped,
    required this.square,
    required this.method,
    required this.scale,
    required this.translationX,
    required this.translationY,
    required this.targetEyeDistance,
    required this.sourceEyeDistance,
    required this.leftEyeX,
    required this.leftEyeY,
    required this.rightEyeX,
    required this.rightEyeY,
    required this.faceCenterX,
    required this.faceCenterY,
    required this.cropLeft,
    required this.cropTop,
    required this.cropWidth,
    required this.cropHeight,
    required this.crop,
  });

  final int sourceWidth;
  final int sourceHeight;
  final double rotationDegrees;
  final int cropBeforeWidth;
  final int cropBeforeHeight;
  final int cropAfterWidth;
  final int cropAfterHeight;
  final bool clamped;
  final bool square;
  final String method;
  final double scale;
  final double translationX;
  final double translationY;
  final double targetEyeDistance;
  final double sourceEyeDistance;
  final double leftEyeX;
  final double leftEyeY;
  final double rightEyeX;
  final double rightEyeY;
  final double faceCenterX;
  final double faceCenterY;
  final int cropLeft;
  final int cropTop;
  final int cropWidth;
  final int cropHeight;
  final img.Image crop;

  double get aspectRatio => cropAfterHeight == 0 ? 0.0 : cropAfterWidth / cropAfterHeight;
}

class _CanonicalTargetPoints {
  const _CanonicalTargetPoints({
    required this.leftEye,
    required this.rightEye,
  });

  final OffsetPoint leftEye;
  final OffsetPoint rightEye;
}

class OffsetPoint {
  const OffsetPoint(this.x, this.y);

  final double x;
  final double y;
}

class _SimilarityTransform {
  _SimilarityTransform({
    required this.scale,
    required this.angle,
    required this.translationX,
    required this.translationY,
    required this.sourceEyeDistance,
    required this.targetEyeDistance,
  });

  final double scale;
  final double angle;
  final double translationX;
  final double translationY;
  final double sourceEyeDistance;
  final double targetEyeDistance;
  bool boundaryPadding = false;
}

class _NormalizedLandmarks {
  const _NormalizedLandmarks({
    required this.leftEyeX,
    required this.leftEyeY,
    required this.rightEyeX,
    required this.rightEyeY,
    required this.faceCenterX,
    required this.faceCenterY,
  });

  final double leftEyeX;
  final double leftEyeY;
  final double rightEyeX;
  final double rightEyeY;
  final double faceCenterX;
  final double faceCenterY;
}
