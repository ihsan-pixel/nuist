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

  img.Image extractAlignedFaceCropFromRgb(
    img.Image rgb,
    Face face, {
    double padding = 0.18,
  }) {
    final rotated = _rotateForLandmarks(rgb, face);
    final crop = _cropWithPadding(rotated, face.boundingBox, padding: padding);
    if (crop.width <= 0 || crop.height <= 0) {
      throw FaceCameraImageException(
        'CAMERA_IMAGE_CONVERSION_FAILED',
        'Crop is empty after alignment.',
      );
    }
    return crop;
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
    return extractAlignedFaceCropFromRgb(rgb, face, padding: padding);
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

  img.Image _cropWithPadding(
    img.Image source,
    Rect bbox, {
    double padding = 0.18,
  }) {
    final padX = source.width * padding;
    final padY = source.height * padding;

    final startX = math.max(0, (bbox.left - padX).floor());
    final startY = math.max(0, (bbox.top - padY).floor());
    final endX = math.min(source.width, (bbox.right + padX).ceil());
    final endY = math.min(source.height, (bbox.bottom + padY).ceil());

    final width = endX - startX;
    final height = endY - startY;
    if (width <= 0 || height <= 0) {
      return img.Image(width: 0, height: 0);
    }

    return img.copyCrop(source, x: startX, y: startY, width: width, height: height);
  }
}
