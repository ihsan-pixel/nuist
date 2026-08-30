import 'dart:math' as math;

class FaceEnrollmentMath {
  const FaceEnrollmentMath();

  List<double> normalizeL2(List<double> values) {
    if (values.isEmpty) {
      throw ArgumentError('values must not be empty');
    }
    final norm = l2Norm(values);
    if (norm <= 0 || !norm.isFinite) {
      throw ArgumentError('values must have a positive finite norm');
    }
    return values.map((value) {
      if (!value.isFinite) {
        throw ArgumentError('values must be finite');
      }
      return value / norm;
    }).toList(growable: false);
  }

  List<double> centroid(List<List<double>> samples) {
    if (samples.isEmpty) {
      throw ArgumentError('samples must not be empty');
    }
    final length = samples.first.length;
    if (length == 0) {
      throw ArgumentError('samples must not be empty vectors');
    }
    for (final sample in samples) {
      if (sample.length != length) {
        throw ArgumentError('sample dimensions must match');
      }
      if (sample.any((value) => !value.isFinite)) {
        throw ArgumentError('samples must be finite');
      }
    }

    final sums = List<double>.filled(length, 0.0, growable: false);
    for (final sample in samples) {
      for (var i = 0; i < length; i++) {
        sums[i] += sample[i];
      }
    }

    final averaged = sums.map((value) => value / samples.length).toList(growable: false);
    return normalizeL2(averaged);
  }

  double cosineSimilarity(List<double> a, List<double> b) {
    if (a.length != b.length) {
      throw ArgumentError('vectors must have the same length');
    }
    if (a.isEmpty) {
      throw ArgumentError('vectors must not be empty');
    }
    var dot = 0.0;
    var normA = 0.0;
    var normB = 0.0;
    for (var i = 0; i < a.length; i++) {
      final va = a[i];
      final vb = b[i];
      if (!va.isFinite || !vb.isFinite) {
        throw ArgumentError('vectors must be finite');
      }
      dot += va * vb;
      normA += va * va;
      normB += vb * vb;
    }
    if (normA <= 0 || normB <= 0) {
      throw ArgumentError('vectors must have positive norm');
    }
    final value = dot / (math.sqrt(normA) * math.sqrt(normB));
    return value.clamp(-1.0, 1.0).toDouble();
  }

  double l2Norm(List<double> values) {
    var sum = 0.0;
    for (final value in values) {
      if (!value.isFinite) {
        throw ArgumentError('values must be finite');
      }
      sum += value * value;
    }
    return sum <= 0 ? 0.0 : math.sqrt(sum);
  }
}
