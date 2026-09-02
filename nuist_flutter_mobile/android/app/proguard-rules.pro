# tflite_flutter references the optional GPU delegate, which is not bundled
# by this app because face inference uses the CPU delegate.
-dontwarn org.tensorflow.lite.gpu.GpuDelegateFactory$Options
