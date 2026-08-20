import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';
import 'package:image_picker/image_picker.dart';

import '../../services/teacher_mobile_repository.dart';

const _enrollPrimary = Color(0xFF00745A);
const _enrollPrimaryDark = Color(0xFF00553F);
const _enrollPrimarySoft = Color(0xFFE5F5F0);
const _enrollPrimaryBorder = Color(0xFFDCE7E3);
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
  final ImagePicker _picker = ImagePicker();
  bool _loading = false;
  String _status = 'Siapkan wajah Anda di depan kamera.';
  String? _error;

  Future<void> _captureAndEnroll() async {
    if (_loading) return;
    setState(() {
      _loading = true;
      _error = null;
      _status = 'Membuka kamera...';
    });

    try {
      final image = await _picker.pickImage(
        source: ImageSource.camera,
        preferredCameraDevice: CameraDevice.front,
        imageQuality: 92,
      );

      if (image == null) {
        setState(() {
          _status = 'Pendaftaran dibatalkan.';
        });
        return;
      }

      final processed = await _analyzeImage(image.path);
      if (processed == null) {
        setState(() {
          _error = 'Wajah tidak terdeteksi. Coba lagi dengan pencahayaan lebih baik.';
          _status = _error!;
        });
        return;
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

      if (!mounted) return;

      setState(() {
        _status = (result['_message'] as String?) ??
            'Pendaftaran wajah berhasil disimpan.';
      });

      Navigator.of(context).pop(result);
    } catch (error) {
      if (!mounted) return;
      setState(() {
        _error = error.toString().replaceFirst('Exception: ', '');
        _status = _error!;
      });
    } finally {
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
      final inputImage = InputImage.fromFilePath(path);
      final faces = await detector.processImage(inputImage);
      if (faces.isEmpty) {
        return null;
      }

      final face = faces.first;
      return {
        'selfie_data': 'data:image/jpeg;base64,${base64Encode(await XFile(path).readAsBytes())}',
        'face_descriptor': _buildDescriptor(face),
        'liveness_score': _estimateLiveness(face)['score'],
        'liveness_challenges': _estimateLiveness(face)['challenges'],
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
    var score = 0.4;
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
      score += 0.15;
      challenges.add('head_tilt');
    }
    return {
      'score': score.clamp(0.0, 1.0),
      'challenges': challenges,
    };
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        foregroundColor: _enrollText,
        elevation: 0,
        titleSpacing: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              widget.title,
              style: const TextStyle(
                color: _enrollText,
                fontWeight: FontWeight.w800,
              ),
            ),
            Text(
              widget.description,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                color: _enrollMuted,
                fontWeight: FontWeight.w700,
                fontSize: 11,
              ),
            ),
          ],
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: _enrollPrimaryBorder),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Pendaftaran Wajah',
                      style: TextStyle(
                        color: _enrollText,
                        fontSize: 18,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      _status,
                      style: const TextStyle(
                        color: _enrollMuted,
                        height: 1.4,
                      ),
                    ),
                    if (_error != null) ...[
                      const SizedBox(height: 8),
                      Text(
                        _error!,
                        style: const TextStyle(
                          color: Colors.red,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                    const SizedBox(height: 14),
                    FilledButton.icon(
                      onPressed: _loading ? null : _captureAndEnroll,
                      style: FilledButton.styleFrom(
                        backgroundColor: _enrollPrimary,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                      ),
                      icon: _loading
                          ? const SizedBox(
                              width: 16,
                              height: 16,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Icon(Icons.camera_alt_rounded),
                      label: Text(_loading ? 'Memproses...' : 'Ambil & Simpan Wajah'),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 12),
              Expanded(
                child: Container(
                  decoration: BoxDecoration(
                    color: _enrollPrimarySoft,
                    borderRadius: BorderRadius.circular(24),
                    border: Border.all(color: _enrollPrimaryBorder),
                  ),
                  child: const Center(
                    child: Icon(
                      Icons.face_retouching_natural_rounded,
                      size: 72,
                      color: _enrollPrimaryDark,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
