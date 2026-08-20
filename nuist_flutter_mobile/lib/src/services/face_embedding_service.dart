import 'dart:io';

class FaceEmbeddingService {
  Future<List<double>?> extractEmbedding(File imageFile) async {
    if (!await imageFile.exists()) {
      return null;
    }

    // Stub aman sementara: embedding model belum dipasang.
    // Jalur fallback descriptor lama tetap berjalan tanpa mengganggu presensi.
    return null;
  }
}

