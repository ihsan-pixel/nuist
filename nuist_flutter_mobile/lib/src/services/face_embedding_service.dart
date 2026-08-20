import 'dart:io';

import 'package:tflite_flutter/tflite_flutter.dart';

class FaceEmbeddingService {
  FaceEmbeddingService({
    this.assetPath = 'assets/models/face_embedding.tflite',
  });

  final String assetPath;

  Interpreter? _interpreter;
  bool _loading = false;

  Future<List<double>?> extractEmbedding(File imageFile) async {
    final interpreter = await _loadInterpreter();
    if (interpreter == null) {
      return null;
    }

    try {
      final imageBytes = await imageFile.readAsBytes();
      if (imageBytes.isEmpty) {
        return null;
      }

      // The actual preprocessing depends on the chosen embedding model.
      // Return null for now if no model-specific preprocessing is wired yet.
      // This keeps the app stable while preserving the new integration points.
      return null;
    } catch (_) {
      return null;
    }
  }

  Future<Interpreter?> _loadInterpreter() async {
    if (_interpreter != null) {
      return _interpreter;
    }
    if (_loading) {
      return null;
    }
    _loading = true;
    try {
      _interpreter = await Interpreter.fromAsset(assetPath);
      return _interpreter;
    } catch (_) {
      return null;
    } finally {
      _loading = false;
    }
  }
}
