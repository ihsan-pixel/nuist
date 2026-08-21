import 'dart:async';
import 'dart:convert';
import 'dart:io';
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
  String _poseInstruction = 'Arahkan wajah ke depan.';
  String _nextStepHint = 'Langkah berikutnya: hadapkan wajah ke depan.';
  final List<String> _poseSequence = <String>[];
  final Map<String, List<double>> _poseDescriptors = <String, List<double>>{};
  Face? _latestFace;
  Size? _latestImageSize;
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
      final pose = _detectPose(face);
      final stable = _isPoseStable(face);
      final ready = _isFaceReady(face);

      if (!mounted) {
        return;
      }

      setState(() {
        _latestFace = face;
        _latestImageSize = Size(image.width.toDouble(), image.height.toDouble());
        _hasFace = face != null;
        _readyToCapture = ready;
        _stableFrames = stable ? _stableFrames + 1 : 0;
        _poseInstruction = _instructionForPose(pose);
        _nextStepHint = _nextHintForPose(pose);
        if (face == null) {
          _status = 'Wajah belum terdeteksi. Posisikan wajah di tengah bingkai.';
        } else if (pose == 'front' && stable) {
          _status = 'Wajah depan stabil. Selanjutnya lihat kanan.';
        } else if (pose == 'right' && stable) {
          _status = 'Bagus. Selanjutnya lihat kiri.';
        } else if (pose == 'left' && stable) {
          _status = 'Bagus. Kembali lihat depan untuk menyimpan.';
        } else if (ready) {
          _status = 'Wajah stabil dan siap didaftarkan.';
        } else {
          _status = 'Tahan posisi wajah agar hasil lebih akurat.';
        }
      });

      _updatePoseSequence(face);
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
    if (!_readyToCapture ||
        _loading ||
        _submitting ||
        !_isEnrollmentSequenceComplete()) {
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

  bool _isPoseStable(Face? face) {
    if (face == null) {
      return false;
    }
    final yaw = (face.headEulerAngleY ?? 0).abs();
    final pitch = (face.headEulerAngleX ?? 0).abs();
    final roll = (face.headEulerAngleZ ?? 0).abs();
    return yaw <= 18 && pitch <= 18 && roll <= 18;
  }

  bool _isEnrollmentSequenceComplete() {
    return _poseSequence.length >= 4 &&
        _poseSequence[0] == 'front' &&
        _poseSequence[1] == 'right' &&
        _poseSequence[2] == 'left' &&
        _poseSequence[3] == 'front_final';
  }

  String _detectPose(Face? face) {
    if (face == null) {
      return 'searching';
    }
    final yaw = face.headEulerAngleY ?? 0;
    if (yaw >= 12) {
      return 'right';
    }
    if (yaw <= -12) {
      return 'left';
    }
    return 'front';
  }

  String _instructionForPose(String pose) {
    switch (pose) {
      case 'right':
        return 'Sekarang lihat kanan sedikit.';
      case 'left':
        return 'Sekarang lihat kiri sedikit.';
      case 'front':
        return 'Hadapkan wajah ke depan.';
      default:
        return 'Arahkan wajah ke dalam bingkai.';
    }
  }

  String _nextHintForPose(String pose) {
    switch (pose) {
      case 'front':
        return _poseSequence.isEmpty
            ? 'Langkah berikutnya: tahan wajah depan sampai stabil.'
            : 'Langkah berikutnya: geser wajah sedikit ke kanan.';
      case 'right':
        return 'Langkah berikutnya: geser wajah sedikit ke kiri.';
      case 'left':
        return 'Langkah berikutnya: kembali lihat depan untuk simpan.';
      default:
        return 'Langkah berikutnya: arahkan wajah ke dalam bingkai.';
    }
  }

  void _updatePoseSequence(Face? face) {
    if (face == null) {
      return;
    }
    final pose = _detectPose(face);
    final stable = _isPoseStable(face);
    if (!stable) {
      return;
    }

    if (pose == 'front' && _poseSequence.isEmpty) {
      _poseSequence.add('front');
      _poseDescriptors['front'] = _buildDescriptor(face);
    } else if (pose == 'right' &&
        _poseSequence.isNotEmpty &&
        _poseSequence.last == 'front' &&
        !_poseSequence.contains('right')) {
      _poseSequence.add('right');
      _poseDescriptors['right'] = _buildDescriptor(face);
    } else if (pose == 'left' &&
        _poseSequence.contains('right') &&
        !_poseSequence.contains('left')) {
      _poseSequence.add('left');
      _poseDescriptors['left'] = _buildDescriptor(face);
    } else if (pose == 'front' &&
        _poseSequence.contains('left') &&
        !_poseSequence.asMap().containsKey(3)) {
      _poseSequence.add('front_final');
      _poseDescriptors['front_final'] = _buildDescriptor(face);
    }
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

      final faceData = _combinedDescriptor();
      if (faceData == null || faceData.length != 128) {
        throw Exception(
          'Data wajah belum cukup lengkap. Ulangi urutan depan, kanan, kiri, lalu depan lagi.',
        );
      }

      if (!_isEnrollmentSequenceComplete() || _poseDescriptors.length < 4) {
        throw Exception(
          'Urutan pendaftaran wajah belum lengkap. Ikuti pose depan, kanan, kiri, lalu depan lagi.',
        );
      }

      final embedding = await _embeddingService.extractEmbedding(File(file.path));

      final result = await widget.repository.enrollFace(
        payload: {
          'user_id': userId,
          'face_data': faceData,
          'face_samples': _poseDescriptors,
          'pose_sequence': _poseSequence,
          if (embedding != null) 'face_embedding': embedding,
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

  List<double>? _combinedDescriptor() {
    if (_poseDescriptors.isEmpty) {
      return null;
    }

    final samples = _poseDescriptors.values.where((item) => item.isNotEmpty).toList();
    if (samples.isEmpty) {
      return null;
    }

    final length = samples.first.length;
    final combined = List<double>.filled(length, 0.0);
    for (final sample in samples) {
      for (var i = 0; i < length && i < sample.length; i++) {
        combined[i] += sample[i];
      }
    }
    for (var i = 0; i < combined.length; i++) {
      combined[i] = combined[i] / samples.length;
    }
    return combined;
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
    if (_poseSequence.isEmpty) {
      return 0.0;
    }
    final known = <String>{..._poseSequence};
    var progress = 0.0;
    if (known.contains('front')) {
      progress += 0.25;
    }
    if (known.contains('right')) {
      progress += 0.25;
    }
    if (known.contains('left')) {
      progress += 0.25;
    }
    if (known.contains('front_final')) {
      progress += 0.25;
    }
    return progress.clamp(0.0, 1.0);
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
              'Face detection',
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
              readyToCapture
                  ? 'Identity Verified'
                  : hasFace
                      ? 'Keep your face centered'
                      : 'Align your face with the frame',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _enrollText,
                fontSize: 21,
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
