import 'dart:async';
import 'dart:convert';
import 'dart:math' as math;
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:flutter/services.dart';
import 'package:image/image.dart' as img;

import '../../services/face_camera_image_converter.dart';
import '../../services/face_recognition_service.dart';
import '../../services/teacher_mobile_repository.dart';

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
    required this.repository,
  });

  final String title;
  final String description;
  final TeacherMobileRepository repository;

  @override
  State<AttendanceFaceScanPage> createState() => _AttendanceFaceScanPageState();
}

class _AttendanceFaceScanPageState extends State<AttendanceFaceScanPage> {
  CameraController? _controller;
  FaceDetector? _detector;
  final FaceCameraImageConverter _cameraImageConverter =
      const FaceCameraImageConverter();
  final FaceRecognitionService _recognitionService = FaceRecognitionService();
  bool _loading = true;
  bool _processingFrame = false;
  bool _hasFace = false;
  bool _readyToCapture = false;
  bool _verifying = false;
  bool _cameraReady = false;
  bool _detectorReady = false;
  bool _modelReady = false;
  bool _profileReady = false;
  int _stableFrames = 0;
  int _verificationAttempts = 0;
  double? _similarityScore;
  Timer? _autoCaptureTimer;
  bool _autoCaptureArmed = false;
  String _status = 'Menyiapkan kamera depan...';
  String? _error;
  DateTime _lastFrameAt = DateTime.fromMillisecondsSinceEpoch(0);
  DateTime? _verificationCooldownUntil;
  DateTime? _firstValidFaceAt;
  DateTime? _stableReadyAt;
  DateTime? _alignmentReadyAt;
  DateTime? _inferenceStartAt;
  DateTime? _apiVerifyStartAt;
  static const int _sampleTargetCount = 3;
  static const int _maxVerificationAttempts = 3;
  static const Duration _sampleInterval = Duration(milliseconds: 220);
  static const Duration _retryCooldown = Duration(milliseconds: 420);
  img.Image? _latestRgbFrame;
  Face? _latestFace;
  bool _completed = false;
  bool _isDisposing = false;
  Future<void>? _cameraStopFuture;
  late final Future<Map<String, dynamic>> _biometricStatusFuture;

  @override
  void initState() {
    super.initState();
    _biometricStatusFuture = widget.repository.getBiometricProfileStatus();
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
      _cameraReady = true;

      final detector = FaceDetector(
        options: FaceDetectorOptions(
          enableClassification: true,
          enableLandmarks: true,
          enableContours: false,
          performanceMode: FaceDetectorMode.accurate,
        ),
      );
      _detectorReady = true;

      unawaited(_recognitionService.initialize().then((_) {
        if (mounted) {
          setState(() {
            _modelReady = true;
          });
        } else {
          _modelReady = true;
        }
      }));
      unawaited(_biometricStatusFuture.then((_) {
        if (mounted) {
          setState(() {
            _profileReady = true;
          });
        } else {
          _profileReady = true;
        }
      }));

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
    if (_completed || _isDisposing || _processingFrame || _detector == null || _controller == null) {
      return;
    }

    final now = DateTime.now();
    if (_verificationCooldownUntil != null &&
        now.isBefore(_verificationCooldownUntil!)) {
      return;
    }

    if (now.difference(_lastFrameAt).inMilliseconds < 70) {
      return;
    }
    _lastFrameAt = now;

    _processingFrame = true;
    try {
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final inputImage = _toInputImage(
        image,
        _controller!.description,
        _controller!.value.deviceOrientation,
      );
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final rgbFrame = _cameraImageConverter.convertToRgbImage(image);
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final faces = await _detector!.processImage(inputImage);
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final face = faces.isNotEmpty ? faces.first : null;
      _latestRgbFrame = rgbFrame;
      _latestFace = face;
      final ready = _isFaceReady(face);
      if (face != null && _firstValidFaceAt == null) {
        _firstValidFaceAt = now;
      }

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
          _status = 'Wajah terdeteksi. Silakan ambil scan.';
          if (_stableFrames >= 1 && _stableReadyAt == null) {
            _stableReadyAt = now;
          }
        } else {
          _status = 'Tahan posisi wajah, jangan terlalu miring.';
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
    if (!_readyToCapture || _loading) {
      _autoCaptureTimer?.cancel();
      _autoCaptureArmed = false;
      return;
    }

    if (_stableFrames < 1 || _autoCaptureArmed) {
      return;
    }

    _autoCaptureArmed = true;
    _autoCaptureTimer?.cancel();
    _autoCaptureTimer = Timer(Duration.zero, () {
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
    return yaw <= 18 && pitch <= 18 && roll <= 18 && leftEye >= 0.10 && rightEye >= 0.10;
  }

  InputImage _toInputImage(
    CameraImage image,
    CameraDescription camera,
    DeviceOrientation orientation,
  ) {
    final rotation = _rotationFromOrientation(camera, orientation);
    final bytes = _convertToNv21(image);

    final size = Size(image.width.toDouble(), image.height.toDouble());
    final metadata = InputImageMetadata(
      size: size,
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

  Future<void> _capture() async {
    if (_completed || _isDisposing || _loading || !_readyToCapture || _controller == null || _verifying) {
      return;
    }

    final verifyStart = DateTime.now();
    setState(() {
      _loading = true;
      _error = null;
      _status = 'Mengambil hasil scan...';
    });

    try {
      debugPrint(
        '[FACE_ATTENDANCE][MODEL] '
        'cache_hit=${_recognitionService.isInitialized} '
        'cameraReady=$_cameraReady '
        'detectorReady=$_detectorReady '
        'modelReady=$_modelReady '
        'profileReady=$_profileReady',
      );

      final batch = await _collectRecognitionBatch();
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      if (batch == null) {
        throw Exception(
          'Wajah tidak valid. Pastikan wajah terlihat jelas dan coba lagi.',
        );
      }

      if (!mounted) {
        return;
      }
      setState(() {
        _verifying = true;
        _status = 'Mencocokkan wajah ke data terdaftar...';
      });
      _apiVerifyStartAt = DateTime.now();
      debugPrint(
        '[FACE_ATTENDANCE][VERIFY_START] '
        'engine=${_recognitionService.modelInfo.engine} '
        'model=${_recognitionService.modelInfo.model} '
        'model_version=${_recognitionService.modelInfo.modelVersion} '
        'dimension=${_recognitionService.modelInfo.dimension}',
      );
      final verification = await widget.repository.verifyFace(
        payload: {
          'engine': _recognitionService.modelInfo.engine,
          'model': _recognitionService.modelInfo.model,
          'model_version': _recognitionService.modelInfo.modelVersion,
          'dimension': _recognitionService.modelInfo.dimension,
          'embedding': batch.centroid,
          'quality_score': 1.0,
          'liveness_score': batch.livenessScore,
          'liveness_challenges': batch.challenges,
        },
      );
      if (_completed || _isDisposing || !mounted) {
        return;
      }

      debugPrint(
        '[FACE_ATTENDANCE][API_RESPONSE_RECEIVED] '
        'verified=${verification['face_verified'] == true} '
        'api_verify_ms=${_apiVerifyStartAt == null ? 0 : DateTime.now().difference(_apiVerifyStartAt!).inMilliseconds}',
      );

      final verified = verification['face_verified'] == true;
      final code = verification['code']?.toString();
      final similarity = (verification['similarity'] as num?)?.toDouble();
      _similarityScore = similarity;
      _verificationAttempts = verified ? 0 : _verificationAttempts + 1;

      if (verified) {
        _completed = true;
        await _stopCameraStreamSafely(reason: 'verification_complete');
        if (_isDisposing || !mounted) {
          return;
        }
        final apiVerifyMs = _apiVerifyStartAt == null
            ? 0
            : DateTime.now().difference(_apiVerifyStartAt!).inMilliseconds;
        final totalMs = DateTime.now().difference(verifyStart).inMilliseconds;
        debugPrint(
          '[FACE_ATTENDANCE][FAST_TIMING] '
          'face_first_seen_ms=${_firstValidFaceAt == null ? 0 : verifyStart.difference(_firstValidFaceAt!).inMilliseconds} '
          'stable_ready_ms=${_stableReadyAt == null ? 0 : verifyStart.difference(_stableReadyAt!).inMilliseconds} '
          'alignment_ms=${_alignmentReadyAt == null ? 0 : _alignmentReadyAt!.difference(verifyStart).inMilliseconds} '
          'inference_ms=${_inferenceStartAt == null ? 0 : DateTime.now().difference(_inferenceStartAt!).inMilliseconds} '
          'api_verify_ms=$apiVerifyMs '
          'attendance_submit_ms=0 '
          'total_biometric_ms=$totalMs',
        );
        if (!mounted) {
          return;
        }
        Navigator.of(context).pop({
          'selfie_data': 'data:image/jpeg;base64,${base64Encode(batch.latestImageBytes)}',
          'face_embedding': batch.centroid,
          'liveness_score': batch.livenessScore,
          'liveness_challenges': batch.challenges,
          'verification': verification,
          'face_verified': true,
          'face_verification': verification,
        });
        return;
      }

      debugPrint(
        '[FACE_ATTENDANCE][VERIFY_RESULT] verified=false '
        'code=${code ?? ""} similarity=${similarity ?? ""}',
      );
      if (_verificationAttempts >= _maxVerificationAttempts) {
        setState(() {
          _error = 'Wajah Tidak Cocok';
          _status = 'Pastikan yang melakukan presensi adalah pemilik akun ini.';
          _verifying = false;
          _loading = false;
          _stableFrames = 0;
          _autoCaptureArmed = false;
          _readyToCapture = false;
          _verificationCooldownUntil = DateTime.now().add(_retryCooldown);
        });
        _resetSampleBuffer();
        return;
      }

      setState(() {
        _error = null;
        _status = 'Membaca wajah...';
        _verifying = false;
        _loading = false;
        _stableFrames = 0;
        _autoCaptureArmed = false;
        _readyToCapture = false;
        _verificationCooldownUntil = DateTime.now().add(_retryCooldown);
      });
      _resetSampleBuffer();
    } catch (error) {
      if (!mounted) {
        return;
      }
      final message = error.toString().replaceFirst('Exception: ', '');
      setState(() {
        _error = message;
        _status = message;
        _verificationCooldownUntil = DateTime.now().add(const Duration(milliseconds: 900));
        _readyToCapture = false;
        _stableFrames = 0;
        _autoCaptureArmed = false;
      });
      _resetSampleBuffer();
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
          _verifying = false;
        });
      }
    }
  }

  void _resetAttempt() {
    setState(() {
      _error = null;
      _status = 'Arahkan wajah ke dalam bingkai.';
      _hasFace = false;
      _readyToCapture = false;
      _stableFrames = 0;
    });
  }

  Future<void> _stopCameraStreamSafely({required String reason}) async {
    if (_cameraStopFuture != null) {
      return _cameraStopFuture!;
    }
    _cameraStopFuture = _stopCameraStreamSafelyInternal(reason: reason);
    return _cameraStopFuture!;
  }

  Future<void> _stopCameraStreamSafelyInternal({required String reason}) async {
    final controller = _controller;
    if (controller == null || !controller.value.isInitialized) {
      return;
    }
    if (!controller.value.isStreamingImages) {
      return;
    }
    debugPrint('[FACE_ATTENDANCE][CAMERA_STOP] reason=$reason');
    try {
      await controller.stopImageStream();
    } catch (_) {}
  }

  Future<_RecognitionBatch?> _collectRecognitionBatch() async {
    final embeddings = <List<double>>[];
    final challenges = <String>{};
    double livenessTotal = 0.0;
    Uint8List? latestImageBytes;

    while (embeddings.length < _sampleTargetCount) {
      if (_completed || _isDisposing || !mounted) {
        return null;
      }

      final frame = _latestRgbFrame;
      final face = _latestFace;
      if (frame == null || face == null || !_isFaceReady(face)) {
        _resetSampleBuffer();
        return null;
      }

      final aligned = _cameraImageConverter.extractAlignedFaceCropFromRgb(frame, face);
      final embeddingBytes = Uint8List.fromList(img.encodeJpg(aligned.crop));
      final embedding = await _recognitionService.generateEmbedding(embeddingBytes);
      if (_completed || _isDisposing || !mounted) {
        return null;
      }

      if (embedding.length != 192 || embedding.any((value) => !value.isFinite)) {
        _resetSampleBuffer();
        return null;
      }

      final liveness = _estimateLiveness(face);
      final sampleChallenges = (liveness['challenges'] as List)
          .map((item) => item.toString().trim())
          .where((item) => item.isNotEmpty)
          .toList(growable: false);

      embeddings.add(embedding);
      challenges.addAll(sampleChallenges);
      livenessTotal += (liveness['score'] as num).toDouble();
      latestImageBytes = Uint8List.fromList(img.encodeJpg(frame));

      if (embeddings.length < _sampleTargetCount) {
        await Future<void>.delayed(_sampleInterval);
      }
    }

    final centroid = _averageAndNormalize(embeddings);
    return _RecognitionBatch(
      centroid: centroid,
      latestImageBytes: latestImageBytes ?? Uint8List(0),
      livenessScore: livenessTotal / embeddings.length,
      challenges: challenges.toList(growable: false),
    );
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

  double _embeddingNorm(List<double> values) {
    var sum = 0.0;
    for (final value in values) {
      sum += value * value;
    }
    return sum == 0.0 ? 0.0 : math.sqrt(sum);
  }

  List<double> _averageAndNormalize(List<List<double>> embeddings) {
    if (embeddings.isEmpty) {
      return const [];
    }

    final dimension = embeddings.first.length;
    final centroid = List<double>.filled(dimension, 0.0);
    for (final embedding in embeddings) {
      for (var index = 0; index < dimension; index++) {
        centroid[index] += embedding[index];
      }
    }

    for (var index = 0; index < dimension; index++) {
      centroid[index] /= embeddings.length;
    }

    final norm = _embeddingNorm(centroid);
    if (norm <= 0) {
      return centroid;
    }

    for (var index = 0; index < dimension; index++) {
      centroid[index] /= norm;
    }
    return centroid;
  }

  void _resetSampleBuffer() {
    // Intentionally left minimal: batch state is implicit in the current frame stream.
  }

  @override
  void dispose() {
    debugPrint('[FACE_ATTENDANCE][DISPOSE]');
    _completed = true;
    _isDisposing = true;
    _autoCaptureTimer?.cancel();
    unawaited(_stopCameraStreamSafely(reason: 'dispose'));
    unawaited(_controller?.dispose());
    unawaited(_detector?.close());
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
              const SizedBox(height: 14),
              _SimilarityBar(
                value: _similarityScore,
                label: _similarityScore == null
                    ? 'Sedang membaca wajah...'
                    : '${((_similarityScore!.clamp(0.0, 1.0)) * 100).round()}%',
              ),
              const Spacer(),
              if (_error != null)
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () {
                          _resetAttempt();
                        },
                        style: OutlinedButton.styleFrom(
                          minimumSize: const Size.fromHeight(52),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: const Text('Coba Lagi'),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: FilledButton(
                        onPressed: () {
                          Navigator.of(context).pop();
                        },
                        style: FilledButton.styleFrom(
                          backgroundColor: _scanPrimary,
                          foregroundColor: Colors.white,
                          minimumSize: const Size.fromHeight(52),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: const Text('Kembali'),
                      ),
                    ),
                  ],
                )
              else
                const SizedBox.shrink(),
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
                color: _scanText,
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
                              size: 104,
                              color: _scanPrimary,
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
                                        ? _scanPrimary
                                        : Colors.white.withValues(alpha: 0.65),
                                    width: 3,
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
                color: _scanText,
                fontSize: 21,
                height: 1.1,
                fontWeight: FontWeight.w800,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              readyToCapture
                  ? 'Wajah sudah stabil dan siap diproses.'
                  : 'Pastikan wajah terlihat jelas, tidak miring, dan pencahayaan cukup.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _scanMuted,
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

class _RecognitionBatch {
  const _RecognitionBatch({
    required this.centroid,
    required this.latestImageBytes,
    required this.livenessScore,
    required this.challenges,
  });

  final List<double> centroid;
  final Uint8List latestImageBytes;
  final double livenessScore;
  final List<String> challenges;
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

class _SimilarityBar extends StatelessWidget {
  const _SimilarityBar({
    required this.value,
    required this.label,
  });

  final double? value;
  final String label;

  @override
  Widget build(BuildContext context) {
    final normalized = (value ?? 0.0).clamp(0.0, 1.0);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        Text(
          'Level kecocokan',
          textAlign: TextAlign.center,
          style: const TextStyle(
            color: _scanMuted,
            fontSize: 12,
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 6),
        ClipRRect(
          borderRadius: BorderRadius.circular(999),
          child: LinearProgressIndicator(
            minHeight: 10,
            value: value == null ? null : normalized,
            backgroundColor: _scanPrimarySoft,
            color: _scanPrimary,
          ),
        ),
        const SizedBox(height: 6),
        Text(
          label,
          textAlign: TextAlign.center,
          style: const TextStyle(
            color: _scanText,
            fontSize: 14,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}
