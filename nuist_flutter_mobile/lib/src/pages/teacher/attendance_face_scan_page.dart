import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

import '../../config/app_config.dart';

const _scanPrimary = Color(0xFF00745A);
const _scanPrimaryDark = Color(0xFF00553F);
const _scanPrimarySoft = Color(0xFFE5F5F0);
const _scanPrimaryBorder = Color(0xFFDCE7E3);
const _scanText = Color(0xFF172A24);
const _scanMuted = Color(0xFF64746E);

class AttendanceFaceScanPage extends StatefulWidget {
  const AttendanceFaceScanPage({
    super.key,
    required this.title,
    required this.description,
  });

  final String title;
  final String description;

  @override
  State<AttendanceFaceScanPage> createState() => _AttendanceFaceScanPageState();
}

class _AttendanceFaceScanPageState extends State<AttendanceFaceScanPage> {
  static const _defaultInstruction =
      'Posisikan wajah di dalam bingkai lalu ikuti instruksi scan.';

  late final WebViewController _controller;
  bool _pageLoading = true;
  bool _autoStartIssued = false;
  bool _scanActive = false;
  bool _cameraReady = false;
  String _instruction = _defaultInstruction;
  String _status = 'Menyiapkan halaman scan wajah.';
  String? _error;
  Map<String, String> _stepStates = const {
    'align': 'pending',
    'blink': 'pending',
    'challenge': 'pending',
    'done': 'pending',
  };

  @override
  void initState() {
    super.initState();
    _controller = WebViewController(
      onPermissionRequest: (request) {
        request.grant();
      },
    )
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(Colors.transparent)
      ..addJavaScriptChannel(
        'FaceScanBridge',
        onMessageReceived: _handleBridgeMessage,
      )
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageFinished: (_) {
            if (!mounted) {
              return;
            }
            setState(() {
              _pageLoading = false;
            });
            _maybeStartScan();
          },
          onWebResourceError: (error) {
            if (!mounted || error.isForMainFrame != true) {
              return;
            }
            setState(() {
              _pageLoading = false;
              _scanActive = false;
              _error = error.description;
              _status = 'Halaman scan wajah tidak dapat dimuat.';
            });
          },
        ),
      )
      ..loadRequest(Uri.parse(AppConfig.attendanceFaceScanBridgeUrl));
  }

  Future<void> _startScan({bool resetState = false}) async {
    if (_pageLoading) {
      return;
    }

    if (mounted) {
      setState(() {
        _scanActive = true;
        _error = null;
        _status = 'Memulai scan wajah.';
        _instruction = _defaultInstruction;
        if (resetState) {
          _stepStates = const {
            'align': 'pending',
            'blink': 'pending',
            'challenge': 'pending',
            'done': 'pending',
          };
        }
      });
    }

    try {
      await _controller.runJavaScript('window.FaceScanPage?.start();');
    } catch (_) {
      if (!mounted) {
        return;
      }
      setState(() {
        _scanActive = false;
        _error = 'Perintah scan wajah tidak dapat dijalankan.';
      });
    }
  }

  void _maybeStartScan() {
    if (_autoStartIssued) {
      return;
    }
    _autoStartIssued = true;
    _startScan(resetState: true);
  }

  void _handleBridgeMessage(JavaScriptMessage message) {
    Map<String, dynamic> event;

    try {
      final decoded = jsonDecode(message.message);
      if (decoded is! Map) {
        return;
      }
      event = Map<String, dynamic>.from(decoded);
    } catch (_) {
      return;
    }

    final type = event['type'] as String? ?? '';
    final payload = event['payload'] is Map
        ? Map<String, dynamic>.from(event['payload'] as Map)
        : const <String, dynamic>{};

    if (!mounted) {
      return;
    }

    switch (type) {
      case 'page_ready':
        setState(() {
          _status = 'Halaman scan siap digunakan.';
        });
        break;
      case 'camera_ready':
        setState(() {
          _cameraReady = true;
          _status = 'Kamera siap. Scan wajah sedang berjalan.';
        });
        break;
      case 'instruction':
        setState(() {
          _instruction =
              (payload['message'] as String?)?.trim().isNotEmpty == true
                  ? (payload['message'] as String).trim()
                  : _instruction;
        });
        break;
      case 'status':
        setState(() {
          _status = (payload['message'] as String?)?.trim().isNotEmpty == true
              ? (payload['message'] as String).trim()
              : _status;
        });
        break;
      case 'challenge':
        final key = payload['key'] as String?;
        final state = payload['state'] as String?;
        if (key == null || state == null || !_stepStates.containsKey(key)) {
          return;
        }
        setState(() {
          _stepStates = Map<String, String>.from(_stepStates)..[key] = state;
        });
        break;
      case 'scan_started':
        setState(() {
          _scanActive = true;
          _error = null;
          _status = 'Scan wajah dimulai.';
        });
        break;
      case 'result':
        final result = payload['result'] is Map
            ? Map<String, dynamic>.from(payload['result'] as Map)
            : <String, dynamic>{};
        Navigator.of(context).pop(result);
        break;
      case 'error':
        setState(() {
          _scanActive = false;
          _error = (payload['message'] as String?)?.trim().isNotEmpty == true
              ? (payload['message'] as String).trim()
              : 'Scan wajah belum berhasil.';
          _status = _error!;
        });
        break;
      default:
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        foregroundColor: _scanText,
        elevation: 0,
        titleSpacing: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              widget.title,
              style: theme.textTheme.titleMedium?.copyWith(
                color: _scanText,
                fontWeight: FontWeight.w800,
              ),
            ),
            Text(
              widget.description,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: theme.textTheme.labelSmall?.copyWith(
                color: _scanMuted,
                fontWeight: FontWeight.w700,
              ),
            ),
          ],
        ),
      ),
      body: SafeArea(
        top: false,
        child: Column(
          children: [
            Expanded(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 10, 16, 0),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(28),
                  child: Stack(
                    children: [
                      Positioned.fill(
                        child: ColoredBox(
                          color: Colors.black,
                          child: WebViewWidget(controller: _controller),
                        ),
                      ),
                      if (_pageLoading)
                        const Positioned.fill(
                          child: ColoredBox(
                            color: Colors.black,
                            child: Center(
                              child: CircularProgressIndicator(
                                color: Colors.white,
                              ),
                            ),
                          ),
                        ),
                    ],
                  ),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 18),
              child: Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.06),
                      blurRadius: 18,
                      offset: const Offset(0, 10),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      _instruction,
                      style: theme.textTheme.bodyMedium?.copyWith(
                        color: _scanText,
                        fontWeight: FontWeight.w800,
                        height: 1.35,
                      ),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      _error ?? _status,
                      style: theme.textTheme.bodySmall?.copyWith(
                        color:
                            _error == null ? _scanMuted : Colors.red.shade700,
                        fontWeight: FontWeight.w700,
                        height: 1.35,
                      ),
                    ),
                    const SizedBox(height: 14),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: const [
                        _FaceScanStepChip(
                          label: 'Posisi Wajah',
                          stepKey: 'align',
                        ),
                        _FaceScanStepChip(
                          label: 'Kedip',
                          stepKey: 'blink',
                        ),
                        _FaceScanStepChip(
                          label: 'Challenge',
                          stepKey: 'challenge',
                        ),
                        _FaceScanStepChip(
                          label: 'Selesai',
                          stepKey: 'done',
                        ),
                      ].map((chip) {
                        return _InheritedFaceScanStepState(
                          stateMap: _stepStates,
                          child: chip,
                        );
                      }).toList(),
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Expanded(
                          child: OutlinedButton(
                            onPressed: () => Navigator.of(context).pop(),
                            style: OutlinedButton.styleFrom(
                              foregroundColor: _scanText,
                              side: const BorderSide(color: _scanPrimaryBorder),
                              padding: const EdgeInsets.symmetric(vertical: 14),
                            ),
                            child: const Text('Batal'),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: FilledButton(
                            onPressed: _pageLoading
                                ? null
                                : () async {
                                    await _startScan(resetState: true);
                                  },
                            style: FilledButton.styleFrom(
                              backgroundColor: _scanPrimary,
                              foregroundColor: Colors.white,
                              padding: const EdgeInsets.symmetric(vertical: 14),
                            ),
                            child: Text(
                              _scanActive
                                  ? 'Ulangi Scan'
                                  : (_cameraReady
                                      ? 'Mulai Ulang'
                                      : 'Mulai Scan'),
                              style: const TextStyle(
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _InheritedFaceScanStepState extends InheritedWidget {
  const _InheritedFaceScanStepState({
    required this.stateMap,
    required super.child,
  });

  final Map<String, String> stateMap;

  static Map<String, String> of(BuildContext context) {
    final inherited = context
        .dependOnInheritedWidgetOfExactType<_InheritedFaceScanStepState>();
    return inherited?.stateMap ?? const <String, String>{};
  }

  @override
  bool updateShouldNotify(_InheritedFaceScanStepState oldWidget) {
    return oldWidget.stateMap != stateMap;
  }
}

class _FaceScanStepChip extends StatelessWidget {
  const _FaceScanStepChip({
    required this.label,
    required this.stepKey,
  });

  final String label;
  final String stepKey;

  @override
  Widget build(BuildContext context) {
    final state = _InheritedFaceScanStepState.of(context)[stepKey] ?? 'pending';
    final isDone = state == 'done';
    final isActive = state == 'active';

    return AnimatedContainer(
      duration: const Duration(milliseconds: 180),
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: isDone
            ? _scanPrimarySoft
            : (isActive ? const Color(0xFFE5F5F0) : const Color(0xFFF7F9FC)),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(
          color: isDone
              ? _scanPrimaryBorder
              : (isActive ? const Color(0xFFDCE7E3) : const Color(0xFFDCE7E3)),
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            isDone
                ? Icons.check_circle_rounded
                : (isActive
                    ? Icons.radio_button_checked_rounded
                    : Icons.radio_button_unchecked_rounded),
            size: 14,
            color: isDone
                ? _scanPrimaryDark
                : (isActive ? const Color(0xFF00745A) : _scanMuted),
          ),
          const SizedBox(width: 6),
          Text(
            label,
            style: Theme.of(context).textTheme.labelMedium?.copyWith(
                  color: isDone
                      ? _scanText
                      : (isActive ? const Color(0xFF00745A) : _scanMuted),
                  fontWeight: FontWeight.w800,
                ),
          ),
        ],
      ),
    );
  }
}
