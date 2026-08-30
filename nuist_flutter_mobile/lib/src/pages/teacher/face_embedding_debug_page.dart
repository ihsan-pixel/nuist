import 'dart:math' as math;
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:image/image.dart' as img;

import '../../services/face_camera_image_converter.dart';
import '../../services/face_recognition_service.dart';

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
      final inputImage = _converter.toInputImage(
        image,
        _controller!.description,
        _controller!.value.deviceOrientation,
      );
      final faces = await _detector!.processImage(inputImage);
      if (faces.isEmpty) {
        if (mounted) {
          setState(() {
            _status = 'No face detected.';
          });
        }
        return;
      }

      final face = faces.first;
      final crop = _converter.extractAlignedFaceCrop(
        image,
        face,
        orientation: _controller!.value.deviceOrientation,
      );
      final cropBytes = img.encodeJpg(crop, quality: 95);
      final startedAt = DateTime.now();
      final embedding = await _recognitionService.generateEmbedding(cropBytes);
      final elapsedMs = DateTime.now().difference(startedAt).inMilliseconds;
      final norm = _l2Norm(embedding);

      if (!mounted) {
        return;
      }
      setState(() {
        _lastNorm = norm;
        _lastEmbeddingSize = embedding.length;
        _lastTiming = '${elapsedMs}ms';
        _status = 'Face detected and embedding validated.';
      });
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error ?? 'Frame processing failed.';
      });
    } finally {
      _processingFrame = false;
    }
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
