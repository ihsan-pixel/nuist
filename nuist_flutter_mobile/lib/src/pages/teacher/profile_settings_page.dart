import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

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
    _avatarUrl =
        (editable['avatar_url'] as String?) ?? (user['avatar_url'] as String?);
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

  ImageProvider<Object>? _buildAvatarImage() {
    if (_localAvatarFile != null) {
      return FileImage(_localAvatarFile!);
    }

    if (_avatarUrl != null && _avatarUrl!.trim().isNotEmpty) {
      return NetworkImage(_avatarUrl!.trim());
    }

    return null;
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
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: false,
        child: ListView(
          padding: const EdgeInsets.fromLTRB(16, 14, 16, 28),
          children: [
            TeacherPageHeader(
              title: 'Pengaturan Profil',
              onBack: () => Navigator.of(context).pop(),
            ),
            const SizedBox(height: 18),
            AppSectionCard(
              child: Column(
                children: [
                  Stack(
                    children: [
                      CircleAvatar(
                        radius: 44,
                        backgroundColor: const Color(0xFFFFF4E8),
                        backgroundImage: _buildAvatarImage(),
                        child: _buildAvatarImage() == null
                            ? const Icon(
                                Icons.person_rounded,
                                color: Color(0xFFF49637),
                                size: 42,
                              )
                            : null,
                      ),
                      Positioned(
                        right: 0,
                        bottom: 0,
                        child: Material(
                          color: const Color(0xFFF49637),
                          shape: const CircleBorder(),
                          child: InkWell(
                            customBorder: const CircleBorder(),
                            onTap: _uploadingAvatar ? null : _pickAvatar,
                            child: SizedBox(
                              width: 34,
                              height: 34,
                              child: _uploadingAvatar
                                  ? const Padding(
                                      padding: EdgeInsets.all(8),
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        color: Colors.white,
                                      ),
                                    )
                                  : const Icon(
                                      Icons.camera_alt_rounded,
                                      color: Colors.white,
                                      size: 18,
                                    ),
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Foto Profil',
                    style: TextStyle(
                      color: Color(0xFF7A4212),
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Tap ikon kamera untuk mengganti foto profile.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Color(0xFF7C8F8D),
                      fontSize: 12,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            AppSectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Data Tenaga Pendidik',
                    style: TextStyle(
                      color: Color(0xFF7A4212),
                      fontSize: 17,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 14),
                  TextField(
                    controller: _nameController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Nama Lengkap',
                      hintText: _currentName,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: _inputDecoration(
                      'Email',
                      hintText: _currentEmail,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _phoneController,
                    keyboardType: TextInputType.phone,
                    decoration: _inputDecoration(
                      'Nomor HP',
                      hintText: _currentPhone,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _birthPlaceController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Tempat Lahir',
                      hintText: _currentBirthPlace,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _birthDateController,
                    readOnly: true,
                    onTap: _pickBirthDate,
                    decoration: _inputDecoration(
                      'Tanggal Lahir',
                      hintText: _currentBirthDate,
                    ).copyWith(
                        suffixIcon: const Icon(Icons.calendar_today_rounded),
                      ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _tmtController,
                    readOnly: true,
                    onTap: _pickTmtDate,
                    decoration: _inputDecoration(
                      'TMT',
                      hintText: _currentTmt,
                    ).copyWith(
                        suffixIcon: const Icon(Icons.calendar_today_rounded),
                      ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _educationController,
                    textCapitalization: TextCapitalization.words,
                    decoration: _inputDecoration(
                      'Pendidikan Terakhir',
                      hintText: _currentEducation,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _nipController,
                    decoration: _inputDecoration(
                      'NIP',
                      hintText: _currentNip,
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _savingProfile ? null : _saveProfile,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFFF49637),
                        foregroundColor: Colors.white,
                        disabledBackgroundColor: const Color(0xFFF6D2AA),
                        padding: const EdgeInsets.symmetric(vertical: 15),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(18),
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
            const SizedBox(height: 16),
            AppSectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Keamanan Akun',
                    style: TextStyle(
                      color: Color(0xFF7A4212),
                      fontSize: 17,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 10),
                  const Text(
                    'Kelola password akun Anda seperti pada halaman mobile.',
                    style: TextStyle(
                      color: Color(0xFF7C8F8D),
                      fontSize: 12,
                      height: 1.45,
                    ),
                  ),
                  const SizedBox(height: 14),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: () async {
                        await widget.onOpenChangePassword();
                      },
                      style: OutlinedButton.styleFrom(
                        foregroundColor: const Color(0xFFF49637),
                        side: const BorderSide(color: Color(0xFFF0C28B)),
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(18),
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
      color: Color(0xFF97AAA8),
    ),
    filled: true,
    fillColor: const Color(0xFFFFFBF7),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFF0D7BB)),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFF0D7BB)),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFF49637), width: 1.4),
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
