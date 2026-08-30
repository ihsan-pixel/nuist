import 'dart:async';
import 'dart:math' as math;
import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:image/image.dart' as img;

import '../../services/face_camera_image_converter.dart';
import '../../services/face_enrollment_math.dart';
import '../../services/face_recognition_service.dart';
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
  static const List<String> _poseOrder = <String>[
    'front',
    'left',
    'right',
    'up',
    'down',
  ];
  static const Map<String, String> _posePrompts = <String, String>{
    'front': 'Hadapkan wajah lurus',
    'left': 'Putar wajah sedikit ke kiri',
    'right': 'Putar wajah sedikit ke kanan',
    'up': 'Angkat wajah sedikit',
    'down': 'Tundukkan wajah sedikit',
  };

  final FaceCameraImageConverter _converter = const FaceCameraImageConverter();
  final FaceRecognitionService _recognitionService = FaceRecognitionService();
  final FaceEnrollmentMath _math = const FaceEnrollmentMath();
  CameraController? _controller;
  FaceDetector? _detector;
  bool _loading = true;
  bool _processingFrame = false;
  bool _hasFace = false;
  bool _readyToCapture = false;
  bool _submitting = false;
  int _stableFrames = 0;
  final PoseCandidateState _poseCandidateState = PoseCandidateState();
  String _poseInstruction = _posePrompts['front']!;
  String _nextStepHint = 'Posisi 1 dari 5';
  int _currentPoseIndex = 0;
  final List<String> _poseSequence = <String>[];
  final Map<String, List<double>> _poseEmbeddings = <String, List<double>>{};
  final List<List<double>> _sampleEmbeddings = <List<double>>[];
  final List<String> _samplePoses = <String>[];
  final List<double> _sampleQualityScores = <double>[];
  final List<double> _sampleLivenessScores = <double>[];
  final List<String> _livenessChallenges = <String>[];
  Face? _latestFace;
  Size? _latestImageSize;
  img.Image? _latestOwnedFrame;
  DateTime? _latestFrameAt;
  DateTime? _lastPoseDebugAt;
  String _status = 'Menyiapkan kamera depan...';
  String? _error;
  bool _submitInFlight = false;
  String? _submitError;

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
    if (_latestFrameAt != null && now.difference(_latestFrameAt!).inMilliseconds < 180) {
      return;
    }
    _latestFrameAt = now;

    _processingFrame = true;
    try {
      final inputImage = _toInputImage(
        image,
        _controller!.description,
        _controller!.value.deviceOrientation,
      );
      final faces = await _detector!.processImage(inputImage);
      final face = faces.isNotEmpty ? faces.first : null;
      final observation = _buildObservation(
        face: face,
        frameId: now.millisecondsSinceEpoch,
        imageWidth: image.width.toDouble(),
        imageHeight: image.height.toDouble(),
      );

      if (!mounted) {
        return;
      }

      final rgbFrame = _converter.convertToRgbImage(image);
      setState(() {
        _latestFace = face;
        _latestImageSize = Size(image.width.toDouble(), image.height.toDouble());
        _latestOwnedFrame = rgbFrame;
        _latestFrameAt = now;
        _hasFace = observation.faceCount > 0;
        _readyToCapture = observation.qualityValid;
        _stableFrames = observation.poseValid ? _stableFrames + 1 : 0;
        _poseInstruction = _instructionForPose(observation.targetPose);
        _nextStepHint = _nextHintForPose(observation.targetPose);
        _currentPoseIndex = math.min(_currentPoseIndex, _poseOrder.length - 1);
        if (face == null) {
          _status = 'Wajah belum terdeteksi. Posisikan wajah di tengah bingkai.';
        } else if (observation.poseValid && observation.qualityValid) {
          _status = 'Posisi ${_currentPoseIndex + 1} dari 5 stabil.';
        } else if (observation.qualityValid) {
          _status = 'Wajah stabil. Ikuti instruksi pose berikutnya.';
        } else {
          _status = 'Tahan posisi wajah agar hasil lebih akurat.';
        }
      });

      _updatePoseSequence(observation);
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

  String _nextHintForPose(String pose) {
    if (_currentPoseIndex + 1 >= _poseOrder.length) {
      return 'Langkah berikutnya: review dan kirim data.';
    }
    return 'Langkah berikutnya: ${_posePrompts[_poseOrder[_currentPoseIndex + 1]]}';
  }

  String _currentPose() {
    if (_currentPoseIndex >= _poseOrder.length) {
      return 'done';
    }
    return _poseOrder[_currentPoseIndex];
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

  void _updatePoseSequence(EnrollmentObservation observation) {
    if (observation.face == null) {
      _resetPoseCandidate(
        reason: 'face_lost',
        stableFrames: _poseCandidateState.validFrameCount,
      );
      return;
    }

    if (_poseCandidateState.targetPose != observation.targetPose) {
      _resetPoseCandidate(
        reason: 'target_changed',
        stableFrames: _poseCandidateState.validFrameCount,
      );
      _poseCandidateState.targetPose = observation.targetPose;
      _poseCandidateState.startedAt = DateTime.now();
      _poseCandidateState.lastFrameId = observation.frameId;
    }

    if (!observation.canEnterCandidate) {
      if (observation.poseValid || observation.qualityValid) {
        debugPrint(
          '[FACE_ENROLL][OBSERVATION_SKIPPED] '
          'pose=${observation.targetPose} '
          'frameId=${observation.frameId} '
          'poseValid=${observation.poseValid} '
          'qualityValid=${observation.qualityValid} '
          'reason=${observation.failureReason ?? 'target_changed'}',
        );
      }
      if (observation.face != null && !observation.qualityValid) {
        _resetPoseCandidate(
          reason: observation.failureReason ?? 'quality_invalid',
          stableFrames: _poseCandidateState.validFrameCount,
        );
      }
      return;
    }

    final now = DateTime.now();
    if (_currentPoseIndex < _poseOrder.length) {
      _readyToCapture = true;
    }

    _poseCandidateState.validFrameCount += 1;
    _poseCandidateState.missCount = 0;
    _poseCandidateState.lastValidAt = now;
    _poseCandidateState.lastFrameId = observation.frameId;
    final candidateStartedAt = _poseCandidateState.startedAt ?? now;
    final candidateAgeMs = now.difference(candidateStartedAt).inMilliseconds;
    debugPrint(
      '[FACE_ENROLL][POSE_CANDIDATE] '
      'pose=${observation.targetPose} '
      'stableFrames=${_poseCandidateState.validFrameCount}',
    );
    debugPrint(
      '[FACE_ENROLL][POSE_OBSERVATION] '
      'pose=${observation.targetPose} '
      'frameId=${observation.frameId} '
      'valid=${observation.poseValid} '
      'qualityValid=${observation.qualityValid} '
      'stableFrames=${_poseCandidateState.validFrameCount} '
      'missCount=${_poseCandidateState.missCount} '
      'elapsedMs=$candidateAgeMs',
    );

    final acquired = _poseCandidateState.validFrameCount >= 2 || candidateAgeMs >= 250;
    if (!acquired || _poseCandidateState.acquired || _loading || _submitting) {
      return;
    }

    _poseCandidateState.acquired = true;
    final stabilityMs = DateTime.now().difference(candidateStartedAt).inMilliseconds;
    debugPrint(
      '[FACE_ENROLL][POSE_ACQUIRED] '
      'pose=${observation.targetPose} '
      'frameId=${observation.frameId} '
      'stableFrames=${_poseCandidateState.validFrameCount} '
      'stabilityMs=$stabilityMs',
    );
    unawaited(_captureAndEnroll());
  }

  double _semanticYawForUser(Face face) {
    final rawYaw = face.headEulerAngleY ?? 0.0;
    final lensDirection = _controller?.description.lensDirection;
    if (lensDirection == CameraLensDirection.front) {
      return -rawYaw;
    }
    return rawYaw;
  }

  String _instructionForPose(String pose) {
    return _posePrompts[pose] ?? 'Arahkan wajah ke dalam bingkai.';
  }

  void _resetPoseCandidate({
    required String reason,
    required int stableFrames,
  }) {
    if (_poseCandidateState.targetPose != null && stableFrames > 0) {
      final startedAt = _poseCandidateState.startedAt ?? DateTime.now();
      debugPrint(
        '[FACE_ENROLL][POSE_CANDIDATE_RESET] '
        'pose=${_poseCandidateState.targetPose} '
        'stableFrames=$stableFrames '
        'reason=$reason '
        'elapsedMs=${DateTime.now().difference(startedAt).inMilliseconds} '
        'missCount=${_poseCandidateState.missCount}',
      );
    }
    _poseCandidateState.reset();
  }

  Future<_EnrollmentSample?> _captureCurrentSample() async {
    final controller = _controller;
    final face = _latestFace;
    final frame = _latestOwnedFrame;
    final frameAt = _latestFrameAt;
    if (controller == null || face == null || frame == null || frameAt == null) {
      throw Exception('Wajah belum siap untuk diambil.');
    }

    final frameAgeMs = DateTime.now().difference(frameAt).inMilliseconds;
    final quality = _computeQualityScore(face);
    final liveness = _estimateLiveness(face);
    _livenessChallenges.addAll(liveness.challenges);
    final currentPose = _currentPose();
    final observation = _buildObservation(
      face: face,
      frameId: frameAt.millisecondsSinceEpoch,
      imageWidth: frame.width.toDouble(),
      imageHeight: frame.height.toDouble(),
      frameAgeOverrideMs: frameAgeMs,
      targetPose: currentPose,
    );

    debugPrint(
      '[FACE_ENROLL][QUALITY] '
      'pose=${observation.targetPose} '
      'faceCount=${observation.faceCount} '
      'frameAgeMs=${observation.frameAgeMs} '
      'eyes=${observation.eyesAvailable} '
      'poseValid=${observation.poseValid} '
      'qualityValid=${observation.qualityValid}',
    );

    if (!observation.poseValid) {
      return null;
    }

    final alignment = _converter.extractAlignedFaceCropFromRgb(frame, face);
    final crop = alignment.crop;
    final sampleBytes = Uint8List.fromList(img.encodeJpg(crop, quality: 95));
    final embedding = await _recognitionService.generateEmbedding(sampleBytes);
    final norm = _math.l2Norm(embedding);

    if (embedding.length != 192) {
      throw Exception('Embedding dimension tidak valid.');
    }
    if (!embedding.every((value) => value.isFinite) || norm <= 0) {
      throw Exception('Embedding tidak valid.');
    }

    debugPrint(
      '[FACE_ENROLL][CANONICAL_ALIGNMENT] '
      'method=${alignment.method} '
      'output=112x112 '
      'sourceEyeDistance=${alignment.sourceEyeDistance.toStringAsFixed(2)} '
      'targetEyeDistance=${alignment.targetEyeDistance.toStringAsFixed(2)} '
      'scale=${alignment.scale.toStringAsFixed(4)} '
      'rotation=${alignment.rotationDegrees.toStringAsFixed(2)} '
      'translationX=${alignment.translationX.toStringAsFixed(2)} '
      'translationY=${alignment.translationY.toStringAsFixed(2)} '
      'boundaryPadding=${alignment.clamped}',
    );

    debugPrint(
      '[FACE_ENROLL][NORMALIZED_LANDMARKS] '
      'leftEye=(${alignment.leftEyeX.toStringAsFixed(4)},${alignment.leftEyeY.toStringAsFixed(4)}) '
      'rightEye=(${alignment.rightEyeX.toStringAsFixed(4)},${alignment.rightEyeY.toStringAsFixed(4)}) '
      'faceCenter=(${alignment.faceCenterX.toStringAsFixed(4)},${alignment.faceCenterY.toStringAsFixed(4)})',
    );

    return _EnrollmentSample(
      pose: _currentPose(),
      embedding: _math.normalizeL2(embedding),
      norm: norm,
      qualityScore: quality,
      livenessScore: liveness.score,
      livenessChallenges: liveness.challenges,
      frameAgeMs: frameAgeMs,
      alignmentMethod: alignment.method,
    );
  }

  EnrollmentObservation _buildObservation({
    required Face? face,
    required int frameId,
    required double imageWidth,
    required double imageHeight,
    int? frameAgeOverrideMs,
    String? targetPose,
  }) {
    final poseTarget = targetPose ?? _currentPose();
    final now = DateTime.now();
    final capturedAt = _latestFrameAt ?? now;
    final frameAgeMs = frameAgeOverrideMs ?? now.difference(capturedAt).inMilliseconds;
    final faceCount = face == null ? 0 : 1;
    final eyesAvailable = face != null &&
        face.landmarks[FaceLandmarkType.leftEye]?.position != null &&
        face.landmarks[FaceLandmarkType.rightEye]?.position != null;
    final rawYaw = face?.headEulerAngleY ?? 0.0;
  final rawPitch = face?.headEulerAngleX ?? 0.0;
  final rawRoll = face?.headEulerAngleZ ?? 0.0;
  final semanticYaw = face == null ? 0.0 : _semanticYawForUser(face);
  final semanticPitch = rawPitch;
  final poseValidation = face == null
        ? null
        : validateEnrollmentPose(face, targetPose: poseTarget);
    final faceSizeValid = face != null &&
        (face.boundingBox.width >= 80 && face.boundingBox.height >= 80);
    final landmarksValid = face != null && eyesAvailable;
    final freshnessValid = frameAgeMs <= 700;
    final sourceWidth = imageWidth > 0 ? imageWidth : (_latestImageSize?.width ?? imageWidth);
    final sourceHeight = imageHeight > 0 ? imageHeight : (_latestImageSize?.height ?? imageHeight);
    final boxLeft = face?.boundingBox.left ?? 0.0;
    final boxTop = face?.boundingBox.top ?? 0.0;
    final boxRight = face?.boundingBox.right ?? 0.0;
    final boxBottom = face?.boundingBox.bottom ?? 0.0;
    final boxWidth = face?.boundingBox.width ?? 0.0;
    final boxHeight = face?.boundingBox.height ?? 0.0;
    final normalizedBox = face == null || sourceWidth <= 0 || sourceHeight <= 0
        ? null
        : (
            left: boxLeft / sourceWidth,
            top: boxTop / sourceHeight,
            right: boxRight / sourceWidth,
            bottom: boxBottom / sourceHeight,
          );
    final boxIntersectionWidth = face == null
        ? 0.0
        : math.max(0.0, math.min(boxRight, sourceWidth) - math.max(boxLeft, 0.0));
    final boxIntersectionHeight = face == null
        ? 0.0
        : math.max(0.0, math.min(boxBottom, sourceHeight) - math.max(boxTop, 0.0));
    final boxIntersectionArea = boxIntersectionWidth * boxIntersectionHeight;
    final boxArea = boxWidth > 0 && boxHeight > 0 ? boxWidth * boxHeight : 0.0;
    final boxIntersectionRatio = boxArea > 0 ? (boxIntersectionArea / boxArea).clamp(0.0, 1.0) : 0.0;
    final eyesInside = face != null &&
        sourceWidth > 0 &&
        sourceHeight > 0 &&
        face.landmarks[FaceLandmarkType.leftEye]?.position != null &&
        face.landmarks[FaceLandmarkType.rightEye]?.position != null;
    final faceCenterX = face == null ? 0.0 : face.boundingBox.left + (face.boundingBox.width / 2.0);
    final faceCenterY = face == null ? 0.0 : face.boundingBox.top + (face.boundingBox.height / 2.0);
    final faceCenterInside = face != null &&
        faceCenterX >= 0 &&
        faceCenterY >= 0 &&
        faceCenterX <= sourceWidth &&
        faceCenterY <= sourceHeight;
    final boundaryCritical = face == null ||
        sourceWidth <= 0 ||
        sourceHeight <= 0 ||
        !boxLeft.isFinite ||
        !boxTop.isFinite ||
        !boxRight.isFinite ||
        !boxBottom.isFinite ||
        boxWidth <= 0 ||
        boxHeight <= 0 ||
        boxIntersectionRatio < 0.35 ||
        !faceCenterInside ||
        !eyesInside;
    final boundaryValid = !boundaryCritical;
    final qualityValid = face != null &&
        faceSizeValid &&
        landmarksValid &&
        freshnessValid &&
        boundaryValid &&
        _isPoseStable(face);
    final failureReason = !qualityValid
        ? (face == null
            ? 'face_lost'
            : !freshnessValid
                ? 'stale_frame'
                : !landmarksValid
                    ? 'missing_landmarks'
                    : !faceSizeValid
                        ? 'face_size_invalid'
                        : boundaryCritical
                            ? 'face_outside_source'
                            : 'quality_invalid')
        : (poseValidation?.valid ?? false)
            ? null
            : 'pose_outside_target';
    final observation = EnrollmentObservation(
      frameId: frameId,
      capturedAt: capturedAt,
      frameAgeMs: frameAgeMs,
      face: face,
      targetPose: poseTarget,
      faceCount: faceCount,
      eyesAvailable: eyesAvailable,
      rawYaw: rawYaw,
      rawPitch: rawPitch,
      rawRoll: rawRoll,
      semanticYaw: semanticYaw,
      semanticPitch: semanticPitch,
      poseValid: poseValidation?.valid ?? false,
      faceSizeValid: faceSizeValid,
      landmarksValid: landmarksValid,
      freshnessValid: freshnessValid,
      otherQualityValid: boundaryValid,
      qualityValid: qualityValid,
      failureReason: failureReason,
      rule: poseValidation?.rule ?? 'missing_face',
    );

    if (poseValidation != null) {
      _logPoseDebug(poseValidation, phase: 'BACKGROUND');
    }
    debugPrint(
      '[FACE_ENROLL][OBSERVATION] '
      'pose=${observation.targetPose} '
      'frameId=${observation.frameId} '
      'frameAgeMs=${observation.frameAgeMs} '
      'poseValid=${observation.poseValid} '
      'qualityValid=${observation.qualityValid} '
      'faceSizeValid=${observation.faceSizeValid} '
      'landmarksValid=${observation.landmarksValid} '
      'freshnessValid=${observation.freshnessValid} '
      'otherQualityValid=${observation.otherQualityValid} '
      'failureReason=${observation.failureReason ?? '-'}',
    );
    debugPrint(
      '[FACE_ENROLL][BOUNDARY] '
      'source=${sourceWidth.toStringAsFixed(0)}x${sourceHeight.toStringAsFixed(0)} '
      'box=(${boxLeft.toStringAsFixed(1)},${boxTop.toStringAsFixed(1)},${boxRight.toStringAsFixed(1)},${boxBottom.toStringAsFixed(1)}) '
      'normalized=${normalizedBox == null ? '-' : '(${normalizedBox.left.toStringAsFixed(3)},${normalizedBox.top.toStringAsFixed(3)},${normalizedBox.right.toStringAsFixed(3)},${normalizedBox.bottom.toStringAsFixed(3)})'} '
      'faceCenter=(${faceCenterX.toStringAsFixed(1)},${faceCenterY.toStringAsFixed(1)}) '
      'eyesInside=$eyesInside '
      'boxIntersectionRatio=${boxIntersectionRatio.toStringAsFixed(3)} '
      'critical=$boundaryCritical '
      'paddingRequired=${boundaryCritical ? 'true' : 'false'}',
    );
    return observation;
  }

  double _computeQualityScore(Face face) {
    var score = 0.0;
    final leftEye = face.landmarks[FaceLandmarkType.leftEye]?.position != null;
    final rightEye = face.landmarks[FaceLandmarkType.rightEye]?.position != null;
    if (leftEye && rightEye) score += 0.35;
    if (_isPoseStable(face)) score += 0.25;
    if (face.boundingBox.width >= 80 && face.boundingBox.height >= 80) score += 0.2;
    if (face.boundingBox.left > 4 && face.boundingBox.top > 4) score += 0.1;
    if (face.boundingBox.right < (_latestImageSize?.width ?? double.infinity) - 4 &&
        face.boundingBox.bottom < (_latestImageSize?.height ?? double.infinity) - 4) {
      score += 0.1;
    }
    return score.clamp(0.0, 1.0);
  }

  _LivenessResult _estimateLiveness(Face face) {
    final challenges = <String>[];
    var score = 0.45;
    final leftEye = face.leftEyeOpenProbability ?? 1.0;
    final rightEye = face.rightEyeOpenProbability ?? 1.0;
    if (leftEye < 0.35 || rightEye < 0.35) {
      score += 0.25;
      challenges.add('blink');
    }
    final yaw = face.headEulerAngleY ?? 0.0;
    if (yaw.abs() >= 10) {
      score += 0.2;
      challenges.add(yaw > 0 ? 'turn_right' : 'turn_left');
    }
    final pitch = face.headEulerAngleX ?? 0.0;
    if (pitch.abs() >= 10) {
      score += 0.1;
      challenges.add(pitch > 0 ? 'head_up' : 'head_down');
    }
    return _LivenessResult(
      score: score.clamp(0.0, 1.0),
      challenges: challenges,
    );
  }

  PoseValidationResult validateEnrollmentPose(
    Face face, {
    required String targetPose,
  }) {
    final rawYaw = face.headEulerAngleY ?? 0.0;
    final rawPitch = face.headEulerAngleX ?? 0.0;
    final rawRoll = face.headEulerAngleZ ?? 0.0;
    final semanticYaw = _semanticYawForUser(face);
    final semanticPitch = rawPitch;
    final rule = switch (targetPose) {
      'front' => 'abs(semanticYaw)<10 && abs(semanticPitch)<10 && abs(rawRoll)<10',
      'left' => 'semanticYaw<=-10 && abs(semanticPitch)<18 && abs(rawRoll)<18',
      'right' => 'semanticYaw>=10 && abs(semanticPitch)<18 && abs(rawRoll)<18',
      'up' => 'semanticPitch>=10 && abs(semanticYaw)<18 && abs(rawRoll)<18',
      'down' => 'semanticPitch<=-10 && abs(semanticYaw)<18 && abs(rawRoll)<18',
      _ => 'unknown',
    };

    final valid = switch (targetPose) {
      'front' => semanticYaw.abs() < 10 && semanticPitch.abs() < 10 && rawRoll.abs() < 10,
      'left' => semanticYaw <= -10 && semanticPitch.abs() < 18 && rawRoll.abs() < 18,
      'right' => semanticYaw >= 10 && semanticPitch.abs() < 18 && rawRoll.abs() < 18,
      'up' => semanticPitch >= 10 && semanticYaw.abs() < 18 && rawRoll.abs() < 18,
      'down' => semanticPitch <= -10 && semanticYaw.abs() < 18 && rawRoll.abs() < 18,
      _ => false,
    };

    return PoseValidationResult(
      targetPose: targetPose,
      semanticPose: switch (targetPose) {
        'front' => 'front',
        'left' => semanticYaw <= -10 ? 'left' : 'front',
        'right' => semanticYaw >= 10 ? 'right' : 'front',
        'up' => semanticPitch >= 10 ? 'up' : 'front',
        'down' => semanticPitch <= -10 ? 'down' : 'front',
        _ => 'searching',
      },
      rawYaw: rawYaw,
      rawPitch: rawPitch,
      rawRoll: rawRoll,
      semanticYaw: semanticYaw,
      semanticPitch: semanticPitch,
      valid: valid,
      failureReason: valid ? null : 'Pose belum sesuai. ${_posePrompts[targetPose] ?? targetPose}.',
      rule: rule,
    );
  }

  void _logPoseDebug(
    PoseValidationResult validation, {
    required String phase,
  }) {
    final now = DateTime.now();
    if (phase == 'BACKGROUND') {
      final last = _lastPoseDebugAt;
      if (last != null && now.difference(last).inMilliseconds < 400) {
        return;
      }
      _lastPoseDebugAt = now;
    }
    debugPrint(
      '[FACE_ENROLL][POSE_DEBUG] '
      'phase=$phase '
      'target=${validation.targetPose} '
      'rawYaw=${validation.rawYaw.toStringAsFixed(2)} '
      'rawPitch=${validation.rawPitch.toStringAsFixed(2)} '
      'rawRoll=${validation.rawRoll.toStringAsFixed(2)} '
      'semanticYaw=${validation.semanticYaw.toStringAsFixed(2)} '
      'semanticPitch=${validation.semanticPitch.toStringAsFixed(2)} '
      'lens=${_controller?.description.lensDirection.name ?? 'unknown'} '
      'rule=${validation.rule} '
      'valid=${validation.valid} '
      'failureReason=${validation.failureReason ?? '-'}',
    );
  }

  void _logConsistencyDiagnostics() {
    if (_sampleEmbeddings.length < 2) {
      return;
    }
    final values = <double>[];
    for (var i = 1; i < _sampleEmbeddings.length; i++) {
      values.add(_math.cosineSimilarity(_sampleEmbeddings.first, _sampleEmbeddings[i]));
    }
    final min = values.reduce((a, b) => a < b ? a : b);
    final mean = values.reduce((a, b) => a + b) / values.length;
    debugPrint(
      '[FACE_ENROLL][CONSISTENCY] '
      'count=${_sampleEmbeddings.length} '
      'min=${min.toStringAsFixed(6)} '
      'mean=${mean.toStringAsFixed(6)}',
    );
  }

  List<double> _buildCentroid() {
    if (_sampleEmbeddings.length != 5) {
      throw Exception('Sample count bukan 5.');
    }
    return _math.centroid(_sampleEmbeddings);
  }

  Future<void> _submitEnrollment() async {
    if (_submitInFlight) {
      return;
    }
    final userId = widget.currentUserId;
    if (userId == null) {
      throw Exception('User ID tidak ditemukan dari sesi aktif. Silakan login ulang.');
    }

    setState(() {
      _submitInFlight = true;
      _submitting = true;
      _status = 'Mengirim profil biometrik...';
    });

    try {
      final centroid = _buildCentroid();
      final livenessScore = _computeAggregateLiveness();
      final payload = <String, dynamic>{
        'user_id': userId,
        'engine': 'tflite',
        'model': 'mobilefacenet',
        'model_version': 'mobilefacenet-be4bc7cf',
        'dimension': 192,
        'embedding': centroid,
        'samples': _sampleEmbeddings,
        'quality_score': _computeAggregateQuality(),
        'liveness_score': livenessScore,
        'liveness_challenges': _livenessChallenges.toSet().toList(growable: false),
        'metadata': {
          'platform': defaultTargetPlatform.name,
          'sample_count': _sampleEmbeddings.length,
          'alignment_method': 'eye_similarity',
          'input_size': '112x112',
          'pose_names': _samplePoses,
          'liveness_challenges': _livenessChallenges.toSet().toList(growable: false),
        },
      };

      debugPrint(
        '[FACE_ENROLL][SUBMIT] '
        'engine=tflite '
        'model=mobilefacenet '
        'modelVersion=mobilefacenet-be4bc7cf '
        'dimension=192 '
        'sampleCount=${_sampleEmbeddings.length}',
      );

      final result = await widget.repository.enrollBiometricProfile(payload: payload);
      if (!mounted) {
        return;
      }

      setState(() {
        _status = 'Pendaftaran wajah berhasil.';
        _submitError = null;
      });

      await showDialog<void>(
        context: context,
        barrierDismissible: false,
        builder: (dialogContext) {
          return AlertDialog(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
            title: const Text('Pendaftaran Wajah Berhasil'),
            content: Text(
              (result['_message'] as String?) ??
                  'Data wajah untuk aplikasi telah diperbarui.',
            ),
            actions: [
              FilledButton(
                onPressed: () => Navigator.of(dialogContext).pop(),
                child: const Text('Selesai'),
              ),
            ],
          );
        },
      );

      if (mounted) {
        Navigator.of(context).pop(result);
      }
      debugPrint('[FACE_ENROLL][SUCCESS]');
    } catch (error, st) {
      debugPrint('[FACE_ENROLL][ERROR] ${error.runtimeType}: $error');
      debugPrintStack(stackTrace: st);
      if (!mounted) {
        return;
      }
      setState(() {
        _submitError = error.toString().replaceFirst('Exception: ', '');
        _status = _submitError!;
        _submitting = false;
        _submitInFlight = false;
      });
      return;
    } finally {
      if (mounted) {
        setState(() {
          _submitting = false;
          _submitInFlight = false;
        });
      } else {
        _submitting = false;
        _submitInFlight = false;
      }
    }
  }

  double _computeAggregateQuality() {
    if (_sampleQualityScores.isEmpty) {
      return 0.0;
    }
    final sum = _sampleQualityScores.fold<double>(0.0, (acc, value) => acc + value);
    return (sum / _sampleQualityScores.length).clamp(0.0, 1.0);
  }

  double _computeAggregateLiveness() {
    if (_sampleLivenessScores.isEmpty) {
      return 0.0;
    }
    final sum = _sampleLivenessScores.fold<double>(0.0, (acc, value) => acc + value);
    return (sum / _sampleLivenessScores.length).clamp(0.0, 1.0);
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
    if (_currentPoseIndex >= _poseOrder.length) {
      return;
    }
    if (_sampleEmbeddings.length >= 5) {
      return;
    }

    setState(() {
      _processingFrame = true;
      _error = null;
      _status = 'Memproses sample wajah...';
    });

    try {
      final sample = await _captureCurrentSample();
      if (sample == null) {
        _resetPoseCandidate(
          reason: 'capture_invalid_pose',
          stableFrames: _poseCandidateState.validFrameCount,
        );
        return;
      }
      _sampleEmbeddings.add(sample.embedding);
      _samplePoses.add(sample.pose);
      _sampleQualityScores.add(sample.qualityScore);
      _sampleLivenessScores.add(sample.livenessScore);
      _poseEmbeddings[sample.pose] = sample.embedding;
      _poseSequence.add(sample.pose);
      _currentPoseIndex = math.min(_currentPoseIndex + 1, _poseOrder.length);
      _poseInstruction = _currentPoseIndex < _poseOrder.length
          ? _posePrompts[_poseOrder[_currentPoseIndex]]!
          : 'Review sample sebelum kirim.';
      _nextStepHint = _nextHintForPose(sample.pose);
      _resetPoseCandidate(
        reason: 'capture_started',
        stableFrames: _poseCandidateState.validFrameCount,
      );

      debugPrint(
        '[FACE_ENROLL][EMBEDDING] '
        'pose=${sample.pose} '
        'dimension=${sample.embedding.length} '
        'finite=${sample.embedding.every((value) => value.isFinite)} '
        'norm=${sample.norm.toStringAsFixed(6)}',
      );

      debugPrint(
        '[FACE_ENROLL][POSE_COMPLETE] '
        'pose=${sample.pose} '
        'sampleCount=${_sampleEmbeddings.length}',
      );

      if (_currentPoseIndex < _poseOrder.length) {
        debugPrint(
          '[FACE_ENROLL][AUTO_ADVANCE] '
          'from=${sample.pose} '
          'to=${_currentPose()}',
        );
      }

      _logConsistencyDiagnostics();

      if (_sampleEmbeddings.length == 5) {
        debugPrint('[FACE_ENROLL][AUTO_SUBMIT] sampleCount=5');
        await _submitEnrollment();
        return;
      }

      if (mounted) {
        setState(() {
          _status = 'Sample ${_sampleEmbeddings.length}/5 tersimpan.';
        });
      }

    } catch (error, st) {
      debugPrint(
        '[FACE_ENROLL][ERROR] ${error.runtimeType}: $error',
      );
      debugPrintStack(stackTrace: st);
      if (!mounted) {
        return;
      }
      setState(() {
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error!;
      });
    } finally {
      if (mounted) {
        setState(() {
          _processingFrame = false;
        });
      } else {
        _processingFrame = false;
      }
    }
  }

  @override
  void dispose() {
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
              Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                decoration: BoxDecoration(
                  color: _readyToCapture ? _enrollPrimarySoft : const Color(0xFFF1F4F3),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: _readyToCapture ? _enrollPrimaryBorder : const Color(0xFFE1E6E3),
                  ),
                ),
                child: Text(
                  _loading
                      ? 'Menyiapkan kamera...'
                      : _submitting
                          ? 'Mengirim profil biometrik...'
                          : _readyToCapture
                                  ? 'Pose valid. Sample akan diambil otomatis.'
                                  : 'Tunggu pose yang diminta agar stabil.',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: _enrollText,
                    fontSize: 13,
                    fontWeight: FontWeight.w700,
                    height: 1.35,
                  ),
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
      progress += 0.2;
    }
    if (known.contains('left')) {
      progress += 0.2;
    }
    if (known.contains('right')) {
      progress += 0.2;
    }
    if (known.contains('up')) {
      progress += 0.2;
    }
    if (known.contains('down')) {
      progress += 0.2;
    }
    return progress.clamp(0.0, 1.0);
  }
}

class PoseCandidateState {
  String? targetPose;
  int validFrameCount = 0;
  int missCount = 0;
  DateTime? startedAt;
  DateTime? lastValidAt;
  int? lastFrameId;
  bool acquired = false;
  final int graceMs = 400;

  void reset() {
    targetPose = null;
    validFrameCount = 0;
    missCount = 0;
    startedAt = null;
    lastValidAt = null;
    lastFrameId = null;
    acquired = false;
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

class _EnrollmentSample {
  const _EnrollmentSample({
    required this.pose,
    required this.embedding,
    required this.norm,
    required this.qualityScore,
    required this.livenessScore,
    required this.livenessChallenges,
    required this.frameAgeMs,
    required this.alignmentMethod,
  });

  final String pose;
  final List<double> embedding;
  final double norm;
  final double qualityScore;
  final double livenessScore;
  final List<String> livenessChallenges;
  final int frameAgeMs;
  final String alignmentMethod;
}

class EnrollmentObservation {
  const EnrollmentObservation({
    required this.frameId,
    required this.capturedAt,
    required this.frameAgeMs,
    required this.face,
    required this.targetPose,
    required this.faceCount,
    required this.eyesAvailable,
    required this.rawYaw,
    required this.rawPitch,
    required this.rawRoll,
    required this.semanticYaw,
    required this.semanticPitch,
    required this.poseValid,
    required this.faceSizeValid,
    required this.landmarksValid,
    required this.freshnessValid,
    required this.otherQualityValid,
    required this.qualityValid,
    required this.failureReason,
    required this.rule,
  });

  final int frameId;
  final DateTime capturedAt;
  final int frameAgeMs;
  final Face? face;
  final String targetPose;
  final int faceCount;
  final bool eyesAvailable;
  final double rawYaw;
  final double rawPitch;
  final double rawRoll;
  final double semanticYaw;
  final double semanticPitch;
  final bool poseValid;
  final bool faceSizeValid;
  final bool landmarksValid;
  final bool freshnessValid;
  final bool otherQualityValid;
  final bool qualityValid;
  final String? failureReason;
  final String rule;

  bool get canEnterCandidate => face != null && poseValid && qualityValid && failureReason == null;
}

class _LivenessResult {
  const _LivenessResult({
    required this.score,
    required this.challenges,
  });

  final double score;
  final List<String> challenges;
}

class PoseValidationResult {
  const PoseValidationResult({
    required this.targetPose,
    required this.semanticPose,
    required this.rawYaw,
    required this.rawPitch,
    required this.rawRoll,
    required this.semanticYaw,
    required this.semanticPitch,
    required this.valid,
    required this.failureReason,
    required this.rule,
  });

  final String targetPose;
  final String semanticPose;
  final double rawYaw;
  final double rawPitch;
  final double rawRoll;
  final double semanticYaw;
  final double semanticPitch;
  final bool valid;
  final String? failureReason;
  final String rule;
}
