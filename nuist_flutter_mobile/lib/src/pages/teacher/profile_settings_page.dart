import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

class _SettingsPalette {
  static const background = Color(0xFFF7F8FC);
  static const surface = Color(0xFFFFFFFF);
  static const primary = Color(0xFF0B8F6E);
  static const textPrimary = Color(0xFF1E293B);
  static const textSecondary = Color(0xFF64748B);
  static const border = Color(0xFFE2E8F0);
  static const iconSurface = Color(0xFFECFDF5);

  const _SettingsPalette._();
}

class TeacherProfileSettingsPage extends StatefulWidget {
  const TeacherProfileSettingsPage({
    super.key,
    required this.repository,
    required this.initialData,
    required this.onOpenChangePassword,
  });

  final TeacherMobileRepository repository;
  final Map<String, dynamic> initialData;
  final Future<void> Function() onOpenChangePassword;

  @override
  State<TeacherProfileSettingsPage> createState() =>
      _TeacherProfileSettingsPageState();
}

class _TeacherProfileSettingsPageState
    extends State<TeacherProfileSettingsPage> {
  final ImagePicker _imagePicker = ImagePicker();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _birthPlaceController = TextEditingController();
  final TextEditingController _birthDateController = TextEditingController();
  final TextEditingController _tmtController = TextEditingController();
  final TextEditingController _educationController = TextEditingController();
  final TextEditingController _nipController = TextEditingController();

  bool _savingProfile = false;
  bool _uploadingAvatar = false;
  String? _avatarUrl;
  File? _localAvatarFile;
  String _currentName = '';
  String _currentEmail = '';
  String _currentPhone = '';
  String _currentBirthPlace = '';
  String _currentBirthDate = '';
  String _currentTmt = '';
  String _currentEducation = '';
  String _currentNip = '';

  @override
  void initState() {
    super.initState();
    _fillFromData(widget.initialData);
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _birthPlaceController.dispose();
    _birthDateController.dispose();
    _tmtController.dispose();
    _educationController.dispose();
    _nipController.dispose();
    super.dispose();
  }

  void _fillFromData(Map<String, dynamic> data) {
    final editable = Map<String, dynamic>.from(
      (data['editable_profile'] as Map?) ?? const <String, dynamic>{},
    );
    final user = Map<String, dynamic>.from(
      (data['user'] as Map?) ?? const <String, dynamic>{},
    );

    _currentName =
        (editable['name'] as String?) ?? (user['name'] as String?) ?? '';
    _currentEmail =
        (editable['email'] as String?) ?? (user['email'] as String?) ?? '';
    _currentPhone =
        (editable['phone'] as String?) ?? (user['phone'] as String?) ?? '';
    _currentBirthPlace = (editable['tempat_lahir'] as String?) ?? '';
    _currentBirthDate = (editable['tanggal_lahir'] as String?) ?? '';
    _currentTmt = (editable['tmt'] as String?) ?? '';
    _currentEducation = (editable['pendidikan_terakhir'] as String?) ?? '';
    _currentNip = (editable['nip'] as String?) ?? '';
    _nameController.clear();
    _emailController.clear();
    _phoneController.clear();
    _birthPlaceController.clear();
    _birthDateController.clear();
    _tmtController.clear();
    _educationController.clear();
    _nipController.clear();
    _localAvatarFile = null;
    final avatarUrl =
        (editable['avatar_url'] as String?) ?? (user['avatar_url'] as String?);
    _avatarUrl = avatarUrl != null && avatarUrl.trim().isNotEmpty
        ? _withAvatarCacheBuster(avatarUrl)
        : null;
  }

  Future<void> _pickAvatar() async {
    final file = await _imagePicker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 80,
      maxWidth: 1600,
    );

    if (file == null || !mounted) {
      return;
    }

    setState(() {
      _localAvatarFile = File(file.path);
      _uploadingAvatar = true;
    });

    try {
      final result = await widget.repository.updateProfileAvatar(
        filePath: file.path,
      );

      if (!mounted) {
        return;
      }

      setState(() {
        final avatarUrl = result['avatar_url'] as String?;
        if (avatarUrl != null && avatarUrl.trim().isNotEmpty) {
          _avatarUrl = _withAvatarCacheBuster(avatarUrl);
        }
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ??
                'Foto profil berhasil diperbarui.',
          ),
        ),
      );
    } catch (error) {
      if (!mounted) {
        return;
      }

      setState(() {
        _localAvatarFile = null;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString().replaceFirst('Exception: ', '')),
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _uploadingAvatar = false;
        });
      }
    }
  }

  Future<void> _pickBirthDate() async {
    final now = DateTime.now();
    final initialDate = _parseDate(
          _birthDateController.text.trim().isNotEmpty
              ? _birthDateController.text.trim()
              : _currentBirthDate,
        ) ??
        DateTime(now.year - 25, now.month, now.day);
    final picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: DateTime(1950),
      lastDate: now,
    );

    if (picked == null) {
      return;
    }

    _birthDateController.text = _formatDate(picked);
  }

  Future<void> _pickTmtDate() async {
    final now = DateTime.now();
    final initialDate = _parseDate(
          _tmtController.text.trim().isNotEmpty
              ? _tmtController.text.trim()
              : _currentTmt,
        ) ??
        DateTime(now.year - 1, now.month, now.day);
    final picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: DateTime(1950),
      lastDate: now,
    );

    if (picked == null) {
      return;
    }

    _tmtController.text = _formatDate(picked);
  }

  Future<void> _saveProfile() async {
    setState(() {
      _savingProfile = true;
    });

    try {
      final result = await widget.repository.updateProfile(
        payload: {
          'name': _resolveRequiredValue(_nameController, _currentName),
          'email': _resolveRequiredValue(_emailController, _currentEmail),
          'phone': _resolveOptionalValue(_phoneController, _currentPhone),
          'tempat_lahir': _resolveOptionalValue(
            _birthPlaceController,
            _currentBirthPlace,
          ),
          'tanggal_lahir': _resolveOptionalValue(
            _birthDateController,
            _currentBirthDate,
          ),
          'tmt': _resolveOptionalValue(_tmtController, _currentTmt),
          'pendidikan_terakhir': _resolveOptionalValue(
            _educationController,
            _currentEducation,
          ),
          'nip': _resolveOptionalValue(_nipController, _currentNip),
        },
      );

      if (!mounted) {
        return;
      }

      final editable = Map<String, dynamic>.from(
        (result['editable_profile'] as Map?) ?? const <String, dynamic>{},
      );
      if (editable.isNotEmpty) {
        setState(() {
          _fillFromData({
            'editable_profile': editable,
            'user': result['user'],
          });
        });
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ?? 'Profil berhasil diperbarui.',
          ),
        ),
      );
    } catch (error) {
      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString().replaceFirst('Exception: ', '')),
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _savingProfile = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final bottomInset = MediaQuery.paddingOf(context).bottom + 24;

    return Scaffold(
      backgroundColor: _SettingsPalette.background,
      body: SafeArea(
        bottom: false,
        child: ListView(
          padding: EdgeInsets.fromLTRB(14, 12, 14, bottomInset),
          children: [
            TeacherPageHeader(
              title: 'Pengaturan Profil',
              onBack: () => Navigator.of(context).pop(),
            ),
            const SizedBox(height: 12),
            AppSectionCard(
              padding: const EdgeInsets.all(14),
              child: Row(
                children: [
                  Stack(
                    children: [
                      _ProfileAvatarPreview(
                        radius: 36,
                        localAvatarFile: _localAvatarFile,
                        avatarUrl: _avatarUrl,
                      ),
                      Positioned(
                        right: 0,
                        bottom: 0,
                        child: Material(
                          color: _SettingsPalette.primary,
                          shape: const CircleBorder(),
                          child: InkWell(
                            customBorder: const CircleBorder(),
                            onTap: _uploadingAvatar ? null : _pickAvatar,
                            child: SizedBox(
                              width: 30,
                              height: 30,
                              child: _uploadingAvatar
                                  ? const Padding(
                                      padding: EdgeInsets.all(7),
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        color: Colors.white,
                                      ),
                                    )
                                  : const Icon(
                                      Icons.camera_alt_rounded,
                                      color: Colors.white,
                                      size: 16,
                                    ),
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Foto Profil',
                          style: TextStyle(
                            color: _SettingsPalette.textPrimary,
                            fontSize: 15,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                        SizedBox(height: 3),
                        Text(
                          'Gunakan foto yang jelas. Ketuk ikon kamera untuk mengganti.',
                          style: TextStyle(
                            color: _SettingsPalette.textSecondary,
                            fontSize: 11.5,
                            height: 1.4,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            AppSectionCard(
              padding: const EdgeInsets.all(14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const _SettingsSectionHeading(
                    eyebrow: 'Profil',
                    title: 'Data Tenaga Pendidik',
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _nameController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Nama Lengkap',
                      hintText: _currentName,
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: _inputDecoration(
                      'Email',
                      hintText: _currentEmail,
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _phoneController,
                    keyboardType: TextInputType.phone,
                    decoration: _inputDecoration(
                      'Nomor HP',
                      hintText: _currentPhone,
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _birthPlaceController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Tempat Lahir',
                      hintText: _currentBirthPlace,
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _birthDateController,
                    readOnly: true,
                    onTap: _pickBirthDate,
                    decoration: _inputDecoration(
                      'Tanggal Lahir',
                      hintText: _currentBirthDate,
                    ).copyWith(
                      suffixIcon: const Icon(
                        Icons.calendar_today_rounded,
                        color: _SettingsPalette.primary,
                        size: 18,
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _tmtController,
                    readOnly: true,
                    onTap: _pickTmtDate,
                    decoration: _inputDecoration(
                      'TMT',
                      hintText: _currentTmt,
                    ).copyWith(
                      suffixIcon: const Icon(
                        Icons.calendar_today_rounded,
                        color: _SettingsPalette.primary,
                        size: 18,
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _educationController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Pendidikan Terakhir',
                      hintText: _currentEducation,
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _nipController,
                    decoration: _inputDecoration(
                      'NIP',
                      hintText: _currentNip,
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _savingProfile ? null : _saveProfile,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _SettingsPalette.primary,
                        foregroundColor: Colors.white,
                        disabledBackgroundColor: _SettingsPalette.border,
                        disabledForegroundColor: _SettingsPalette.textSecondary,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        elevation: 0,
                      ),
                      child: _savingProfile
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Text(
                              'Simpan Perubahan',
                              style: TextStyle(fontWeight: FontWeight.w800),
                            ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            AppSectionCard(
              padding: const EdgeInsets.all(14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const _SettingsSectionHeading(
                    eyebrow: 'Keamanan',
                    title: 'Keamanan Akun',
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Kelola password akun Anda seperti pada halaman mobile.',
                    style: TextStyle(
                      color: _SettingsPalette.textSecondary,
                      fontSize: 11.5,
                      height: 1.45,
                    ),
                  ),
                  const SizedBox(height: 10),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: () async {
                        await widget.onOpenChangePassword();
                      },
                      style: OutlinedButton.styleFrom(
                        foregroundColor: _SettingsPalette.primary,
                        backgroundColor: _SettingsPalette.surface,
                        side: const BorderSide(color: _SettingsPalette.border),
                        padding: const EdgeInsets.symmetric(vertical: 13),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                      ),
                      icon: const Icon(Icons.lock_outline_rounded),
                      label: const Text(
                        'Ubah Password',
                        style: TextStyle(fontWeight: FontWeight.w800),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

String _resolveRequiredValue(
  TextEditingController controller,
  String currentValue,
) {
  final editedValue = controller.text.trim();
  if (editedValue.isNotEmpty) {
    return editedValue;
  }

  return currentValue.trim();
}

String? _resolveOptionalValue(
  TextEditingController controller,
  String currentValue,
) {
  final editedValue = controller.text.trim();
  if (editedValue.isNotEmpty) {
    return editedValue;
  }

  final fallbackValue = currentValue.trim();
  return fallbackValue.isEmpty ? null : fallbackValue;
}

InputDecoration _inputDecoration(
  String label, {
  String? hintText,
}) {
  return InputDecoration(
    labelText: label,
    hintText: (hintText != null && hintText.trim().isNotEmpty)
        ? hintText.trim()
        : null,
    hintStyle: const TextStyle(
      color: _SettingsPalette.textSecondary,
      fontSize: 14,
    ),
    labelStyle: const TextStyle(
      color: _SettingsPalette.textSecondary,
      fontSize: 13,
    ),
    filled: true,
    fillColor: _SettingsPalette.surface,
    isDense: true,
    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _SettingsPalette.border),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _SettingsPalette.border),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _SettingsPalette.primary, width: 1.4),
    ),
  );
}

DateTime? _parseDate(String value) {
  try {
    final parts = value.split('-');
    if (parts.length != 3) {
      return null;
    }
    return DateTime(
      int.parse(parts[0]),
      int.parse(parts[1]),
      int.parse(parts[2]),
    );
  } catch (_) {
    return null;
  }
}

String _formatDate(DateTime value) {
  final year = value.year.toString().padLeft(4, '0');
  final month = value.month.toString().padLeft(2, '0');
  final day = value.day.toString().padLeft(2, '0');
  return '$year-$month-$day';
}

String _withAvatarCacheBuster(String url) {
  final separator = url.contains('?') ? '&' : '?';
  return '$url${separator}t=${DateTime.now().millisecondsSinceEpoch}';
}

class _ProfileAvatarPreview extends StatelessWidget {
  const _ProfileAvatarPreview({
    required this.radius,
    required this.localAvatarFile,
    required this.avatarUrl,
  });

  final double radius;
  final File? localAvatarFile;
  final String? avatarUrl;

  @override
  Widget build(BuildContext context) {
    final size = radius * 2;
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        color: _SettingsPalette.iconSurface,
        shape: BoxShape.circle,
        border: Border.all(color: _SettingsPalette.border),
      ),
      clipBehavior: Clip.antiAlias,
      child: localAvatarFile != null
          ? Image.file(
              localAvatarFile!,
              fit: BoxFit.cover,
              errorBuilder: (_, __, ___) => const _AvatarFallbackIcon(),
            )
          : (avatarUrl != null && avatarUrl!.trim().isNotEmpty)
              ? Image.network(
                  avatarUrl!.trim(),
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => const _AvatarFallbackIcon(),
                )
              : const _AvatarFallbackIcon(),
    );
  }
}

class _AvatarFallbackIcon extends StatelessWidget {
  const _AvatarFallbackIcon();

  @override
  Widget build(BuildContext context) {
    return const Icon(
      Icons.person_rounded,
      color: _SettingsPalette.primary,
      size: 36,
    );
  }
}

class _SettingsSectionHeading extends StatelessWidget {
  const _SettingsSectionHeading({
    required this.eyebrow,
    required this.title,
  });

  final String eyebrow;
  final String title;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          eyebrow.toUpperCase(),
          style: const TextStyle(
            color: _SettingsPalette.textSecondary,
            fontSize: 10.5,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.4,
          ),
        ),
        const SizedBox(height: 3),
        Text(
          title,
          style: const TextStyle(
            color: _SettingsPalette.textPrimary,
            fontSize: 15,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}
