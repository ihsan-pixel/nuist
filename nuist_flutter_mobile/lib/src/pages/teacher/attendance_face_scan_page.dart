import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:flutter/services.dart';

const _scanPrimary = Color(0xFF00745A);
const _scanPrimarySoft = Color(0xFFEAF6F1);
const _scanPrimaryBorder = Color(0xFFD7E5DE);
const _scanText = Color(0xFF172A24);
const _scanMuted = Color(0xFF64746E);

class AttendanceFaceScanPage extends StatefulWidget {
  const AttendanceFaceScanPage({
    super.key,
    required this.title,
    required this.description,
  });

  final String title;
  final String description;

  @override
  State<AttendanceFaceScanPage> createState() => _AttendanceFaceScanPageState();
}

class _AttendanceFaceScanPageState extends State<AttendanceFaceScanPage> {
  CameraController? _controller;
  FaceDetector? _detector;
  bool _loading = true;
  bool _processingFrame = false;
  bool _hasFace = false;
  bool _readyToCapture = false;
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
          enableContours: false,
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
        _status = 'Arahkan wajah ke dalam bingkai.';
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
          _status = 'Wajah terdeteksi dengan stabil. Silakan ambil scan.';
        } else {
          _status = 'Tahan posisi wajah, jangan terlalu miring.';
        }
      });

      _scheduleAutoCapture();
    } catch (_) {
      if (mounted) {
        setState(() {
          _status = 'Gagal membaca frame kamera.';
        });
      }
    } finally {
      _processingFrame = false;
    }
  }

  void _scheduleAutoCapture() {
    if (!_readyToCapture || _loading) {
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
      if (mounted && _readyToCapture && !_loading) {
        unawaited(_capture());
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

    final bytes = <int>[];
    for (final plane in image.planes) {
      bytes.addAll(plane.bytes);
    }

    final size = Size(image.width.toDouble(), image.height.toDouble());
    final metadata = InputImageMetadata(
      size: size,
      rotation: rotation,
      format: format,
      bytesPerRow: image.planes.first.bytesPerRow,
    );

    return InputImage.fromBytes(bytes: Uint8List.fromList(bytes), metadata: metadata);
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

  Future<void> _capture() async {
    if (_loading || !_readyToCapture || _controller == null) {
      return;
    }

    setState(() {
      _loading = true;
      _error = null;
      _status = 'Mengambil hasil scan...';
    });

    try {
      await _controller?.stopImageStream();
      final file = await _controller!.takePicture();
      final processed = await _analyzeImage(file.path);
      if (processed == null) {
        throw Exception(
          'Wajah tidak valid. Pastikan wajah terlihat jelas dan coba lagi.',
        );
      }

      if (!mounted) {
        return;
      }
      Navigator.of(context).pop(processed);
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
          _loading = false;
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
      final imageBytes = await File(path).readAsBytes();
      final liveness = _estimateLiveness(face);
      return {
        'selfie_data': 'data:image/jpeg;base64,${base64Encode(imageBytes)}',
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
        foregroundColor: _scanText,
        elevation: 0,
        titleSpacing: 0,
        title: Text(
          widget.title,
          style: const TextStyle(
            color: _scanText,
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
                  color: _scanMuted,
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
                  color: _error != null ? Colors.red : _scanText,
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  height: 1.35,
                ),
              ),
              const Spacer(),
              FilledButton(
                onPressed: _loading || !_readyToCapture ? null : _capture,
                style: FilledButton.styleFrom(
                  backgroundColor: _scanPrimary,
                  foregroundColor: Colors.white,
                  minimumSize: const Size.fromHeight(52),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                ),
                child: Text(
                  _loading
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
        width: 300,
        height: 334,
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
            const SizedBox(height: 4),
            const Text(
              'Face detection',
              style: TextStyle(
                color: _scanText,
                fontSize: 14,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 10),
            Container(
              width: 148,
              height: 148,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: _scanPrimarySoft,
                border: Border.all(color: _scanPrimaryBorder, width: 1.5),
              ),
              child: ClipOval(
                child: loading && controller == null
                    ? const Center(child: CircularProgressIndicator())
                    : controller == null
                        ? const Center(
                            child: Icon(
                              Icons.face_retouching_natural_rounded,
                              size: 82,
                              color: _scanPrimary,
                            ),
                          )
                        : Stack(
                            fit: StackFit.expand,
                            children: [
                              CameraPreview(controller!),
                              Container(
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  border: Border.all(
                                    color: readyToCapture
                                        ? _scanPrimary
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
            const SizedBox(height: 12),
            Text(
              readyToCapture
                  ? 'Identity Verified'
                  : hasFace
                      ? 'Keep your face centered'
                      : 'Align your face with the frame',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _scanText,
                fontSize: 19,
                height: 1.15,
                fontWeight: FontWeight.w800,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              readyToCapture
                  ? 'Wajah sudah stabil dan siap diproses.'
                  : 'Pastikan wajah terlihat jelas, tidak miring, dan pencahayaan cukup.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _scanMuted,
                fontSize: 11,
                height: 1.25,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 10),
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
        color: active ? _scanPrimarySoft : const Color(0xFFF1F4F3),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: active ? _scanPrimary : _scanMuted,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}
