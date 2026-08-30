import 'dart:math' as math;

import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
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
  FaceDebugStage _stage = FaceDebugStage.cameraFrame;
  String _status = 'Initializing debug harness...';
  String? _error;
  double? _lastNorm;
  int? _lastEmbeddingSize;
  String? _lastTiming;

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
      if (faces.isEmpty) {
        if (mounted) {
          setState(() {
            _status = 'No face detected.';
          });
        }
        return;
      }

      final face = faces.first;
      final box = face.boundingBox;
      debugPrint(
        '[FACE_DEBUG][FACE_BOX] '
        'left=${box.left.toStringAsFixed(2)} '
        'top=${box.top.toStringAsFixed(2)} '
        'width=${box.width.toStringAsFixed(2)} '
        'height=${box.height.toStringAsFixed(2)}',
      );

      _setStage(FaceDebugStage.frameCopy, 'copying camera frame to RGB path');
      debugPrint('[FACE_DEBUG][OK][${FaceDebugStage.frameCopy.label}]');

      _setStage(FaceDebugStage.rgbConversion, 'converting camera frame to RGB');
      final rgbImage = _converter.convertToRgbImage(image);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.rgbConversion.label}] '
        'source=${image.width}x${image.height} converted=${rgbImage.width}x${rgbImage.height}',
      );

      _setStage(FaceDebugStage.landmarkMapping, 'checking landmarks for alignment');
      final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position;
      final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position;
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.landmarkMapping.label}] '
        'leftEye=${leftEye != null} '
        'rightEye=${rightEye != null} '
        'leftFinite=${_pointFinite(leftEye)} '
        'rightFinite=${_pointFinite(rightEye)}',
      );

      _setStage(FaceDebugStage.alignment, 'aligning and cropping face');
      final crop = _converter.extractAlignedFaceCrop(
        image,
        face,
        orientation: _controller!.value.deviceOrientation,
      );
      final eyeDistance = _eyeDistance(leftEye, rightEye);
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.alignment.label}] '
        'source=${image.width}x${image.height} '
        'eyeDistance=${eyeDistance?.toStringAsFixed(2) ?? 'n/a'} '
        'crop=${crop.width}x${crop.height}',
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

      _setStage(FaceDebugStage.resize112, 'encoding crop for model input');
      if (crop.width <= 0 || crop.height <= 0) {
        throw FaceCameraImageException(
          'FACE_ALIGNMENT_INVALID_CROP',
          'crop=${crop.width}x${crop.height}',
        );
      }
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.resize112.label}] '
        'aligned=${crop.width}x${crop.height}',
      );

      final cropBytes = img.encodeJpg(crop, quality: 95);
      _setStage(FaceDebugStage.tflitePreprocess, 'preparing TFLite input');
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.tflitePreprocess.label}] '
        'inputShape=[1,112,112,3] expectedElements=${112 * 112 * 3} '
        'encodedBytes=${cropBytes.length}',
      );

      _setStage(FaceDebugStage.tfliteInference, 'running TFLite inference');
      final startedAt = DateTime.now();
      final embedding = await _recognitionService.generateEmbedding(cropBytes);
      final elapsedMs = DateTime.now().difference(startedAt).inMilliseconds;
      debugPrint(
        '[FACE_DEBUG][OK][${FaceDebugStage.tfliteInference.label}] '
        'outputDim=${embedding.length} inferenceMs=$elapsedMs',
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
      _setStage(FaceDebugStage.complete, 'debug pipeline complete');

      if (!mounted) {
        return;
      }
      setState(() {
        _lastNorm = norm;
        _lastEmbeddingSize = embedding.length;
        _lastTiming = '${elapsedMs}ms';
        _status = 'Face detected and embedding validated.';
      });
    } catch (error, st) {
      debugPrint(
        '[FACE_DEBUG][ERROR][${_stage.label}] '
        '${error.runtimeType}: $error',
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

    return Scaffold(
      appBar: AppBar(
        title: const Text('Face Debug Harness'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _DiagnosticTile(label: 'Camera', value: _cameraReady ? 'ready' : 'failed'),
            _DiagnosticTile(label: 'ML Kit', value: _mlKitReady ? 'ready' : 'failed'),
            _DiagnosticTile(label: 'TFLite', value: _tfliteReady ? 'ready' : 'failed'),
            _DiagnosticTile(label: 'Stage', value: _stage.label),
            _DiagnosticTile(label: 'Status', value: _status),
            if (_error != null) _DiagnosticTile(label: 'Error', value: _error!),
            _DiagnosticTile(label: 'Embedding Size', value: '${_lastEmbeddingSize ?? '-'}'),
            _DiagnosticTile(label: 'Embedding Norm', value: _lastNorm?.toStringAsFixed(4) ?? '-'),
            _DiagnosticTile(label: 'Timing', value: _lastTiming ?? '-'),
            const SizedBox(height: 12),
            Expanded(
              child: ClipRRect(
                borderRadius: BorderRadius.circular(20),
                child: Container(
                  color: Colors.black,
                  child: _controller == null || !_controller!.value.isInitialized
                      ? const Center(
                          child: Text(
                            'Camera preview unavailable',
                            style: TextStyle(color: Colors.white),
                          ),
                        )
                      : CameraPreview(_controller!),
                ),
              ),
            ),
          ],
        ),
      ),
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
