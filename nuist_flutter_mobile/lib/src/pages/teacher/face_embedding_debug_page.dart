import 'dart:math' as math;

import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:image/image.dart' as img;

import '../../services/face_camera_image_converter.dart';
import '../../services/face_recognition_service.dart';

enum FaceDebugStage {
  cameraFrame,
  mlkitInput,
  faceDetection,
  frameCopy,
  rgbConversion,
  landmarkMapping,
  alignment,
  cropValidation,
  resize112,
  tflitePreprocess,
  tfliteInference,
  embeddingValidation,
  complete,
}

extension FaceDebugStageLabel on FaceDebugStage {
  String get label => switch (this) {
        FaceDebugStage.cameraFrame => 'CAMERA_FRAME',
        FaceDebugStage.mlkitInput => 'MLKIT_INPUT',
        FaceDebugStage.faceDetection => 'FACE_DETECTION',
        FaceDebugStage.frameCopy => 'FRAME_COPY',
        FaceDebugStage.rgbConversion => 'RGB_CONVERSION',
        FaceDebugStage.landmarkMapping => 'LANDMARK_MAPPING',
        FaceDebugStage.alignment => 'ALIGNMENT',
        FaceDebugStage.cropValidation => 'CROP_VALIDATION',
        FaceDebugStage.resize112 => 'RESIZE_112',
        FaceDebugStage.tflitePreprocess => 'TFLITE_PREPROCESS',
        FaceDebugStage.tfliteInference => 'TFLITE_INFERENCE',
        FaceDebugStage.embeddingValidation => 'EMBEDDING_VALIDATION',
        FaceDebugStage.complete => 'COMPLETE',
      };
}

class FaceEmbeddingDebugPage extends StatefulWidget {
  const FaceEmbeddingDebugPage({super.key});

  @override
  State<FaceEmbeddingDebugPage> createState() => _FaceEmbeddingDebugPageState();
}

class _FaceEmbeddingDebugPageState extends State<FaceEmbeddingDebugPage> {
  final FaceCameraImageConverter _converter = const FaceCameraImageConverter();
  final FaceRecognitionService _recognitionService = FaceRecognitionService();
  CameraController? _controller;
  FaceDetector? _detector;
  bool _cameraReady = false;
  bool _mlKitReady = false;
  bool _tfliteReady = false;
  bool _processingFrame = false;
  bool _isCapturing = false;
  FaceDebugStage _stage = FaceDebugStage.cameraFrame;
  String _status = 'Initializing debug harness...';
  String? _error;
  String? _captureDisabledReason;
  double? _lastNorm;
  int? _lastEmbeddingSize;
  String? _lastTiming;
  Face? _latestFace;
  int _latestFaceCount = 0;
  _OwnedCameraFrame? _latestFrame;
  int _latestFrameId = 0;
  DateTime? _latestFrameCapturedAt;
  String? _latestSampleLabel;
  final List<_SampleRecord> _samples = <_SampleRecord>[];
  Map<String, double>? _latestSimilarityStats;

  @override
  void initState() {
    super.initState();
    if (!kDebugMode) {
      _status = 'Debug harness disabled in release.';
      return;
    }
    _initialize();
  }

  Future<void> _initialize() async {
    try {
      final cameras = await availableCameras();
      final frontCamera = cameras.firstWhere(
        (camera) => camera.lensDirection == CameraLensDirection.front,
        orElse: () => cameras.first,
      );

      final controller = CameraController(
        frontCamera,
        ResolutionPreset.medium,
        enableAudio: false,
        imageFormatGroup: ImageFormatGroup.yuv420,
      );
      await controller.initialize();

      final detector = FaceDetector(
        options: FaceDetectorOptions(
          enableClassification: true,
          enableLandmarks: true,
          enableTracking: true,
          performanceMode: FaceDetectorMode.accurate,
        ),
      );

      await _recognitionService.initialize();

      if (!mounted) {
        await detector.close();
        await controller.dispose();
        _recognitionService.dispose();
        return;
      }

      setState(() {
        _controller = controller;
        _detector = detector;
        _cameraReady = true;
        _mlKitReady = true;
        _tfliteReady = true;
        _status = 'Camera, ML Kit, and TFLite ready.';
      });

      await controller.startImageStream(_processFrame);
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error ?? 'Initialization failed.';
      });
    }
  }

  Future<void> _processFrame(CameraImage image) async {
    if (_isCapturing) {
      debugPrint('[FACE_DEBUG][BACKGROUND][SKIP] reason=capture_in_progress');
      return;
    }
    if (_processingFrame || _detector == null || _controller == null) {
      return;
    }
    _processingFrame = true;

    try {
      _setStage(FaceDebugStage.cameraFrame, 'camera frame received', {
        'width': image.width,
        'height': image.height,
        'format': image.format.group.toString(),
        'planes': image.planes.length,
      });
      for (var i = 0; i < image.planes.length; i++) {
        final plane = image.planes[i];
        debugPrint(
          '[FACE_DEBUG][PLANE][$i] bytes=${plane.bytes.length} '
          'bytesPerRow=${plane.bytesPerRow} '
          'bytesPerPixel=${plane.bytesPerPixel}',
        );
      }

      _setStage(FaceDebugStage.mlkitInput, 'building ML Kit input');
      final inputImage = _converter.toInputImage(
        image,
        _controller!.description,
        _controller!.value.deviceOrientation,
      );
      debugPrint('[FACE_DEBUG][OK][${FaceDebugStage.mlkitInput.label}]');

      _setStage(FaceDebugStage.faceDetection, 'running face detector');
      final faces = await _detector!.processImage(inputImage);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.faceDetection.label}] faces=${faces.length}',
      );
      _latestFaceCount = faces.length;
      if (faces.isEmpty) {
        if (mounted) {
          setState(() {
            _status = 'No face detected.';
            _captureDisabledReason = 'Capture disabled: no valid face';
          });
        }
        return;
      }

      final face = faces.first;
      _latestFace = face;
      _latestFrameId += 1;
      _latestFrameCapturedAt = DateTime.now();
      _latestFrame = _OwnedCameraFrame.fromCameraImage(
        _converter.convertToRgbImage(image),
        frameId: _latestFrameId,
        capturedAt: _latestFrameCapturedAt!,
        deviceOrientation: _controller!.value.deviceOrientation,
      );

      final box = face.boundingBox;
      debugPrint(
        '[FACE_DEBUG][FACE_BOX] '
        'left=${box.left.toStringAsFixed(2)} '
        'top=${box.top.toStringAsFixed(2)} '
        'width=${box.width.toStringAsFixed(2)} '
        'height=${box.height.toStringAsFixed(2)}',
      );

      final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
      final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
      final faceReady = leftEye != null && rightEye != null;
      _captureDisabledReason = _buildCaptureDisabledReason(faceReady: faceReady);

      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.landmarkMapping.label}] '
        'leftEye=${leftEye != null} rightEye=${rightEye != null} '
        'leftFinite=${_pointFinite(leftEye)} rightFinite=${_pointFinite(rightEye)}',
      );
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.complete.label}] backgroundReady=true',
      );

      if (mounted) {
        setState(() {
          _error = null;
          _status = _captureDisabledReason == null
              ? 'Wajah siap diambil. Tekan Capture Sample.'
              : _captureDisabledReason!;
        });
      }
    } catch (error, st) {
      debugPrint(
        '[FACE_DEBUG][ERROR][${_stage.label}] ${error.runtimeType}: $error',
      );
      debugPrintStack(stackTrace: st);
      if (!mounted) {
        return;
      }
      setState(() {
        final message = error.toString().replaceFirst('Exception: ', '');
        _error = '${error.runtimeType}: $message';
        _status = 'ERROR';
      });
    } finally {
      _processingFrame = false;
    }
  }

  Future<void> _captureSample() async {
    debugPrint('[FACE_DEBUG][CAPTURE][PRESSED]');
    final blockedReason = _captureBlockedReason();
    if (blockedReason != null) {
      debugPrint('[FACE_DEBUG][CAPTURE][BLOCKED] reason=$blockedReason');
      return;
    }

    _isCapturing = true;
    try {
      while (_processingFrame) {
        await Future<void>.delayed(const Duration(milliseconds: 10));
      }

      final frame = _latestFrame;
      final face = _latestFace;
      final controller = _controller;
      if (frame == null || controller == null) {
        debugPrint('[FACE_DEBUG][CAPTURE][BLOCKED] reason=no_frame');
        if (mounted) {
          setState(() {
            _status = 'Wajah belum siap untuk diambil.';
          });
        }
        return;
      }
      if (face == null) {
        debugPrint('[FACE_DEBUG][CAPTURE][BLOCKED] reason=no_face');
        if (mounted) {
          setState(() {
            _status = 'Wajah belum siap untuk diambil.';
          });
        }
        return;
      }
      if (_latestFaceCount > 1) {
        debugPrint('[FACE_DEBUG][CAPTURE][BLOCKED] reason=multiple_faces');
        if (mounted) {
          setState(() {
            _status = 'Multiple faces detected.';
          });
        }
        return;
      }

      final startedAt = DateTime.now();
      final sampleLabel = String.fromCharCode(65 + _samples.length);
      debugPrint('[FACE_DEBUG][CAPTURE][START] sample=$sampleLabel');
      debugPrint(
        '[FACE_DEBUG][CAPTURE_FRAME] '
        'sample=$sampleLabel '
        'frameId=${frame.frameId} '
        'frameAgeMs=${DateTime.now().difference(frame.capturedAt).inMilliseconds}',
      );

      _setStage(FaceDebugStage.rgbConversion, 'converting owned frame to RGB');
      final rgbImage = frame.rgbImage;
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.rgbConversion.label}] '
        'source=${rgbImage.width}x${rgbImage.height} '
        'converted=${rgbImage.width}x${rgbImage.height}',
      );

      _setStage(FaceDebugStage.faceDetection, 'reusing cached face detection');
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.faceDetection.label}] faces=1',
      );

      _setStage(FaceDebugStage.landmarkMapping, 'reusing cached landmark mapping');
      final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
      final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.landmarkMapping.label}] '
        'leftEye=${leftEye != null} rightEye=${rightEye != null} '
        'leftFinite=${_pointFinite(leftEye)} rightFinite=${_pointFinite(rightEye)}',
      );

      _setStage(FaceDebugStage.alignment, 'aligning from owned frame');
      final alignment = _converter.extractAlignedFaceCropFromRgb(
        frame.rgbImage,
        face,
      );
      final crop = alignment.crop;
      final eyeDistance = _eyeDistance(leftEye, rightEye);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.alignment.label}] '
        'source=${rgbImage.width}x${rgbImage.height} '
        'eyeDistance=${eyeDistance?.toStringAsFixed(2) ?? 'n/a'} '
        'crop=${crop.width}x${crop.height}',
      );
      debugPrint(
        '[FACE_DEBUG][ALIGNMENT_GEOMETRY] '
        'source=${alignment.sourceWidth}x${alignment.sourceHeight} '
        'eyeDistance=${eyeDistance?.toStringAsFixed(2) ?? 'n/a'} '
        'rotationDegrees=${alignment.rotationDegrees.toStringAsFixed(2)} '
        'cropBefore=${alignment.cropBeforeWidth}x${alignment.cropBeforeHeight} '
        'cropAfter=${alignment.cropAfterWidth}x${alignment.cropAfterHeight} '
        'aspectRatio=${alignment.aspectRatio.toStringAsFixed(3)} '
        'square=${alignment.square} '
        'clamped=${alignment.clamped}',
      );
      debugPrint(
        '[FACE_DEBUG][CROP_COMPOSITION] '
        'faceCenterX=${alignment.faceCenterX.toStringAsFixed(4)} '
        'faceCenterY=${alignment.faceCenterY.toStringAsFixed(4)} '
        'cropLeft=${alignment.cropLeft} '
        'cropTop=${alignment.cropTop} '
        'cropWidth=${alignment.cropWidth} '
        'cropHeight=${alignment.cropHeight} '
        'faceCenterInCropX=${(alignment.faceCenterX * alignment.cropWidth).toStringAsFixed(2)} '
        'faceCenterInCropY=${(alignment.faceCenterY * alignment.cropHeight).toStringAsFixed(2)} '
        'normalizedFaceCenterX=${alignment.faceCenterX.toStringAsFixed(4)} '
        'normalizedFaceCenterY=${alignment.faceCenterY.toStringAsFixed(4)} '
        'leftEyeInCropX=${(alignment.leftEyeX * alignment.cropWidth).toStringAsFixed(2)} '
        'leftEyeInCropY=${(alignment.leftEyeY * alignment.cropHeight).toStringAsFixed(2)} '
        'rightEyeInCropX=${(alignment.rightEyeX * alignment.cropWidth).toStringAsFixed(2)} '
        'rightEyeInCropY=${(alignment.rightEyeY * alignment.cropHeight).toStringAsFixed(2)} '
        'normalizedLeftEyeX=${alignment.leftEyeX.toStringAsFixed(4)} '
        'normalizedLeftEyeY=${alignment.leftEyeY.toStringAsFixed(4)} '
        'normalizedRightEyeX=${alignment.rightEyeX.toStringAsFixed(4)} '
        'normalizedRightEyeY=${alignment.rightEyeY.toStringAsFixed(4)}',
      );

      _setStage(FaceDebugStage.cropValidation, 'validating crop bounds');
      if (crop.width <= 0 || crop.height <= 0) {
        throw FaceCameraImageException(
          'FACE_ALIGNMENT_INVALID_CROP',
          'crop=${crop.width}x${crop.height}',
        );
      }
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.cropValidation.label}] '
        'crop=${crop.width}x${crop.height}',
      );

      _setStage(FaceDebugStage.resize112, 'preparing 112x112 sample');
      final sample = img.copyResize(
        crop,
        width: 112,
        height: 112,
        interpolation: img.Interpolation.linear,
      );
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.resize112.label}] '
        'output=${sample.width}x${sample.height}',
      );

      _setStage(FaceDebugStage.tflitePreprocess, 'encoding sample for inference');
      final sampleBytes = img.encodeJpg(sample, quality: 95);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.tflitePreprocess.label}] '
        'inputShape=[1,112,112,3] expectedElements=${112 * 112 * 3} '
        'source=${sample.width}x${sample.height} encodedBytes=${sampleBytes.length}',
      );

      _setStage(FaceDebugStage.tfliteInference, 'running TFLite inference');
      debugPrint(
        '[FACE_DEBUG][START][${FaceDebugStage.tfliteInference.label}] '
        'inputShape=[1,112,112,3] elementCount=${112 * 112 * 3}',
      );
      final inferenceStartedAt = DateTime.now();
      final embedding = await _recognitionService.generateEmbedding(sampleBytes);
      final inferenceMs = DateTime.now().difference(inferenceStartedAt).inMilliseconds;
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.tfliteInference.label}] '
        'outputDim=${embedding.length} inferenceMs=$inferenceMs',
      );

      _setStage(FaceDebugStage.embeddingValidation, 'validating embedding');
      final norm = _l2Norm(embedding);
      final allFinite = embedding.every((value) => value.isFinite);
      final nonZero = embedding.any((value) => value != 0.0);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.embeddingValidation.label}] '
        'dimension=${embedding.length} allFinite=$allFinite '
        'nonZero=$nonZero norm=${norm.toStringAsFixed(6)}',
      );

      _samples.add(
        _SampleRecord(
          label: sampleLabel,
          embedding: List<double>.unmodifiable(embedding),
          norm: norm,
        ),
      );
      _latestSampleLabel = sampleLabel;
      _lastEmbeddingSize = embedding.length;
      _lastNorm = norm;
      _lastTiming = '${DateTime.now().difference(startedAt).inMilliseconds}ms';
      _latestSimilarityStats = _computeSimilarityStats();
      _logSimilarityToA(sampleLabel);
      if (_samples.length == 5) {
        _logConsistencySummary();
      }
      _setStage(FaceDebugStage.complete, 'debug pipeline complete');
      debugPrint('[FACE_DEBUG][OK][COMPLETE] sample=$sampleLabel');

      if (mounted) {
        setState(() {
          _error = null;
          _status = 'Sample $sampleLabel captured.';
        });
      }
    } catch (error, st) {
      debugPrint(
        '[FACE_DEBUG][ERROR][${_stage.label}] ${error.runtimeType}: $error',
      );
      debugPrintStack(stackTrace: st);
      if (mounted) {
        setState(() {
          final message = error.toString().replaceFirst('Exception: ', '');
          _error = '${error.runtimeType}: $message';
          _status = 'ERROR';
        });
      }
    } finally {
      _isCapturing = false;
    }
  }

  void _resetSamples() {
    setState(() {
      _samples.clear();
      _latestSampleLabel = null;
      _latestSimilarityStats = null;
      _lastEmbeddingSize = null;
      _lastNorm = null;
      _lastTiming = null;
      _error = null;
      _status = 'Samples reset.';
    });
  }

  String? _captureBlockedReason() {
    if (!_cameraReady) {
      return 'camera_not_ready';
    }
    if (!_mlKitReady) {
      return 'mlkit_not_ready';
    }
    if (!_tfliteReady) {
      return 'model_not_ready';
    }
    if (_isCapturing) {
      return 'already_capturing';
    }
    if (_processingFrame) {
      return 'background_processing';
    }
    if (_latestFrame == null) {
      return 'no_frame';
    }
    if (_latestFace == null) {
      return 'no_face';
    }
    if (_latestFaceCount > 1) {
      return 'multiple_faces';
    }
    if (_samples.length >= 5) {
      return 'max_samples_reached';
    }
    return null;
  }

  String? _buildCaptureDisabledReason({required bool faceReady}) {
    final reason = _captureBlockedReason();
    if (reason == null) {
      return null;
    }
    return switch (reason) {
      'camera_not_ready' => 'Capture disabled: camera not ready',
      'mlkit_not_ready' => 'Capture disabled: ML Kit not ready',
      'model_not_ready' => 'Capture disabled: model not ready',
      'already_capturing' => 'Capture disabled: already capturing',
      'background_processing' => 'Capture disabled: processing',
      'no_frame' => 'Capture disabled: no valid owned frame',
      'no_face' => 'Capture disabled: no valid face',
      'multiple_faces' => 'Capture disabled: multiple faces',
      'max_samples_reached' => 'Capture disabled: max samples reached',
      _ => faceReady ? 'Capture disabled: processing' : 'Capture disabled: no valid face',
    };
  }

  String _frameFreshnessLabel() {
    final frame = _latestFrame;
    final capturedAt = _latestFrameCapturedAt;
    if (frame == null || capturedAt == null) {
      return 'frameId=- frameAgeMs=n/a';
    }
    final ageMs = DateTime.now().difference(capturedAt).inMilliseconds;
    return 'frameId=${frame.frameId} frameAgeMs=$ageMs';
  }

  Map<String, double> _computeSimilarityStats() {
    if (_samples.length < 2) {
      return {};
    }

    final similarities = <String, double>{};
    final first = _samples.first;
    for (var i = 1; i < _samples.length; i++) {
      final other = _samples[i];
      final similarity = _cosineSimilarity(first.embedding, other.embedding);
      similarities['${first.label}${other.label}'] = similarity;
    }

    final values = similarities.values.toList(growable: false)..sort();
    final sum = values.fold<double>(0.0, (acc, value) => acc + value);
    similarities['min'] = values.first;
    similarities['max'] = values.last;
    similarities['average'] = sum / values.length;
    similarities['median'] = values[values.length ~/ 2];
    return similarities;
  }

  double _cosineSimilarity(List<double> a, List<double> b) {
    if (a.length != b.length) {
      throw StateError('Cosine similarity requires equal dimensions');
    }
    if (a.isEmpty) {
      throw StateError('Cosine similarity requires non-empty vectors');
    }
    var dot = 0.0;
    var normA = 0.0;
    var normB = 0.0;
    for (var i = 0; i < a.length; i++) {
      dot += a[i] * b[i];
      normA += a[i] * a[i];
      normB += b[i] * b[i];
    }
    if (!dot.isFinite || !normA.isFinite || !normB.isFinite) {
      throw StateError('Cosine similarity requires finite vectors');
    }
    if (normA <= 0 || normB <= 0) {
      throw StateError('Cosine similarity requires non-zero norms');
    }
    final denom = math.sqrt(normA) * math.sqrt(normB);
    if (denom <= 0 || !denom.isFinite) {
      throw StateError('Cosine similarity denominator invalid');
    }
    final value = dot / denom;
    return value.clamp(-1.0, 1.0).toDouble();
  }

  void _logSimilarityToA(String sampleLabel) {
    if (_samples.length < 2) {
      return;
    }
    final first = _samples.first;
    final current = _samples.last;
    final similarity = _cosineSimilarity(first.embedding, current.embedding);
    current.cosineToA = similarity;
    debugPrint(
      '[FACE_DEBUG][SIMILARITY] ${first.label}-${current.label}=${similarity.toStringAsFixed(6)}',
    );
  }

  void _logConsistencySummary() {
    if (_samples.length < 2) {
      return;
    }
    final values = <double>[];
    final first = _samples.first;
    for (var i = 1; i < _samples.length; i++) {
      values.add(_cosineSimilarity(first.embedding, _samples[i].embedding));
    }
    if (values.isEmpty) {
      return;
    }
    final sorted = values.toList()..sort();
    final mean = values.fold<double>(0.0, (acc, value) => acc + value) / values.length;
    final summary = <String, double>{
      'AB': _samples.length >= 2 ? _cosineSimilarity(first.embedding, _samples[1].embedding) : 0.0,
      'AC': _samples.length >= 3 ? _cosineSimilarity(first.embedding, _samples[2].embedding) : 0.0,
      'AD': _samples.length >= 4 ? _cosineSimilarity(first.embedding, _samples[3].embedding) : 0.0,
      'AE': _samples.length >= 5 ? _cosineSimilarity(first.embedding, _samples[4].embedding) : 0.0,
      'min': sorted.first,
      'max': sorted.last,
      'mean': mean,
    };
    _latestSimilarityStats = summary;
    debugPrint(
      '[FACE_DEBUG][CONSISTENCY_SUMMARY] '
      'AB=${summary['AB']!.toStringAsFixed(6)} '
      'AC=${summary['AC']!.toStringAsFixed(6)} '
      'AD=${summary['AD']!.toStringAsFixed(6)} '
      'AE=${summary['AE']!.toStringAsFixed(6)} '
      'min=${summary['min']!.toStringAsFixed(6)} '
      'max=${summary['max']!.toStringAsFixed(6)} '
      'mean=${summary['mean']!.toStringAsFixed(6)}',
    );
  }

  void _setStage(
    FaceDebugStage stage,
    String action, [
    Map<String, Object?> meta = const {},
  ]) {
    _stage = stage;
    final metaText = meta.isEmpty
        ? ''
        : ' ${meta.entries.map((entry) => '${entry.key}=${entry.value}').join(' ')}';
    debugPrint('[FACE_DEBUG][START][${stage.label}] $action$metaText');
  }

  bool _pointFinite(math.Point<int>? point) {
    if (point == null) {
      return false;
    }
    return point.x.isFinite && point.y.isFinite;
  }

  double? _eyeDistance(math.Point<int>? leftEye, math.Point<int>? rightEye) {
    if (!_pointFinite(leftEye) || !_pointFinite(rightEye)) {
      return null;
    }
    final left = leftEye!;
    final right = rightEye!;
    final dx = right.x - left.x;
    final dy = right.y - left.y;
    return math.sqrt((dx * dx) + (dy * dy));
  }

  double _l2Norm(List<double> values) {
    var sum = 0.0;
    for (final value in values) {
      sum += value * value;
    }
    return sum <= 0 ? 0.0 : math.sqrt(sum);
  }

  @override
  void dispose() {
    _controller?.dispose();
    _detector?.close();
    _recognitionService.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!kDebugMode) {
      return const Scaffold(
        body: Center(
          child: Text('Debug harness disabled.'),
        ),
      );
    }

    final disabledReason = _buildCaptureDisabledReason(
      faceReady: _latestFace != null &&
          _latestFace!.landmarks[FaceLandmarkType.leftEye]?.position != null &&
          _latestFace!.landmarks[FaceLandmarkType.rightEye]?.position != null,
    );
    final previewAspectRatio = _cameraPreviewAspectRatio();
    final previewSize = _controller?.value.previewSize;
    final previewWidth = previewSize?.height ?? 720;
    final previewHeight = previewSize?.width ?? 480;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Face Debug Harness'),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _CameraPreviewFrame(
                controller: _controller,
                aspectRatio: previewAspectRatio,
                previewWidth: previewWidth.toDouble(),
                previewHeight: previewHeight.toDouble(),
              ),
              const SizedBox(height: 12),
              Expanded(
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      _DiagnosticTile(label: 'Camera', value: _cameraReady ? 'ready' : 'failed'),
                      _DiagnosticTile(label: 'ML Kit', value: _mlKitReady ? 'ready' : 'failed'),
                      _DiagnosticTile(label: 'TFLite', value: _tfliteReady ? 'ready' : 'failed'),
                      _DiagnosticTile(label: 'Stage', value: _stage.label),
                      _DiagnosticTile(label: 'Status', value: _status),
                      _DiagnosticTile(label: 'Preview Size', value: _previewDebugLabel()),
                      _DiagnosticTile(label: 'Frame Freshness', value: _frameFreshnessLabel()),
                      if (disabledReason != null) _DiagnosticTile(label: 'Capture', value: disabledReason),
                      if (_error != null) _DiagnosticTile(label: 'Error', value: _error!),
                      _DiagnosticTile(label: 'Sample', value: _latestSampleLabel ?? '-'),
                      if (_samples.isNotEmpty)
                        _DiagnosticTile(
                          label: 'Cosine to A',
                          value: _latestSampleLabel == 'A'
                              ? '-'
                              : (_samples.last.cosineToA?.toStringAsFixed(6) ?? '-'),
                        ),
                      _DiagnosticTile(label: 'Embedding Size', value: '${_lastEmbeddingSize ?? '-'}'),
                      _DiagnosticTile(label: 'Embedding Norm', value: _lastNorm?.toStringAsFixed(4) ?? '-'),
                      _DiagnosticTile(label: 'Timing', value: _lastTiming ?? '-'),
                      if (_latestSimilarityStats != null && _latestSimilarityStats!.isNotEmpty)
                        _SimilarityPanel(stats: _latestSimilarityStats!),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: FilledButton(
                              onPressed: disabledReason == null ? _captureSample : null,
                              child: Text(
                                disabledReason ?? (_isCapturing ? 'Capturing...' : 'Capture Sample'),
                              ),
                            ),
                          ),
                          const SizedBox(width: 12),
                          OutlinedButton(
                            onPressed: _samples.isEmpty ? null : _resetSamples,
                            child: const Text('Reset Samples'),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  double _cameraPreviewAspectRatio() {
    final size = _controller?.value.previewSize;
    if (size == null || size.height == 0) {
      return 3 / 4;
    }
    return size.width / size.height;
  }

  String _previewDebugLabel() {
    final size = _controller?.value.previewSize;
    final orientation = _controller?.value.deviceOrientation.name ?? 'unknown';
    final cameraRatio = size == null || size.height == 0 ? 'n/a' : (size.width / size.height).toStringAsFixed(3);
    final visualRatio = _cameraPreviewAspectRatio().toStringAsFixed(3);
    return 'preview=${size == null ? 'n/a' : '${size.width.toInt()}x${size.height.toInt()}'} '
        'orientation=$orientation '
        'cameraRatio=$cameraRatio '
        'visualRatio=$visualRatio';
  }
}

class _CameraPreviewFrame extends StatelessWidget {
  const _CameraPreviewFrame({
    required this.controller,
    required this.aspectRatio,
    required this.previewWidth,
    required this.previewHeight,
  });

  final CameraController? controller;
  final double aspectRatio;
  final double previewWidth;
  final double previewHeight;

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(18),
      child: Container(
        color: Colors.black,
        height: 320,
        width: double.infinity,
        child: controller == null || !controller!.value.isInitialized
            ? const Center(
                child: Text(
                  'Camera preview unavailable',
                  style: TextStyle(color: Colors.white),
                ),
              )
            : FittedBox(
                fit: BoxFit.cover,
                alignment: Alignment.center,
                child: SizedBox(
                  width: previewWidth,
                  height: previewHeight,
                  child: CameraPreview(controller!),
                ),
              ),
      ),
    );
  }
}

class _OwnedCameraFrame {
  _OwnedCameraFrame({
    required this.rgbImage,
    required this.frameId,
    required this.capturedAt,
    required this.orientation,
  });

  final img.Image rgbImage;
  final int frameId;
  final DateTime capturedAt;
  final DeviceOrientation orientation;

  factory _OwnedCameraFrame.fromCameraImage(
    img.Image rgbImage, {
    required int frameId,
    required DateTime capturedAt,
    required DeviceOrientation deviceOrientation,
  }) {
    return _OwnedCameraFrame(
      rgbImage: rgbImage,
      frameId: frameId,
      capturedAt: capturedAt,
      orientation: deviceOrientation,
    );
  }
}

class _SampleRecord {
  _SampleRecord({
    required this.label,
    required this.embedding,
    required this.norm,
  });

  final String label;
  final List<double> embedding;
  final double norm;
  double? cosineToA;
}

class _SimilarityPanel extends StatelessWidget {
  const _SimilarityPanel({required this.stats});

  final Map<String, double> stats;

  @override
  Widget build(BuildContext context) {
    final pairs = stats.entries.where((entry) {
      final key = entry.key;
      return key.length == 2 && key.codeUnits.every((c) => c >= 65 && c <= 90);
    }).toList();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        const SizedBox(height: 4),
        const Text(
          'Cosine Similarity',
          style: TextStyle(fontWeight: FontWeight.w800),
        ),
        const SizedBox(height: 8),
        for (final entry in pairs)
          Padding(
            padding: const EdgeInsets.only(bottom: 6),
            child: Text('${entry.key}: ${entry.value.toStringAsFixed(4)}'),
          ),
        if (stats.containsKey('min')) Text('min: ${stats['min']!.toStringAsFixed(4)}'),
        if (stats.containsKey('max')) Text('max: ${stats['max']!.toStringAsFixed(4)}'),
        if (stats.containsKey('average')) Text('average: ${stats['average']!.toStringAsFixed(4)}'),
        if (stats.containsKey('median')) Text('median: ${stats['median']!.toStringAsFixed(4)}'),
      ],
    );
  }
}

class _DiagnosticTile extends StatelessWidget {
  const _DiagnosticTile({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: DecoratedBox(
        decoration: BoxDecoration(
          color: const Color(0xFFF4F6F5),
          borderRadius: BorderRadius.circular(14),
        ),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
          child: Row(
            children: [
              Text(
                '$label:',
                style: const TextStyle(fontWeight: FontWeight.w800),
              ),
              const SizedBox(width: 10),
              Expanded(child: Text(value)),
            ],
          ),
        ),
      ),
    );
  }
}
