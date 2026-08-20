import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'dart:math' as math;
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:flutter/services.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../services/face_embedding_service.dart';

const _enrollPrimary = Color(0xFF00745A);
const _enrollPrimarySoft = Color(0xFFEAF6F1);
const _enrollPrimaryBorder = Color(0xFFD7E5DE);
const _enrollText = Color(0xFF172A24);
const _enrollMuted = Color(0xFF64746E);

class AttendanceFaceEnrollmentPage extends StatefulWidget {
  const AttendanceFaceEnrollmentPage({
    super.key,
    required this.repository,
    required this.title,
    required this.description,
    this.currentUserId,
  });

  final TeacherMobileRepository repository;
  final String title;
  final String description;
  final int? currentUserId;

  @override
  State<AttendanceFaceEnrollmentPage> createState() =>
      _AttendanceFaceEnrollmentPageState();
}

class _AttendanceFaceEnrollmentPageState
    extends State<AttendanceFaceEnrollmentPage> {
  CameraController? _controller;
  FaceDetector? _detector;
  final FaceEmbeddingService _embeddingService = FaceEmbeddingService();
  bool _loading = true;
  bool _processingFrame = false;
  bool _hasFace = false;
  bool _readyToCapture = false;
  bool _submitting = false;
  int _stableFrames = 0;
  int _sampleCount = 0;
  String _poseInstruction = 'Arahkan wajah ke dalam lingkaran.';
  String _nextStepHint = 'Tahan sebentar agar sistem mengambil sample otomatis.';
  Face? _latestFace;
  Size? _latestImageSize;
  Timer? _autoCaptureTimer;
  bool _autoCaptureArmed = false;
  String _status = 'Menyiapkan kamera depan...';
  String? _error;
  DateTime _lastFrameAt = DateTime.fromMillisecondsSinceEpoch(0);
  DateTime _lastSampleAt = DateTime.fromMillisecondsSinceEpoch(0);

  @override
  void initState() {
    super.initState();
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

      if (!mounted) {
        await detector.close();
        await controller.dispose();
        return;
      }

      setState(() {
        _controller = controller;
        _detector = detector;
        _loading = false;
        _status = 'Hadapkan wajah Anda ke dalam bingkai.';
      });

      await controller.startImageStream(_processCameraImage);
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _loading = false;
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error ?? 'Kamera gagal dibuka.';
      });
    }
  }

  Future<void> _processCameraImage(CameraImage image) async {
    if (_processingFrame || _detector == null || _controller == null) {
      return;
    }

    final now = DateTime.now();
    if (now.difference(_lastFrameAt).inMilliseconds < 180) {
      return;
    }
    _lastFrameAt = now;

    _processingFrame = true;
    try {
      final inputImage = _toInputImage(
        image,
        _controller!.description,
        _controller!.value.deviceOrientation,
      );
      final faces = await _detector!.processImage(inputImage);
      final face = faces.isNotEmpty ? faces.first : null;
      final ready = _isFaceReady(face);
      final quality = _evaluateFaceQuality(face, image);
      final sampleReady = face != null && ready && quality['passed'] == true;

      if (!mounted) {
        return;
      }

      setState(() {
        _latestFace = face;
        _latestImageSize = Size(image.width.toDouble(), image.height.toDouble());
        _hasFace = face != null;
        _readyToCapture = ready;
        _stableFrames = ready ? _stableFrames + 1 : 0;
        _poseInstruction = _instructionForQuality(quality, face);
        _nextStepHint = _nextHintForSampleState();
        if (face == null) {
          _status = 'Wajah belum terdeteksi. Posisikan wajah di tengah bingkai.';
        } else if (ready) {
          _status = _sampleCount >= 3
              ? 'Wajah stabil. Hampir selesai.'
              : 'Wajah stabil. Tahan sebentar.';
        } else {
          _status = _instructionForQuality(quality, face);
        }
        if (sampleReady &&
            _sampleCount < 5 &&
            now.difference(_lastSampleAt).inMilliseconds >= 600) {
          _lastSampleAt = now;
          _sampleCount += 1;
        }
      });

      _scheduleAutoCapture();
    } catch (_) {
      if (mounted) {
        setState(() {
          _error = 'Gagal membaca frame kamera.';
          _status = _error!;
        });
      }
    } finally {
      _processingFrame = false;
    }
  }

  void _scheduleAutoCapture() {
    if (!_readyToCapture || _loading || _submitting) {
      _autoCaptureTimer?.cancel();
      _autoCaptureArmed = false;
      return;
    }

    if (_stableFrames < 8 || _autoCaptureArmed) {
      return;
    }

    _autoCaptureArmed = true;
    _autoCaptureTimer?.cancel();
    _autoCaptureTimer = Timer(const Duration(milliseconds: 650), () {
      if (mounted && _readyToCapture && !_loading && !_submitting && _sampleCount >= 3) {
        unawaited(_captureAndEnroll());
      } else {
        _autoCaptureArmed = false;
      }
    });
  }

  bool _isFaceReady(Face? face) {
    if (face == null) {
      return false;
    }
    final yaw = (face.headEulerAngleY ?? 0).abs();
    final pitch = (face.headEulerAngleX ?? 0).abs();
    final roll = (face.headEulerAngleZ ?? 0).abs();
    final leftEye = face.leftEyeOpenProbability ?? 1.0;
    final rightEye = face.rightEyeOpenProbability ?? 1.0;
    return yaw <= 12 && pitch <= 12 && roll <= 12 && leftEye >= 0.2 && rightEye >= 0.2;
  }

  InputImage _toInputImage(
    CameraImage image,
    CameraDescription camera,
    DeviceOrientation orientation,
  ) {
    final rotation = _rotationFromOrientation(camera, orientation);
    final bytes = _convertToNv21(image);
    final metadata = InputImageMetadata(
      size: Size(image.width.toDouble(), image.height.toDouble()),
      rotation: rotation,
      format: InputImageFormat.nv21,
      bytesPerRow: image.planes.first.bytesPerRow,
    );
    return InputImage.fromBytes(
      bytes: bytes,
      metadata: metadata,
    );
  }

  Uint8List _convertToNv21(CameraImage image) {
    final yPlane = image.planes[0];
    final uPlane = image.planes[1];
    final vPlane = image.planes[2];
    final int ySize = image.width * image.height;
    final int uvSize = image.width * image.height ~/ 2;
    final bytes = Uint8List(ySize + uvSize);

    var offset = 0;
    for (var row = 0; row < image.height; row++) {
      final rowStart = row * yPlane.bytesPerRow;
      bytes.setRange(
        offset,
        offset + image.width,
        yPlane.bytes,
        rowStart,
      );
      offset += image.width;
    }

    final chromaHeight = image.height ~/ 2;
    final chromaWidth = image.width ~/ 2;
    for (var row = 0; row < chromaHeight; row++) {
      for (var col = 0; col < chromaWidth; col++) {
        final vIndex = row * vPlane.bytesPerRow + col * vPlane.bytesPerPixel!;
        final uIndex = row * uPlane.bytesPerRow + col * uPlane.bytesPerPixel!;
        bytes[offset++] = vPlane.bytes[vIndex];
        bytes[offset++] = uPlane.bytes[uIndex];
      }
    }

    return bytes;
  }

  InputImageRotation _rotationFromOrientation(
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

  Future<void> _captureAndEnroll() async {
    if (_loading || _controller == null || !_readyToCapture || _submitting) {
      return;
    }

    setState(() {
      _submitting = true;
      _error = null;
      _status = 'Menyimpan data wajah...';
    });

    try {
      await _controller?.stopImageStream();
      final file = await _controller!.takePicture();
      final processed = await _analyzeImage(file.path);
      if (processed == null) {
        throw Exception(
          'Wajah tidak valid. Pastikan wajah terlihat jelas lalu ulangi.',
        );
      }

      final userId = widget.currentUserId;
      if (userId == null) {
        throw Exception('User ID tidak ditemukan dari sesi aktif. Silakan login ulang.');
      }

      final embedding = await _embeddingService.extractEmbedding(File(file.path));
      final embeddingValidation = _validateEmbedding(embedding);
      if (!embeddingValidation['valid']) {
        throw Exception(
          'Data wajah tidak dapat diproses. Silakan ulangi.',
        );
      }

      final result = await widget.repository.enrollFace(
        payload: {
          'user_id': userId,
          'face_embedding': embeddingValidation['embedding'],
          'liveness_score': processed['liveness_score'],
          'liveness_challenges': processed['liveness_challenges'],
          'device_info': 'flutter_mobile_face_enrollment',
        },
      );

      if (!mounted) {
        return;
      }
      await showDialog<void>(
        context: context,
        barrierDismissible: false,
        builder: (dialogContext) {
          return AlertDialog(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(20),
            ),
            title: const Text('Daftar Wajah Berhasil'),
            content: Text(
              (result['_message'] as String?) ??
                  'Data wajah berhasil disimpan dan siap digunakan untuk presensi.',
            ),
            actions: [
              FilledButton(
                onPressed: () => Navigator.of(dialogContext).pop(),
                child: const Text('Lanjut'),
              ),
            ],
          );
        },
      );

      if (!mounted) {
        return;
      }
      Navigator.of(context).pop(result);
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error!;
      });
      try {
        if (_controller != null && !_controller!.value.isStreamingImages) {
          await _controller?.startImageStream(_processCameraImage);
        }
      } catch (_) {}
    } finally {
      _autoCaptureArmed = false;
      _autoCaptureTimer?.cancel();
      if (mounted) {
        setState(() {
          _submitting = false;
        });
      }
    }
  }

  Future<Map<String, dynamic>?> _analyzeImage(String path) async {
    final detector = FaceDetector(
      options: FaceDetectorOptions(
        enableClassification: true,
        enableLandmarks: true,
        performanceMode: FaceDetectorMode.accurate,
      ),
    );

    try {
      final faces = await detector.processImage(InputImage.fromFilePath(path));
      if (faces.isEmpty) {
        return null;
      }

      final face = faces.first;
      final liveness = _estimateLiveness(face);
      return {
        'selfie_data': 'data:image/jpeg;base64,${base64Encode(await File(path).readAsBytes())}',
        'face_descriptor': _buildDescriptor(face),
        'liveness_score': liveness['score'],
        'liveness_challenges': liveness['challenges'],
      };
    } finally {
      await detector.close();
    }
  }

  List<double> _buildDescriptor(Face face) {
    final box = face.boundingBox;
    final landmarks = face.landmarks;
    final width = box.width <= 0 ? 1.0 : box.width;
    final height = box.height <= 0 ? 1.0 : box.height;
    double normX(double x) => (x - box.left) / width;
    double normY(double y) => (y - box.top) / height;

    final points = <double>[
      box.left,
      box.top,
      box.width,
      box.height,
      face.headEulerAngleX ?? 0,
      face.headEulerAngleY ?? 0,
      face.headEulerAngleZ ?? 0,
      face.leftEyeOpenProbability ?? 0,
      face.rightEyeOpenProbability ?? 0,
      _landmarkValue(landmarks[FaceLandmarkType.leftEye], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.rightEye], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.noseBase], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.leftMouth], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.rightMouth], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.bottomMouth], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.leftEar], normX, normY),
      _landmarkValue(landmarks[FaceLandmarkType.rightEar], normX, normY),
    ];

    final descriptor = <double>[];
    for (var i = 0; i < 128; i++) {
      descriptor.add(points[i % points.length]);
    }
    return descriptor;
  }

  String _instructionForQuality(Map<String, dynamic> quality, Face? face) {
    if (face == null) {
      return 'Arahkan wajah ke dalam lingkaran.';
    }

    final issue = quality['issue'] as String?;
    switch (issue) {
      case 'too_far':
        return 'Terlalu jauh dari kamera.';
      case 'too_close':
        return 'Terlalu dekat dari kamera.';
      case 'blur':
        return 'Tahan posisi sebentar.';
      case 'lighting':
        return 'Pencahayaan kurang.';
      case 'off_center':
        return 'Pusatkan wajah ke tengah.';
      default:
        return quality['passed'] == true
            ? 'Posisi sudah bagus.'
            : 'Arahkan wajah ke dalam lingkaran.';
    }
  }

  String _nextHintForSampleState() {
    if (_sampleCount >= 5) {
      return 'Sample cukup. Menyimpan data wajah...';
    }
    if (_sampleCount >= 3) {
      return 'Hampir selesai. Tahan sebentar lagi.';
    }
    return 'Tahan sebentar agar sistem mengambil sample otomatis.';
  }

  Map<String, dynamic> _evaluateFaceQuality(Face? face, CameraImage image) {
    if (face == null) {
      return {'passed': false, 'issue': 'no_face', 'score': 0.0};
    }

    final box = face.boundingBox;
    final imageWidth = image.width.toDouble();
    final imageHeight = image.height.toDouble();
    final faceArea = box.width * box.height;
    final frameArea = imageWidth * imageHeight;
    final areaRatio = frameArea <= 0 ? 0.0 : faceArea / frameArea;
    final centerX = box.center.dx / imageWidth;
    final centerY = box.center.dy / imageHeight;
    final yaw = (face.headEulerAngleY ?? 0).abs();
    final pitch = (face.headEulerAngleX ?? 0).abs();
    final roll = (face.headEulerAngleZ ?? 0).abs();
    final eyeLeft = face.leftEyeOpenProbability ?? 1.0;
    final eyeRight = face.rightEyeOpenProbability ?? 1.0;

    if (areaRatio < 0.03) {
      return {'passed': false, 'issue': 'too_far', 'score': areaRatio};
    }
    if (areaRatio > 0.42) {
      return {'passed': false, 'issue': 'too_close', 'score': areaRatio};
    }
    if ((centerX - 0.5).abs() > 0.18 || (centerY - 0.5).abs() > 0.18) {
      return {'passed': false, 'issue': 'off_center', 'score': areaRatio};
    }
    if (yaw > 18 || pitch > 18 || roll > 12) {
      return {'passed': false, 'issue': 'pose', 'score': areaRatio};
    }
    if (eyeLeft < 0.2 || eyeRight < 0.2) {
      return {'passed': false, 'issue': 'blink', 'score': areaRatio};
    }

    return {'passed': true, 'issue': null, 'score': 1.0};
  }

  Map<String, dynamic> _validateEmbedding(List<double>? embedding) {
    if (embedding == null || embedding.length != 128) {
      return {'valid': false, 'embedding': null};
    }

    final normalized = embedding.map((value) => value.toDouble()).toList(growable: false);
    if (normalized.any((value) => value.isNaN || value.isInfinite)) {
      return {'valid': false, 'embedding': null};
    }

    final min = normalized.reduce((a, b) => a < b ? a : b);
    final max = normalized.reduce((a, b) => a > b ? a : b);
    var sumSquares = 0.0;
    for (final value in normalized) {
      sumSquares += value * value;
    }
    final norm = math.sqrt(sumSquares);
    if (norm <= 0) {
      return {'valid': false, 'embedding': null};
    }

    final unit = normalized.map((value) => value / norm).toList(growable: false);
    return {
      'valid': true,
      'embedding': unit,
      'embedding_dimension': unit.length,
      'embedding_min': min,
      'embedding_max': max,
      'embedding_norm': norm,
    };
  }

  double _landmarkValue(
    FaceLandmark? landmark,
    double Function(double x) normX,
    double Function(double y) normY,
  ) {
    final position = landmark?.position;
    if (position == null) {
      return 0;
    }
    return (normX(position.x.toDouble()) + normY(position.y.toDouble())) / 2.0;
  }

  Map<String, dynamic> _estimateLiveness(Face face) {
    final challenges = <String>[];
    var score = 0.45;
    final leftEye = face.leftEyeOpenProbability ?? 0.0;
    final rightEye = face.rightEyeOpenProbability ?? 0.0;
    if (leftEye < 0.35 || rightEye < 0.35) {
      score += 0.25;
      challenges.add('blink');
    }
    final turnY = face.headEulerAngleY ?? 0.0;
    if (turnY > 8 || turnY < -8) {
      score += 0.2;
      challenges.add(turnY > 0 ? 'turn_left' : 'turn_right');
    }
    final tiltZ = (face.headEulerAngleZ ?? 0.0).abs();
    if (tiltZ > 5) {
      score += 0.1;
      challenges.add('head_tilt');
    }
    return {
      'score': score.clamp(0.0, 1.0),
      'challenges': challenges,
    };
  }

  @override
  void dispose() {
    _autoCaptureTimer?.cancel();
    unawaited(_controller?.stopImageStream());
    _controller?.dispose();
    _detector?.close();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7F6),
      appBar: AppBar(
        backgroundColor: const Color(0xFFF5F7F6),
        foregroundColor: _enrollText,
        elevation: 0,
        titleSpacing: 0,
        title: Text(
          widget.title,
          style: const TextStyle(
            color: _enrollText,
            fontWeight: FontWeight.w800,
          ),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.fromLTRB(20, 8, 20, 20),
          child: Column(
            children: [
              const SizedBox(height: 18),
              _FaceHeroCard(
                controller: _controller,
                loading: _loading && _controller == null,
                hasFace: _hasFace,
                readyToCapture: _readyToCapture,
                stableFrames: _stableFrames,
                progress: _enrollmentProgress,
                latestFace: _latestFace,
                latestImageSize: _latestImageSize,
              ),
              const SizedBox(height: 24),
              Text(
                widget.description,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: _enrollMuted,
                  fontSize: 13,
                  height: 1.4,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 12),
              Text(
                _error ?? _status,
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: _error != null ? Colors.red : _enrollText,
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  height: 1.35,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                _poseInstruction,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: _enrollMuted,
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                _nextStepHint,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: _enrollPrimary,
                  fontSize: 11.5,
                  fontWeight: FontWeight.w800,
                ),
              ),
              const Spacer(),
              FilledButton(
                onPressed: (_loading || !_readyToCapture || _submitting)
                    ? null
                    : _captureAndEnroll,
                style: FilledButton.styleFrom(
                  backgroundColor: _enrollPrimary,
                  foregroundColor: Colors.white,
                  minimumSize: const Size.fromHeight(52),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                ),
                child: Text(
                  _loading || _submitting
                      ? 'Memproses...'
                      : _readyToCapture
                          ? 'Continue'
                          : 'Menunggu Wajah Stabil',
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  double get _enrollmentProgress {
    return (_sampleCount / 5.0).clamp(0.0, 1.0);
  }
}

class _FaceHeroCard extends StatelessWidget {
  const _FaceHeroCard({
    required this.controller,
    required this.loading,
    required this.hasFace,
    required this.readyToCapture,
    required this.stableFrames,
    required this.progress,
    required this.latestFace,
    required this.latestImageSize,
  });

  final CameraController? controller;
  final bool loading;
  final bool hasFace;
  final bool readyToCapture;
  final int stableFrames;
  final double progress;
  final Face? latestFace;
  final Size? latestImageSize;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        width: 344,
        height: 426,
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(34),
          boxShadow: const [
            BoxShadow(
              color: Color(0x14000000),
              blurRadius: 24,
              offset: Offset(0, 10),
            ),
          ],
          border: Border.all(color: const Color(0xFFE7ECE9)),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const SizedBox(height: 6),
            const Text(
              'Daftar Wajah',
              style: TextStyle(
                color: _enrollText,
                fontSize: 15,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 14),
            Container(
              width: 236,
              height: 236,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: _enrollPrimarySoft,
                border: Border.all(color: _enrollPrimaryBorder, width: 1.5),
              ),
              child: ClipOval(
                child: loading && controller == null
                    ? const Center(child: CircularProgressIndicator())
                    : controller == null
                        ? const Center(
                            child: Icon(
                              Icons.verified_user_rounded,
                              size: 104,
                              color: _enrollPrimary,
                            ),
                          )
                          : Stack(
                            fit: StackFit.expand,
                            children: [
                              Center(
                                child: _CameraPreviewFit(controller: controller!),
                              ),
                              Container(
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  border: Border.all(
                                    color: readyToCapture
                                        ? _enrollPrimary
                                        : Colors.white.withValues(alpha: 0.65),
                                    width: 3,
                                  ),
                                ),
                              ),
                              if (readyToCapture)
                                Positioned(
                                  right: 18,
                                  top: 18,
                                  child: Container(
                                    width: 26,
                                    height: 26,
                                    decoration: const BoxDecoration(
                                      color: Color(0xFF22C55E),
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(
                                      Icons.check_rounded,
                                      color: Colors.white,
                                      size: 18,
                                    ),
                                  ),
                                ),
                            ],
                          ),
              ),
            ),
            const SizedBox(height: 14),
            Text(
              hasFace
                  ? (readyToCapture ? 'Wajah terdeteksi' : 'Pusatkan wajah')
                  : 'Arahkan wajah ke lingkaran',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _enrollText,
                fontSize: 18,
                height: 1.1,
                fontWeight: FontWeight.w800,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              readyToCapture
                  ? 'Tahan sebentar...'
                  : 'Pastikan wajah terlihat jelas dan pencahayaan cukup.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _enrollMuted,
                fontSize: 11,
                height: 1.3,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 12),
            ClipRRect(
              borderRadius: BorderRadius.circular(999),
              child: LinearProgressIndicator(
                minHeight: 8,
                value: progress,
                backgroundColor: const Color(0xFFE4ECE8),
                valueColor: const AlwaysStoppedAnimation<Color>(_enrollPrimary),
              ),
            ),
            const SizedBox(height: 8),
            Text(
              '${(progress * 100).round()}% sample terkumpul',
              style: const TextStyle(
                color: _enrollMuted,
                fontSize: 11,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 10),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _MiniBadge(
                  label: hasFace ? 'Terdeteksi' : 'Mencari',
                  active: hasFace,
                ),
                const SizedBox(width: 8),
                _MiniBadge(
                  label: stableFrames >= 6 ? 'Stabil' : 'Menstabilkan',
                  active: stableFrames >= 6,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _MiniBadge extends StatelessWidget {
  const _MiniBadge({required this.label, required this.active});

  final String label;
  final bool active;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
      decoration: BoxDecoration(
        color: active ? _enrollPrimarySoft : const Color(0xFFF1F4F3),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: active ? _enrollPrimary : _enrollMuted,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

class _CameraPreviewFit extends StatelessWidget {
  const _CameraPreviewFit({required this.controller});

  final CameraController controller;

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final previewSize = controller.value.previewSize;
        if (previewSize == null) {
          return CameraPreview(controller);
        }

        final previewAspect = previewSize.height / previewSize.width;
        return ClipOval(
          child: SizedBox.expand(
            child: FittedBox(
              fit: BoxFit.cover,
              alignment: Alignment.center,
              child: SizedBox(
                width: previewAspect,
                height: 1,
                child: AspectRatio(
                  aspectRatio: previewAspect,
                  child: CameraPreview(controller),
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}
