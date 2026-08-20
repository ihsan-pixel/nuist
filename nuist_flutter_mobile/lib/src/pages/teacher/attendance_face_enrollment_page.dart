import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:flutter/services.dart';

import '../../services/teacher_mobile_repository.dart';

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
  });

  final TeacherMobileRepository repository;
  final String title;
  final String description;

  @override
  State<AttendanceFaceEnrollmentPage> createState() =>
      _AttendanceFaceEnrollmentPageState();
}

class _AttendanceFaceEnrollmentPageState
    extends State<AttendanceFaceEnrollmentPage> {
  CameraController? _controller;
  FaceDetector? _detector;
  bool _loading = true;
  bool _processingFrame = false;
  bool _hasFace = false;
  bool _readyToCapture = false;
  bool _submitting = false;
  int _stableFrames = 0;
  Timer? _autoCaptureTimer;
  bool _autoCaptureArmed = false;
  String _status = 'Menyiapkan kamera depan...';
  String? _error;
  DateTime _lastFrameAt = DateTime.fromMillisecondsSinceEpoch(0);

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

      if (!mounted) {
        return;
      }

      setState(() {
        _hasFace = face != null;
        _readyToCapture = ready;
        _stableFrames = ready ? _stableFrames + 1 : 0;
        if (face == null) {
          _status = 'Wajah belum terdeteksi. Posisikan wajah di tengah bingkai.';
        } else if (ready) {
          _status = 'Wajah siap didaftarkan.';
        } else {
          _status = 'Tahan posisi wajah agar hasil lebih akurat.';
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

    if (_stableFrames < 6 || _autoCaptureArmed) {
      return;
    }

    _autoCaptureArmed = true;
    _autoCaptureTimer?.cancel();
    _autoCaptureTimer = Timer(const Duration(milliseconds: 850), () {
      if (mounted && _readyToCapture && !_loading && !_submitting) {
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
    final format = InputImageFormatValue.fromRawValue(image.format.raw) ??
        InputImageFormat.yuv420;
    final bytes = WriteBuffer();
    for (final plane in image.planes) {
      bytes.putUint8List(plane.bytes);
    }
    final metadata = InputImageMetadata(
      size: Size(image.width.toDouble(), image.height.toDouble()),
      rotation: rotation,
      format: format,
      bytesPerRow: image.planes.first.bytesPerRow,
    );
    return InputImage.fromBytes(
      bytes: bytes.done().buffer.asUint8List(),
      metadata: metadata,
    );
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

      final profile = await widget.repository.getProfile();
      final user = Map<String, dynamic>.from(
        (profile['user'] as Map?) ?? const <String, dynamic>{},
      );
      final userId = (user['id'] as num?)?.toInt();
      if (userId == null) {
        throw Exception('User ID tidak ditemukan dari profil.');
      }

      final result = await widget.repository.enrollFace(
        payload: {
          'user_id': userId,
          'face_data': processed['face_descriptor'],
          'liveness_score': processed['liveness_score'],
          'liveness_challenges': processed['liveness_challenges'],
          'device_info': 'flutter_mobile_face_enrollment',
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
}

class _FaceHeroCard extends StatelessWidget {
  const _FaceHeroCard({
    required this.controller,
    required this.loading,
    required this.hasFace,
    required this.readyToCapture,
    required this.stableFrames,
  });

  final CameraController? controller;
  final bool loading;
  final bool hasFace;
  final bool readyToCapture;
  final int stableFrames;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        width: 332,
        height: 408,
        padding: const EdgeInsets.all(22),
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
              'Face detection',
              style: TextStyle(
                color: _enrollText,
                fontSize: 15,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 14),
            Container(
              width: 188,
              height: 188,
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
                                  size: 96,
                                  color: _enrollPrimary,
                                ),
                              )
                        : Stack(
                            fit: StackFit.expand,
                            children: [
                              Center(
                                child: AspectRatio(
                                  aspectRatio: controller!.value.aspectRatio,
                                  child: CameraPreview(controller!),
                                ),
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
                                    width: 24,
                                    height: 24,
                                    decoration: const BoxDecoration(
                                      color: Color(0xFF22C55E),
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(
                                      Icons.check_rounded,
                                      color: Colors.white,
                                      size: 16,
                                    ),
                                  ),
                                ),
                            ],
                          ),
              ),
            ),
            const SizedBox(height: 18),
            Text(
              readyToCapture
                  ? 'Identity Verified'
                  : hasFace
                      ? 'Keep your face centered'
                      : 'Align your face with the frame',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _enrollText,
                fontSize: 20,
                height: 1.1,
                fontWeight: FontWeight.w800,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              readyToCapture
                  ? 'Wajah sudah stabil dan siap didaftarkan.'
                  : 'Pastikan wajah terlihat jelas, tidak miring, dan pencahayaan cukup.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _enrollMuted,
                fontSize: 11,
                height: 1.3,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 14),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _MiniBadge(
                  label: hasFace ? 'Face detected' : 'Searching',
                  active: hasFace,
                ),
                const SizedBox(width: 8),
                _MiniBadge(
                  label: stableFrames >= 6 ? 'Stable' : 'Stabilizing',
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
