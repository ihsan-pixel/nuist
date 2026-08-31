import 'dart:math' as math;
import 'package:flutter/foundation.dart';
import 'package:image/image.dart' as img;
import 'package:tflite_flutter/tflite_flutter.dart';

class FaceEmbeddingModelConfig {
  const FaceEmbeddingModelConfig({
    required this.name,
    required this.version,
    required this.assetPath,
    required this.inputWidth,
    required this.inputHeight,
    required this.channels,
    required this.outputDimension,
    required this.normalization,
    required this.colorOrder,
    required this.tensorLayout,
    this.engine = 'tflite',
  });

  final String name;
  final String version;
  final String assetPath;
  final int inputWidth;
  final int inputHeight;
  final int channels;
  final int outputDimension;
  final String normalization;
  final String colorOrder;
  final String tensorLayout;
  final String engine;

  String get model => name;
  String get modelVersion => version;
  int get dimension => outputDimension;
}

class FaceModelContractException implements Exception {
  FaceModelContractException(this.code, [this.message = '']);

  final String code;
  final String message;

  @override
  String toString() => message.isEmpty ? code : '$code: $message';
}

class FaceRecognitionService {
  FaceRecognitionService({
    Future<Interpreter> Function()? interpreterFactory,
    FaceEmbeddingModelConfig? modelConfig,
  })  : _interpreterFactory = interpreterFactory ?? _defaultInterpreterFactory,
        modelConfig = modelConfig ??
            const FaceEmbeddingModelConfig(
              name: 'mobilefacenet',
              version: 'mobilefacenet-be4bc7cf',
              assetPath: 'assets/models/mobilefacenet.tflite',
              inputWidth: 112,
              inputHeight: 112,
              channels: 3,
              outputDimension: 192,
              normalization: '(pixel - 127.5) / 128.0',
              colorOrder: 'rgb',
              tensorLayout: 'nhwc',
            );

  static const List<int> _expectedInputShape = <int>[1, 112, 112, 3];
  static const List<int> _expectedOutputShape = <int>[1, 192];

  final Future<Interpreter> Function() _interpreterFactory;
  final FaceEmbeddingModelConfig modelConfig;
  FaceEmbeddingModelConfig get modelInfo => modelConfig;

  Interpreter? _interpreter;
  bool _initialized = false;

  static Future<Interpreter> _defaultInterpreterFactory() {
    return Interpreter.fromAsset('assets/models/mobilefacenet.tflite');
  }

  bool get isInitialized => _initialized;

  Future<void> initialize() async {
    if (_initialized) {
      return;
    }

    final interpreter = await _interpreterFactory();
    _validateContract(interpreter);
    _interpreter = interpreter;
    _initialized = true;
  }

  Future<List<double>> generateEmbedding(Uint8List imageBytes) async {
    final interpreter = _interpreter;
    if (!_initialized || interpreter == null) {
      throw FaceModelContractException(
        'FACE_MODEL_NOT_INITIALIZED',
        'FaceRecognitionService must be initialized before inference.',
      );
    }

    final decoded = img.decodeImage(imageBytes);
    if (decoded == null) {
      throw FaceModelContractException(
        'FACE_IMAGE_INVALID',
        'Unable to decode input image bytes.',
      );
    }

    final normalizedInput = _prepareInput(decoded);
    _logInputStats(normalizedInput);
    final output = List.generate(
      1,
      (_) => List<double>.filled(modelConfig.dimension, 0.0, growable: false),
      growable: false,
    );

    final outputTensor = interpreter.getOutputTensor(0);
    if (!_shapeEquals(outputTensor.shape, _expectedOutputShape) ||
        outputTensor.type != TensorType.float32) {
      throw FaceModelContractException(
        'FACE_MODEL_OUTPUT_CONTRACT_INVALID',
        'Output tensor must be $_expectedOutputShape float32, found shape ${outputTensor.shape} and type ${outputTensor.type}.',
      );
    }

    debugPrint(
      '[FACE_DEBUG][MODEL_CONTRACT] '
      'outputTensorShape=${outputTensor.shape} '
      'providedOutputShape=[1, ${modelConfig.dimension}]',
    );

    interpreter.run(normalizedInput, output);

    final rawEmbedding = List<double>.from(output[0]);
    _logRawOutputStats(rawEmbedding);

    if (rawEmbedding.length != modelConfig.dimension) {
      throw FaceModelContractException(
        'FACE_MODEL_OUTPUT_INVALID',
        'Unexpected embedding length: ${rawEmbedding.length}.',
      );
    }

    final norm = _l2Norm(rawEmbedding);
    if (norm <= 0) {
      throw FaceModelContractException(
        'FACE_EMBEDDING_INVALID',
        'Embedding norm is zero.',
      );
    }

    final normalizedEmbedding = rawEmbedding.map((value) => value / norm).toList(growable: false);
    _logNormalizedOutputStats(normalizedEmbedding);
    debugPrint(
      '[FACE_DEBUG][OK][TFLITE_INFERENCE] '
      'outputShape=[1,192] '
      'dimension=${rawEmbedding.length} '
      'inference_ms=${interpreter.lastNativeInferenceDurationMicroSeconds / 1000.0}',
    );
    debugPrint(
      '[FACE_DEBUG][OK][EMBEDDING_VALIDATION] '
      'dimension=${normalizedEmbedding.length} '
      'allFinite=${normalizedEmbedding.every((value) => value.isFinite)} '
      'nonZero=${normalizedEmbedding.any((value) => value != 0.0)} '
      'norm=${_l2Norm(normalizedEmbedding).toStringAsFixed(6)}',
    );

    return normalizedEmbedding;
  }

  void dispose() {
    _interpreter?.close();
    _interpreter = null;
    _initialized = false;
  }

  void _validateContract(Interpreter interpreter) {
    final inputTensor = interpreter.getInputTensor(0);
    final outputTensor = interpreter.getOutputTensor(0);

    if (!_shapeEquals(inputTensor.shape, _expectedInputShape) || inputTensor.type != TensorType.float32) {
      throw FaceModelContractException(
        'FACE_MODEL_CONTRACT_INVALID',
        'Input tensor must be $_expectedInputShape float32, found shape ${inputTensor.shape} and type ${inputTensor.type}.',
      );
    }

    if (!_shapeEquals(outputTensor.shape, _expectedOutputShape) || outputTensor.type != TensorType.float32) {
      throw FaceModelContractException(
        'FACE_MODEL_CONTRACT_INVALID',
        'Output tensor must be $_expectedOutputShape float32, found shape ${outputTensor.shape} and type ${outputTensor.type}.',
      );
    }

    if (modelConfig.dimension != _expectedOutputShape[1]) {
      throw FaceModelContractException(
        'FACE_MODEL_CONTRACT_INVALID',
        'Model config dimension ${modelConfig.dimension} does not match output dimension ${_expectedOutputShape[1]}.',
      );
    }
  }

  List<List<List<List<double>>>> _prepareInput(img.Image source) {
    final resized = img.copyResize(
      source,
      width: modelConfig.inputWidth,
      height: modelConfig.inputHeight,
      interpolation: img.Interpolation.linear,
    );

    final buffer = List.generate(
      1,
      (_) => List.generate(
        modelConfig.inputHeight,
        (_) => List.generate(
          modelConfig.inputWidth,
          (_) => List<double>.filled(3, 0.0, growable: false),
          growable: false,
        ),
        growable: false,
      ),
      growable: false,
    );

    for (var y = 0; y < modelConfig.inputHeight; y++) {
      for (var x = 0; x < modelConfig.inputWidth; x++) {
        final pixel = resized.getPixel(x, y);
        buffer[0][y][x][0] = (pixel.r.toDouble() - 127.5) / 128.0;
        buffer[0][y][x][1] = (pixel.g.toDouble() - 127.5) / 128.0;
        buffer[0][y][x][2] = (pixel.b.toDouble() - 127.5) / 128.0;
      }
    }

    return buffer;
  }

  void _logInputStats(List<List<List<List<double>>>> input) {
    if (!kDebugMode) {
      return;
    }

    final values = input.expand((b) => b).expand((row) => row).expand((pixel) => pixel).toList(growable: false);
    _logVectorStats('[FACE_MODEL][INPUT_STATS]', values);
  }

  void _logRawOutputStats(List<double> values) {
    if (!kDebugMode) {
      return;
    }
    _logVectorStats('[FACE_MODEL][RAW_OUTPUT_STATS]', values);
  }

  void _logNormalizedOutputStats(List<double> values) {
    if (!kDebugMode) {
      return;
    }
    _logVectorStats('[FACE_MODEL][NORMALIZED_OUTPUT_STATS]', values);
  }

  void _logVectorStats(String tag, List<double> values) {
    if (values.isEmpty) {
      return;
    }

    final min = values.reduce((a, b) => a < b ? a : b);
    final max = values.reduce((a, b) => a > b ? a : b);
    final mean = values.reduce((a, b) => a + b) / values.length;
    final variance = values.fold<double>(0.0, (acc, value) {
      final diff = value - mean;
      return acc + diff * diff;
    }) / values.length;
    final stddev = math.sqrt(variance);
    final norm = _l2Norm(values);

    debugPrint(
      '$tag '
      'dimension=${values.length} '
      'norm=${norm.toStringAsFixed(6)} '
      'mean=${mean.toStringAsFixed(6)} '
      'stddev=${stddev.toStringAsFixed(6)} '
      'min=${min.toStringAsFixed(6)} '
      'max=${max.toStringAsFixed(6)}',
    );
  }

  static double _l2Norm(List<double> values) {
    var sum = 0.0;
    for (final value in values) {
      sum += value * value;
    }
    return sum == 0.0 ? 0.0 : math.sqrt(sum);
  }

  static bool _shapeEquals(List<int> actual, List<int> expected) {
    if (actual.length != expected.length) {
      return false;
    }

    for (var i = 0; i < actual.length; i++) {
      if (actual[i] != expected[i]) {
        return false;
      }
    }
    return true;
  }
}
