import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

import '../../config/app_config.dart';

class TurnstileVerificationPage extends StatefulWidget {
  const TurnstileVerificationPage({super.key});
  @override
  State<TurnstileVerificationPage> createState() => _TurnstileVerificationPageState();
}

class _TurnstileVerificationPageState extends State<TurnstileVerificationPage> {
  late final WebViewController _controller;
  @override
  void initState() {
    super.initState();
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..addJavaScriptChannel('Turnstile', onMessageReceived: (message) => Navigator.of(context).pop(message.message))
      ..loadRequest(Uri.parse('${AppConfig.webBaseUrl}/mobile/student-password-reset/captcha'));
  }
  @override
  Widget build(BuildContext context) => Scaffold(appBar: AppBar(title: const Text('Verifikasi keamanan')), body: WebViewWidget(controller: _controller));
}
