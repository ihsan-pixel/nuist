import 'dart:async';
import 'dart:convert';
import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:flutter/services.dart';
import 'package:image/image.dart' as img;

import '../../services/face_camera_image_converter.dart';
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
  int _frameSequence = 0;
  int _lastAcceptedFrameSequence = 0;
  DateTime? _lastAcceptedFrameAt;
  Completer<_RecognitionFrameSnapshot>? _pendingSampleRequest;
  static const int _sampleTargetCount = 3;
  static const Duration _frameThrottle = Duration(milliseconds: 80);
  static const Duration _faceStabilityWindow = Duration(milliseconds: 160);
  img.Image? _latestRgbFrame;
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
          // ML Kit only gates capture; identity is still verified by Python.
          performanceMode: FaceDetectorMode.fast,
        ),
      );
      _detectorReady = true;

      _modelReady = true;
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

    if (now.difference(_lastFrameAt) < _frameThrottle) {
      return;
    }
    _lastFrameAt = now;
    _frameSequence += 1;
    final frameSeq = _frameSequence;

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
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final faces = await _detector!.processImage(inputImage);
      if (_completed || _isDisposing || !mounted) {
        return;
      }
      final face = faces.isNotEmpty ? faces.first : null;
      if (face != null) {
        _latestRgbFrame = _cameraImageConverter.convertToOrientedRgbImage(
          image,
          _controller!.description,
          _controller!.value.deviceOrientation,
        );
      } else {
        _latestRgbFrame = null;
      }
      _maybeFulfillPendingSample(
        frameSeq: frameSeq,
        frame: _latestRgbFrame,
        face: face,
        capturedAt: now,
      );
      final ready = _isFaceReady(face);
      if (face != null && _firstValidFaceAt == null) {
        _firstValidFaceAt = now;
        _stableReadyAt ??= now;
      }

      if (!mounted) {
        return;
      }

      setState(() {
        _hasFace = face != null;
        _readyToCapture = ready && _isFaceStable(now);
        _stableFrames = ready ? _stableFrames + 1 : 0;
        if (face == null) {
          _status = 'Wajah belum terdeteksi. Posisikan wajah di tengah bingkai.';
        } else if (ready) {
          _status = _readyToCapture
              ? 'Wajah stabil. Silakan ambil scan.'
              : 'Tahan sebentar, wajah sedang distabilkan.';
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
        'local_detector_only=true '
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
        'engine=python '
        'model=sface '
        'model_version=v1',
      );
      final verification = await widget.repository.verifyFace(
        payload: {
          'frames': batch.frames,
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
      _verificationAttempts += 1;
      debugPrint(
        '[FACE_ATTENDANCE][VERIFY_RESULT] '
        'attempt=$_verificationAttempts '
        'verified=$verified '
        'similarity=${similarity ?? ""} '
        'threshold=${verification['threshold'] ?? ""} '
        'code=${code ?? ""}',
      );

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
          'selfie_frames': batch.frames,
          'face_embedding': verification['face_embedding'],
          'liveness_score': verification['liveness_score'],
          'liveness_challenges': verification['liveness_challenges'] ?? const <dynamic>[],
          'verification': verification,
          'face_verified': true,
          'face_verification': verification,
        });
        return;
      }

      await _showFaceMismatchAlertAndExit(similarity: similarity);
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

  Future<void> _showFaceMismatchAlertAndExit({double? similarity}) async {
    _completed = true;
    await _stopCameraStreamSafely(reason: 'face_not_matched');
    if (!mounted) {
      return;
    }

    await showDialog<void>(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        title: const Text('Wajah Tidak Cocok'),
        content: Text(
          similarity == null
              ? 'Wajah tidak cocok dengan akun yang digunakan. Silakan gunakan akun dan wajah yang terdaftar.'
              : 'Wajah tidak cocok dengan data wajah terdaftar (kemiripan ${(similarity * 100).round()}%).',
        ),
        actions: [
          FilledButton(
            onPressed: () => Navigator.of(context).pop(),
            child: const Text('Kembali'),
          ),
        ],
      ),
    );

    if (mounted) {
      Navigator.of(context).pop();
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
    final samples = <_RecognitionFrameSnapshot>[];
    final challenges = <String>{};
    double livenessTotal = 0.0;

    while (samples.length < _sampleTargetCount) {
      if (_completed || _isDisposing || !mounted) {
        return null;
      }

      final snapshot = await _waitForNextRecognitionFrame();
      if (snapshot == null) {
        return null;
      }

      final face = snapshot.face;
      final frame = snapshot.frame;
      if (frame == null || face == null || !_isFaceReady(face)) {
        continue;
      }

      final liveness = _estimateLiveness(face);
      final sampleChallenges = (liveness['challenges'] as List)
          .map((item) => item.toString().trim())
          .where((item) => item.isNotEmpty)
          .toList(growable: false);

      samples.add(snapshot.copyWith(
        livenessScore: (liveness['score'] as num).toDouble(),
        challenges: sampleChallenges,
      ));
      challenges.addAll(sampleChallenges);
      livenessTotal += (liveness['score'] as num).toDouble();
      debugPrint(
        '[FACE_ATTENDANCE][SAMPLE_ACCEPT] '
        'batch=${_verificationAttempts + 1} '
        'sample=${samples.length} '
        'frame_seq=${snapshot.frameSeq} '
        'frame_age_ms=${snapshot.frameAgeMs} '
        'delta_from_previous_ms=${snapshot.deltaFromPreviousMs} '
        'local_frame_only=true',
      );
    }

    final latestBytes = samples.isNotEmpty ? samples.last.latestImageBytes : Uint8List(0);
    return _RecognitionBatch(
      frames: samples
          .map((sample) => 'data:image/jpeg;base64,${base64Encode(sample.latestImageBytes)}')
          .toList(growable: false),
      latestImageBytes: latestBytes,
      livenessScore: livenessTotal / samples.length,
      challenges: challenges.toList(growable: false),
      intraBatchSimilarities: const [],
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

  void _resetSampleBuffer() {
    _latestRgbFrame = null;
    _pendingSampleRequest = null;
    _lastAcceptedFrameAt = null;
  }

  void _maybeFulfillPendingSample({
    required int frameSeq,
    required img.Image? frame,
    required Face? face,
    required DateTime capturedAt,
  }) {
    final pending = _pendingSampleRequest;
    if (pending == null || pending.isCompleted) {
      return;
    }
    if (face == null || frame == null || !_isFaceReady(face)) {
      return;
    }
    if (frameSeq <= _lastAcceptedFrameSequence) {
      return;
    }

    final delta = _lastAcceptedFrameAt == null
        ? 0
        : capturedAt.difference(_lastAcceptedFrameAt!).inMilliseconds;
    _lastAcceptedFrameSequence = frameSeq;
    _lastAcceptedFrameAt = capturedAt;
      pending.complete(
        _RecognitionFrameSnapshot(
        frameSeq: frameSeq,
        frameAgeMs: 0,
        deltaFromPreviousMs: delta,
        capturedAt: capturedAt,
        frame: frame,
        face: face,
        latestImageBytes: Uint8List.fromList(img.encodeJpg(frame)),
      ),
    );
    _pendingSampleRequest = null;
  }

  Future<_RecognitionFrameSnapshot?> _waitForNextRecognitionFrame() async {
    if (_completed || _isDisposing || !mounted) {
      return null;
    }

    final completer = Completer<_RecognitionFrameSnapshot>();
    _pendingSampleRequest = completer;
    try {
      return await completer.future.timeout(const Duration(seconds: 3));
    } catch (_) {
      if (_pendingSampleRequest == completer) {
        _pendingSampleRequest = null;
      }
      return null;
    }
  }

  bool _isFaceStable(DateTime now) {
    final readyAt = _stableReadyAt;
    if (readyAt == null) {
      return false;
    }

    return now.difference(readyAt) >= _faceStabilityWindow;
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
          child: SingleChildScrollView(
            physics: const ClampingScrollPhysics(),
            child: Column(
              children: [
                const SizedBox(height: 18),
                _FaceHeroCard(
                  controller: _controller,
                  loading: _loading && _controller == null,
                  hasFace: _hasFace,
                  readyToCapture: _readyToCapture,
                ),
                const SizedBox(height: 18),
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
                      ? '--'
                      : '${((_similarityScore!.clamp(0.0, 1.0)) * 100).round()}%',
                ),
                const SizedBox(height: 18),
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
                const SizedBox(height: 8),
              ],
            ),
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
  });

  final CameraController? controller;
  final bool loading;
  final bool hasFace;
  final bool readyToCapture;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        width: 344,
        height: 374,
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
                  ? 'Siap dipindai'
                  : hasFace
                      ? 'Pertahankan posisi'
                      : 'Arahkan wajah ke bingkai',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: _scanText,
                fontSize: 21,
                height: 1.1,
                fontWeight: FontWeight.w800,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _RecognitionBatch {
  const _RecognitionBatch({
    required this.frames,
    required this.latestImageBytes,
    required this.livenessScore,
    required this.challenges,
    required this.intraBatchSimilarities,
  });

  final List<String> frames;
  final Uint8List latestImageBytes;
  final double livenessScore;
  final List<String> challenges;
  final List<double> intraBatchSimilarities;
}

class _RecognitionFrameSnapshot {
  const _RecognitionFrameSnapshot({
    required this.frameSeq,
    required this.frameAgeMs,
    required this.deltaFromPreviousMs,
    required this.capturedAt,
    required this.frame,
    required this.face,
    required this.latestImageBytes,
    this.embedding,
    this.livenessScore,
    this.challenges,
  });

  final int frameSeq;
  final int frameAgeMs;
  final int deltaFromPreviousMs;
  final DateTime capturedAt;
  final img.Image? frame;
  final Face? face;
  final Uint8List latestImageBytes;
  final List<double>? embedding;
  final double? livenessScore;
  final List<String>? challenges;

  _RecognitionFrameSnapshot copyWith({
    List<double>? embedding,
    double? livenessScore,
    List<String>? challenges,
  }) {
    return _RecognitionFrameSnapshot(
      frameSeq: frameSeq,
      frameAgeMs: frameAgeMs,
      deltaFromPreviousMs: deltaFromPreviousMs,
      capturedAt: capturedAt,
      frame: frame,
      face: face,
      latestImageBytes: latestImageBytes,
      embedding: embedding ?? this.embedding,
      livenessScore: livenessScore ?? this.livenessScore,
      challenges: challenges ?? this.challenges,
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
          'Kecocokan',
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
          child: value == null
              ? const LinearProgressIndicator(
                  minHeight: 8,
                  backgroundColor: _scanPrimarySoft,
                  color: _scanPrimary,
                )
              : TweenAnimationBuilder<double>(
                  tween: Tween<double>(begin: 0, end: normalized),
                  duration: const Duration(milliseconds: 650),
                  curve: Curves.easeOutCubic,
                  builder: (context, animatedValue, child) {
                    return LinearProgressIndicator(
                      minHeight: 8,
                      value: animatedValue,
                      backgroundColor: _scanPrimarySoft,
                      color: _scanPrimary,
                    );
                  },
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
