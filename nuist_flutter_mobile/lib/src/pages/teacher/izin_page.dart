import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

const _izinPrimary = Color(0xFFF49637);
const _izinPrimaryDark = Color(0xFFC96A19);
const _izinText = Color(0xFF1F4F4C);
const _izinMuted = Color(0xFF6D7F7D);
const _izinSoft = Color(0xFFFFF7EF);

class TeacherIzinPage extends StatefulWidget {
  const TeacherIzinPage({
    super.key,
    required this.repository,
    this.onOpenManageIzin,
  });

  final TeacherMobileRepository repository;
  final Future<void> Function()? onOpenManageIzin;

  @override
  State<TeacherIzinPage> createState() => _TeacherIzinPageState();
}

class _TeacherIzinPageState extends State<TeacherIzinPage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getIzin();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getIzin();
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _openIzinForm({
    required Map<String, dynamic> action,
    required Map<String, dynamic> formMeta,
  }) async {
    final type = action['type'] as String? ?? '';
    if (type.isEmpty) {
      return;
    }

    final title = action['title'] as String? ?? 'Pengajuan Izin';
    final dateController = TextEditingController();
    final endDateController = TextEditingController();
    final reasonController = TextEditingController();
    final noteController = TextEditingController();
    final descriptionController = TextEditingController();
    final locationController = TextEditingController();
    final startTimeController = TextEditingController();
    final endTimeController = TextEditingController();
    PlatformFile? attachment;
    var isSubmitting = false;
    final selectedPresenceDays = <int>{};
    final selectedNoPresenceDays = <int>{};
    final dayOptions = ((formMeta['day_options'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    Future<void> pickDate(
      BuildContext context,
      TextEditingController controller,
      StateSetter sheetSetState,
    ) async {
      final now = DateTime.now();
      final initialDate = _parseDate(controller.text) ?? now;
      final picked = await showDatePicker(
        context: context,
        initialDate: initialDate,
        firstDate: DateTime(now.year - 1),
        lastDate: DateTime(now.year + 3),
      );
      if (picked == null) {
        return;
      }
      sheetSetState(() {
        controller.text = _formatDate(picked);
      });
    }

    Future<void> pickTime(
      BuildContext context,
      TextEditingController controller,
      StateSetter sheetSetState,
    ) async {
      final picked = await showTimePicker(
        context: context,
        initialTime: _parseTime(controller.text) ?? TimeOfDay.now(),
      );
      if (picked == null) {
        return;
      }
      sheetSetState(() {
        controller.text = _formatTimeOfDay(picked);
      });
    }

    Future<void> pickAttachment(StateSetter sheetSetState) async {
      final result = await FilePicker.platform.pickFiles(
        type: FileType.custom,
        allowedExtensions: const ['pdf', 'jpg', 'jpeg', 'png'],
        withData: false,
      );
      final file = result?.files.single;
      if (file == null) {
        return;
      }
      sheetSetState(() {
        attachment = file;
      });
    }

    Future<void> submit(StateSetter sheetSetState) async {
      String? attachmentField;
      final payload = <String, dynamic>{};

      switch (type) {
        case 'tidak_masuk':
          payload['tanggal'] = dateController.text.trim();
          payload['alasan'] = reasonController.text.trim();
          attachmentField = 'file_izin';
          break;
        case 'sakit':
          payload['tanggal'] = dateController.text.trim();
          payload['keterangan'] = noteController.text.trim();
          attachmentField = 'surat_izin';
          break;
        case 'terlambat':
          payload['tanggal'] = dateController.text.trim();
          payload['alasan'] = reasonController.text.trim();
          payload['waktu_masuk'] = startTimeController.text.trim();
          attachmentField = 'file_izin';
          break;
        case 'tugas_luar':
          payload['tanggal'] = dateController.text.trim();
          payload['deskripsi_tugas'] = descriptionController.text.trim();
          payload['lokasi_tugas'] = locationController.text.trim();
          payload['waktu_masuk'] = startTimeController.text.trim();
          payload['waktu_keluar'] = endTimeController.text.trim();
          attachmentField = 'file_tugas';
          break;
        case 'cuti':
          payload['tanggal_mulai'] = dateController.text.trim();
          payload['tanggal_selesai'] = endDateController.text.trim();
          payload['alasan'] = reasonController.text.trim();
          attachmentField = 'file_izin';
          break;
        case 'mengajar_sekolah_lain':
          payload['tanggal_mulai'] = dateController.text.trim();
          payload['tanggal_selesai'] = endDateController.text.trim();
          payload['alasan'] = noteController.text.trim();
          payload['hari_presensi'] =
              selectedPresenceDays.toList()..sort((a, b) => a.compareTo(b));
          payload['hari_tidak_presensi'] =
              selectedNoPresenceDays.toList()..sort((a, b) => a.compareTo(b));
          attachmentField = 'file_izin';
          break;
      }

      sheetSetState(() {
        isSubmitting = true;
      });

      try {
        final result = await widget.repository.submitIzin(
          type: type,
          payload: payload,
          attachmentField: attachmentField,
          attachmentPath: attachment?.path,
        );

        if (!mounted) {
          return;
        }

        Navigator.of(context).pop();
        await _refresh();

        if (!mounted) {
          return;
        }

        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              (result['_message'] as String?) ??
                  'Izin berhasil diajukan dan menunggu persetujuan.',
            ),
          ),
        );

        if (type == 'terlambat') {
          await showDialog<void>(
            context: context,
            builder: (context) {
              return AlertDialog(
                title: const Text('Penting'),
                content: const Text(
                  'Jika izin terlambat disetujui, segera lakukan presensi setelah sampai di sekolah agar tidak tercatat alpha.',
                ),
                actions: [
                  TextButton(
                    onPressed: () => Navigator.of(context).pop(),
                    child: const Text('Mengerti'),
                  ),
                ],
              );
            },
          );
        }
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
          sheetSetState(() {
            isSubmitting = false;
          });
        }
      }
    }

    await showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (sheetContext) {
        return StatefulBuilder(
          builder: (context, sheetSetState) {
            final isExternalTeaching = type == 'mengajar_sekolah_lain';
            final needsDateRange = type == 'cuti' || isExternalTeaching;
            final needsFile = type != 'cuti' || attachment != null;

            return AnimatedPadding(
              duration: const Duration(milliseconds: 180),
              curve: Curves.easeOut,
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
              ),
              child: Container(
                decoration: const BoxDecoration(
                  color: Color(0xFFFFFCF8),
                  borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
                ),
                child: SafeArea(
                  top: false,
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Center(
                          child: Container(
                            width: 46,
                            height: 4,
                            decoration: BoxDecoration(
                              color: const Color(0xFFE5D4C3),
                              borderRadius: BorderRadius.circular(999),
                            ),
                          ),
                        ),
                        const SizedBox(height: 16),
                        Text(
                          title,
                          style: const TextStyle(
                            color: _izinText,
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          action['subtitle'] as String? ??
                              'Lengkapi form pengajuan izin sesuai kebutuhan.',
                          style: const TextStyle(
                            color: _izinMuted,
                            fontSize: 12,
                            height: 1.45,
                          ),
                        ),
                        const SizedBox(height: 16),
                        if (isExternalTeaching) ...[
                          _LabeledField(
                            label: 'Sekolah Lain',
                            child: TextField(
                              readOnly: true,
                              controller: TextEditingController(
                                text:
                                    (formMeta['external_school_name'] as String?)
                                            ?.trim()
                                            .isNotEmpty ==
                                        true
                                        ? formMeta['external_school_name']
                                            as String
                                        : 'Belum diatur',
                              ),
                              decoration: _izinInputDecoration('Sekolah Lain'),
                            ),
                          ),
                          const SizedBox(height: 12),
                        ],
                        _LabeledField(
                          label: needsDateRange
                              ? 'Tanggal Mulai'
                              : 'Tanggal Izin',
                          child: TextField(
                            controller: dateController,
                            readOnly: true,
                            onTap: () => pickDate(
                              context,
                              dateController,
                              sheetSetState,
                            ),
                            decoration:
                                _izinInputDecoration(
                                  needsDateRange
                                      ? 'Tanggal Mulai'
                                      : 'Tanggal Izin',
                                ).copyWith(
                                  suffixIcon: const Icon(
                                    Icons.calendar_today_rounded,
                                  ),
                                ),
                          ),
                        ),
                        if (needsDateRange) ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Tanggal Selesai',
                            child: TextField(
                              controller: endDateController,
                              readOnly: true,
                              onTap: () => pickDate(
                                context,
                                endDateController,
                                sheetSetState,
                              ),
                              decoration:
                                  _izinInputDecoration(
                                    'Tanggal Selesai',
                                  ).copyWith(
                                    suffixIcon: const Icon(
                                      Icons.calendar_today_rounded,
                                    ),
                                  ),
                            ),
                          ),
                        ],
                        if (type == 'tidak_masuk' ||
                            type == 'terlambat' ||
                            type == 'cuti') ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: type == 'tidak_masuk'
                                ? 'Alasan Tidak Masuk'
                                : type == 'terlambat'
                                    ? 'Alasan Terlambat'
                                    : 'Alasan Cuti',
                            child: TextField(
                              controller: reasonController,
                              minLines: 3,
                              maxLines: 4,
                              decoration: _izinInputDecoration('Tulis alasan'),
                            ),
                          ),
                        ],
                        if (type == 'sakit') ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Keterangan',
                            child: TextField(
                              controller: noteController,
                              minLines: 3,
                              maxLines: 4,
                              decoration: _izinInputDecoration(
                                'Jelaskan kondisi kesehatan',
                              ),
                            ),
                          ),
                        ],
                        if (type == 'tugas_luar') ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Deskripsi Tugas',
                            child: TextField(
                              controller: descriptionController,
                              minLines: 3,
                              maxLines: 4,
                              decoration: _izinInputDecoration(
                                'Jelaskan tugas yang akan dilakukan',
                              ),
                            ),
                          ),
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Lokasi Tugas',
                            child: TextField(
                              controller: locationController,
                              decoration: _izinInputDecoration(
                                'Masukkan lokasi tugas',
                              ),
                            ),
                          ),
                        ],
                        if (type == 'terlambat' || type == 'tugas_luar') ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: type == 'terlambat'
                                ? 'Waktu Masuk'
                                : 'Waktu Mulai',
                            child: TextField(
                              controller: startTimeController,
                              readOnly: true,
                              onTap: () => pickTime(
                                context,
                                startTimeController,
                                sheetSetState,
                              ),
                              decoration:
                                  _izinInputDecoration(
                                    type == 'terlambat'
                                        ? 'Waktu Masuk'
                                        : 'Waktu Mulai',
                                  ).copyWith(
                                    suffixIcon: const Icon(
                                      Icons.schedule_rounded,
                                    ),
                                  ),
                            ),
                          ),
                        ],
                        if (type == 'tugas_luar') ...[
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Waktu Selesai',
                            child: TextField(
                              controller: endTimeController,
                              readOnly: true,
                              onTap: () => pickTime(
                                context,
                                endTimeController,
                                sheetSetState,
                              ),
                              decoration:
                                  _izinInputDecoration(
                                    'Waktu Selesai',
                                  ).copyWith(
                                    suffixIcon: const Icon(
                                      Icons.schedule_rounded,
                                    ),
                                  ),
                            ),
                          ),
                        ],
                        if (isExternalTeaching) ...[
                          const SizedBox(height: 12),
                          const Text(
                            'Hari Aktif Presensi di Sekolah Utama',
                            style: TextStyle(
                              color: _izinText,
                              fontSize: 13,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                          const SizedBox(height: 10),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: dayOptions.map((day) {
                              final value = (day['value'] as num?)?.toInt() ?? 0;
                              final selected =
                                  selectedPresenceDays.contains(value);
                              return FilterChip(
                                selected: selected,
                                onSelected: (checked) {
                                  sheetSetState(() {
                                    if (checked) {
                                      selectedPresenceDays.add(value);
                                    } else {
                                      selectedPresenceDays.remove(value);
                                    }
                                  });
                                },
                                selectedColor: _izinPrimary.withOpacity(0.18),
                                checkmarkColor: _izinPrimaryDark,
                                label: Text(day['label'] as String? ?? '-'),
                              );
                            }).toList(),
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'Hari Diizinkan Tidak Presensi Masuk',
                            style: TextStyle(
                              color: _izinText,
                              fontSize: 13,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                          const SizedBox(height: 10),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: dayOptions.map((day) {
                              final value = (day['value'] as num?)?.toInt() ?? 0;
                              final selected =
                                  selectedNoPresenceDays.contains(value);
                              return FilterChip(
                                selected: selected,
                                onSelected: (checked) {
                                  sheetSetState(() {
                                    if (checked) {
                                      selectedNoPresenceDays.add(value);
                                    } else {
                                      selectedNoPresenceDays.remove(value);
                                    }
                                  });
                                },
                                selectedColor: const Color(0xFFFFE1D2),
                                checkmarkColor: const Color(0xFFD45B19),
                                label: Text(day['label'] as String? ?? '-'),
                              );
                            }).toList(),
                          ),
                          const SizedBox(height: 12),
                          _LabeledField(
                            label: 'Keterangan',
                            child: TextField(
                              controller: noteController,
                              minLines: 3,
                              maxLines: 4,
                              decoration: _izinInputDecoration(
                                'Tambahkan keterangan jadwal atau dasar pengajuan',
                              ),
                            ),
                          ),
                        ],
                        const SizedBox(height: 12),
                        _LabeledField(
                          label: 'Upload Lampiran',
                          child: InkWell(
                            borderRadius: BorderRadius.circular(18),
                            onTap: isSubmitting
                                ? null
                                : () => pickAttachment(sheetSetState),
                            child: Ink(
                              padding: const EdgeInsets.all(14),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(18),
                                border: Border.all(
                                  color: const Color(0xFFE8D6C6),
                                ),
                              ),
                              child: Row(
                                children: [
                                  const Icon(
                                    Icons.attach_file_rounded,
                                    color: _izinPrimary,
                                  ),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Text(
                                      attachment?.name ??
                                          'PDF/JPG/PNG maksimal 5MB',
                                      style: TextStyle(
                                        color: attachment == null
                                            ? _izinMuted
                                            : _izinText,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(height: 18),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: isSubmitting ? null : () => submit(sheetSetState),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: _izinPrimary,
                              foregroundColor: Colors.white,
                              disabledBackgroundColor: const Color(0xFFF6D2AA),
                              padding: const EdgeInsets.symmetric(vertical: 15),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(18),
                              ),
                              elevation: 0,
                            ),
                            child: isSubmitting
                                ? const SizedBox(
                                    width: 18,
                                    height: 18,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                      color: Colors.white,
                                    ),
                                  )
                                : const Text(
                                    'Kirim Pengajuan',
                                    style: TextStyle(fontWeight: FontWeight.w800),
                                  ),
                          ),
                        ),
                        if (!needsFile) const SizedBox.shrink(),
                      ],
                    ),
                  ),
                ),
              ),
            );
          },
        );
      },
    );

    dateController.dispose();
    endDateController.dispose();
    reasonController.dispose();
    noteController.dispose();
    descriptionController.dispose();
    locationController.dispose();
    startTimeController.dispose();
    endTimeController.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFAF5),
      appBar: AppBar(
        title: const Text('Izin'),
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
      ),
      body: FutureBuilder<Map<String, dynamic>>(
        future: _future,
        builder: (context, snapshot) {
          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (snapshot.connectionState == ConnectionState.waiting)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.only(top: 120),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (snapshot.hasError)
                  AppSectionCard(
                    child: Text(
                      snapshot.error.toString(),
                      style: const TextStyle(
                        color: Color(0xFF9F1239),
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  )
                else
                  _IzinContent(
                    data: snapshot.data ?? const <String, dynamic>{},
                    onCreate: ({required action, required formMeta}) {
                      return _openIzinForm(
                        action: action,
                        formMeta: formMeta,
                      );
                    },
                    onOpenManageIzin: widget.onOpenManageIzin,
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _IzinContent extends StatelessWidget {
  const _IzinContent({
    required this.data,
    required this.onCreate,
    this.onOpenManageIzin,
  });

  final Map<String, dynamic> data;
  final Future<void> Function({
    required Map<String, dynamic> action,
    required Map<String, dynamic> formMeta,
  }) onCreate;
  final Future<void> Function()? onOpenManageIzin;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final permissions = Map<String, dynamic>.from(
      (data['permissions'] as Map?) ?? const <String, dynamic>{},
    );
    final formMeta = Map<String, dynamic>.from(
      (data['form_meta'] as Map?) ?? const <String, dynamic>{},
    );
    final menu = ((data['menu'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final items = ((data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Ajukan Izin',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: _izinText,
                ),
              ),
              const SizedBox(height: 6),
              const Text(
                'Pilih jenis izin yang ingin diajukan sesuai kebutuhan Anda.',
                style: TextStyle(
                  color: _izinMuted,
                  fontSize: 12,
                ),
              ),
              const SizedBox(height: 14),
              if (permissions['can_manage_izin'] == true &&
                  onOpenManageIzin != null) ...[
                SizedBox(
                  width: double.infinity,
                  child: OutlinedButton.icon(
                    onPressed: () async {
                      await onOpenManageIzin!();
                    },
                    style: OutlinedButton.styleFrom(
                      foregroundColor: _izinPrimaryDark,
                      side: const BorderSide(color: Color(0xFFF0C28B)),
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(18),
                      ),
                    ),
                    icon: const Icon(Icons.approval_rounded),
                    label: const Text(
                      'Kelola Izin',
                      style: TextStyle(fontWeight: FontWeight.w800),
                    ),
                  ),
                ),
                const SizedBox(height: 14),
              ],
              if (menu.isEmpty)
                const AppEmptyState(
                  title: 'Menu izin belum tersedia',
                  message: 'Silakan coba beberapa saat lagi.',
                  icon: Icons.assignment_late_outlined,
                )
              else
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: menu.length,
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 12,
                    mainAxisSpacing: 12,
                    childAspectRatio: 0.98,
                  ),
                  itemBuilder: (context, index) {
                    final action = menu[index];
                    return InkWell(
                      borderRadius: BorderRadius.circular(20),
                      onTap: () => onCreate(
                        action: action,
                        formMeta: formMeta,
                      ),
                      child: Ink(
                        decoration: BoxDecoration(
                          color: _izinSoft,
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: const Color(0xFFF4DFC8)),
                        ),
                        padding: const EdgeInsets.all(14),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Container(
                              width: 42,
                              height: 42,
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(14),
                              ),
                              child: Icon(
                                _izinIcon(action['icon'] as String?),
                                color: _izinPrimaryDark,
                              ),
                            ),
                            const SizedBox(height: 12),
                            Text(
                              action['title'] as String? ?? '-',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(
                                color: _izinText,
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                            const SizedBox(height: 6),
                            Expanded(
                              child: Text(
                                action['subtitle'] as String? ?? '',
                                maxLines: 3,
                                overflow: TextOverflow.ellipsis,
                                style: const TextStyle(
                                  color: _izinMuted,
                                  fontSize: 12,
                                  height: 1.35,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        Row(
          children: [
            Expanded(
              child: AppStatCard(
                label: 'Pending',
                value: '${summary['pending'] ?? 0}',
                color: const Color(0xFFF4A12A),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Disetujui',
                value: '${summary['approved'] ?? 0}',
                color: const Color(0xFF2E8B57),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Ditolak',
                value: '${summary['rejected'] ?? 0}',
                color: const Color(0xFFB42318),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Riwayat Izin',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: _izinText,
                ),
              ),
              const SizedBox(height: 12),
              if (items.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada pengajuan izin',
                  message: 'Riwayat izin Anda akan tampil di sini.',
                  icon: Icons.assignment_outlined,
                )
              else
                ...items.map(
                  (item) => Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFFCF8),
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(color: const Color(0xFFEEDFD0)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Expanded(
                              child: Text(
                                item['title'] as String? ?? '-',
                                style: const TextStyle(
                                  fontWeight: FontWeight.w800,
                                  color: _izinText,
                                ),
                              ),
                            ),
                            _IzinStatusPill(
                              status: item['status'] as String? ?? '-',
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        Text(
                          item['submitted_at_label'] as String? ?? '-',
                          style: const TextStyle(
                            color: _izinMuted,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        if ((item['end_date_label'] as String?) != null) ...[
                          const SizedBox(height: 4),
                          Text(
                            'Sampai ${item['end_date_label']}',
                            style: const TextStyle(
                              color: Color(0xFF8BA3A1),
                              fontSize: 12,
                            ),
                          ),
                        ],
                        if ((item['reason'] as String?)?.isNotEmpty == true) ...[
                          const SizedBox(height: 8),
                          Text(
                            item['reason'] as String,
                            style: const TextStyle(color: _izinText),
                          ),
                        ],
                        if ((item['location'] as String?)?.isNotEmpty == true) ...[
                          const SizedBox(height: 8),
                          _MetaLine(
                            icon: Icons.location_on_outlined,
                            text: item['location'] as String,
                          ),
                        ],
                        if ((item['start_time'] as String?)?.isNotEmpty ==
                                true ||
                            (item['end_time'] as String?)?.isNotEmpty ==
                                true) ...[
                          const SizedBox(height: 6),
                          _MetaLine(
                            icon: Icons.schedule_rounded,
                            text:
                                '${item['start_time'] ?? '-'}${(item['end_time'] as String?)?.isNotEmpty == true ? ' - ${item['end_time']}' : ''}',
                          ),
                        ],
                        if (((item['day_presence_labels'] as List?) ?? const [])
                                .isNotEmpty ||
                            ((item['day_no_presence_labels'] as List?) ?? const [])
                                .isNotEmpty) ...[
                          const SizedBox(height: 8),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: [
                              ...(((item['day_presence_labels'] as List?) ??
                                      const [])
                                  .whereType<String>()
                                  .map(
                                    (label) => _SmallChip(
                                      label: 'Presensi $label',
                                      color: const Color(0xFFFFEAD5),
                                      textColor: _izinPrimaryDark,
                                    ),
                                  )),
                              ...(((item['day_no_presence_labels'] as List?) ??
                                      const [])
                                  .whereType<String>()
                                  .map(
                                    (label) => _SmallChip(
                                      label: 'Tidak Presensi $label',
                                      color: const Color(0xFFEAF7F2),
                                      textColor: const Color(0xFF2E8B57),
                                    ),
                                  )),
                            ],
                          ),
                        ],
                      ],
                    ),
                  ),
                ),
            ],
          ),
        ),
      ],
    );
  }
}

class _IzinStatusPill extends StatelessWidget {
  const _IzinStatusPill({
    required this.status,
  });

  final String status;

  @override
  Widget build(BuildContext context) {
    final color = status == 'approved'
        ? const Color(0xFF2E8B57)
        : status == 'rejected'
            ? const Color(0xFFB42318)
            : const Color(0xFFF4A12A);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        status,
        style: TextStyle(
          color: color,
          fontSize: 12,
          fontWeight: FontWeight.w800,
        ),
      ),
    );
  }
}

class _LabeledField extends StatelessWidget {
  const _LabeledField({
    required this.label,
    required this.child,
  });

  final String label;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            color: _izinText,
            fontSize: 13,
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 8),
        child,
      ],
    );
  }
}

class _MetaLine extends StatelessWidget {
  const _MetaLine({
    required this.icon,
    required this.text,
  });

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(icon, size: 16, color: _izinMuted),
        const SizedBox(width: 6),
        Expanded(
          child: Text(
            text,
            style: const TextStyle(
              color: _izinMuted,
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
      ],
    );
  }
}

class _SmallChip extends StatelessWidget {
  const _SmallChip({
    required this.label,
    required this.color,
    required this.textColor,
  });

  final String label;
  final Color color;
  final Color textColor;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: textColor,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

InputDecoration _izinInputDecoration(String hint) {
  return InputDecoration(
    hintText: hint,
    filled: true,
    fillColor: Colors.white,
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFE8D6C6)),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFE8D6C6)),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: _izinPrimary, width: 1.4),
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

TimeOfDay? _parseTime(String value) {
  try {
    final parts = value.split(':');
    if (parts.length < 2) {
      return null;
    }
    return TimeOfDay(
      hour: int.parse(parts[0]),
      minute: int.parse(parts[1]),
    );
  } catch (_) {
    return null;
  }
}

String _formatTimeOfDay(TimeOfDay value) {
  final hour = value.hour.toString().padLeft(2, '0');
  final minute = value.minute.toString().padLeft(2, '0');
  return '$hour:$minute';
}

IconData _izinIcon(String? icon) {
  switch (icon) {
    case 'person_off':
      return Icons.person_off_rounded;
    case 'medical_information':
      return Icons.local_hospital_rounded;
    case 'schedule':
      return Icons.schedule_rounded;
    case 'work_history':
      return Icons.work_history_rounded;
    case 'event_available':
      return Icons.event_available_rounded;
    case 'domain':
      return Icons.domain_rounded;
    default:
      return Icons.assignment_outlined;
  }
}
