import 'dart:io';

import 'package:flutter/material.dart';
import 'package:open_filex/open_filex.dart';
import 'package:path_provider/path_provider.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

const _manageIzinPrimary = Color(0xFFF49637);
const _manageIzinText = Color(0xFF1F4F4C);
const _manageIzinMuted = Color(0xFF6D7F7D);

class TeacherIzinManagePage extends StatefulWidget {
  const TeacherIzinManagePage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherIzinManagePage> createState() => _TeacherIzinManagePageState();
}

class _TeacherIzinManagePageState extends State<TeacherIzinManagePage> {
  String _status = 'pending';
  late Future<Map<String, dynamic>> _future;
  int? _openingAttachmentId;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getManagedIzin(status: _status);
  }

  Future<void> _refresh() async {
    final future = widget.repository.getManagedIzin(status: _status);
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _changeStatus(String status) async {
    if (_status == status) {
      return;
    }
    final future = widget.repository.getManagedIzin(status: status);
    setState(() {
      _status = status;
      _future = future;
    });
    await future;
  }

  Future<void> _handleDecision({
    required Map<String, dynamic> item,
    required bool approve,
  }) async {
    final notesController = TextEditingController();
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text(approve ? 'Setujui Izin' : 'Tolak Izin'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                '${item['requester_name'] ?? 'Guru'} • ${item['title'] ?? 'Izin'}',
              ),
              const SizedBox(height: 12),
              TextField(
                controller: notesController,
                minLines: 3,
                maxLines: 4,
                decoration: InputDecoration(
                  hintText: approve
                      ? 'Catatan persetujuan (opsional)'
                      : 'Alasan penolakan (opsional)',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(14),
                  ),
                ),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              style: ElevatedButton.styleFrom(
                backgroundColor: approve
                    ? const Color(0xFF2E8B57)
                    : const Color(0xFFB42318),
                foregroundColor: Colors.white,
              ),
              child: Text(approve ? 'Setujui' : 'Tolak'),
            ),
          ],
        );
      },
    );

    if (confirmed != true || !mounted) {
      notesController.dispose();
      return;
    }

    try {
      final izinId = (item['id'] as num).toInt();
      final result = approve
          ? await widget.repository.approveManagedIzin(
              izinId: izinId,
              approvalNotes: notesController.text.trim(),
            )
          : await widget.repository.rejectManagedIzin(
              izinId: izinId,
              approvalNotes: notesController.text.trim(),
            );

      if (!mounted) {
        return;
      }

      await _refresh();

      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ??
                (approve
                    ? 'Izin berhasil disetujui.'
                    : 'Izin berhasil ditolak.'),
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
      notesController.dispose();
    }
  }

  Future<void> _openAttachment(Map<String, dynamic> item) async {
    final fileUrl = (item['file_url'] as String?)?.trim();
    if (fileUrl == null || fileUrl.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Tenaga pendidik belum mengunggah lampiran izin.'),
        ),
      );
      return;
    }

    final izinId = (item['id'] as num?)?.toInt();
    setState(() {
      _openingAttachmentId = izinId;
    });

    try {
      if (_isImageAttachment(fileUrl)) {
        if (!mounted) {
          return;
        }
        await showDialog<void>(
          context: context,
          builder: (context) {
            return Dialog(
              backgroundColor: Colors.black,
              insetPadding: const EdgeInsets.all(16),
              child: Stack(
                children: [
                  InteractiveViewer(
                    minScale: 0.8,
                    maxScale: 4,
                    child: Image.network(
                      fileUrl,
                      fit: BoxFit.contain,
                      errorBuilder: (context, error, stackTrace) {
                        return const SizedBox(
                          height: 280,
                          child: Center(
                            child: Text(
                              'Lampiran tidak dapat ditampilkan.',
                              style: TextStyle(color: Colors.white),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                  Positioned(
                    top: 8,
                    right: 8,
                    child: IconButton(
                      onPressed: () => Navigator.of(context).pop(),
                      icon: const Icon(
                        Icons.close_rounded,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ],
              ),
            );
          },
        );
        return;
      }

      final result = await widget.repository.downloadAttachment(url: fileUrl);
      final bytes = result['bytes'] as List<int>? ?? const <int>[];
      if (bytes.isEmpty) {
        throw Exception('Lampiran kosong atau gagal diunduh.');
      }

      final filename =
          (result['filename'] as String?)?.trim().isNotEmpty == true
              ? (result['filename'] as String).trim()
              : 'lampiran-izin';
      final tempDir = await getTemporaryDirectory();
      final file = File('${tempDir.path}/$filename');
      await file.writeAsBytes(bytes, flush: true);
      final openResult = await OpenFilex.open(file.path);

      if (openResult.type != ResultType.done && mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              openResult.message.isNotEmpty
                  ? openResult.message
                  : 'Lampiran tidak dapat dibuka di perangkat ini.',
            ),
          ),
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
        setState(() {
          _openingAttachmentId = null;
        });
      }
    }
  }

  bool _isImageAttachment(String url) {
    final lower = url.toLowerCase();
    return lower.endsWith('.jpg') ||
        lower.endsWith('.jpeg') ||
        lower.endsWith('.png') ||
        lower.endsWith('.webp');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFAF5),
      appBar: AppBar(
        title: const Text('Kelola Izin'),
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
                  _ManageIzinContent(
                    data: snapshot.data ?? const <String, dynamic>{},
                    selectedStatus: _status,
                    openingAttachmentId: _openingAttachmentId,
                    onStatusChange: _changeStatus,
                    onOpenAttachment: _openAttachment,
                    onApprove: (item) => _handleDecision(
                      item: item,
                      approve: true,
                    ),
                    onReject: (item) => _handleDecision(
                      item: item,
                      approve: false,
                    ),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _ManageIzinContent extends StatelessWidget {
  const _ManageIzinContent({
    required this.data,
    required this.selectedStatus,
    required this.openingAttachmentId,
    required this.onStatusChange,
    required this.onOpenAttachment,
    required this.onApprove,
    required this.onReject,
  });

  final Map<String, dynamic> data;
  final String selectedStatus;
  final int? openingAttachmentId;
  final ValueChanged<String> onStatusChange;
  final Future<void> Function(Map<String, dynamic> item) onOpenAttachment;
  final Future<void> Function(Map<String, dynamic> item) onApprove;
  final Future<void> Function(Map<String, dynamic> item) onReject;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final items = ((data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
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
                'Pengajuan Izin',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: _manageIzinText,
                ),
              ),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  _FilterChipButton(
                    label: 'Pending',
                    selected: selectedStatus == 'pending',
                    onTap: () => onStatusChange('pending'),
                  ),
                  _FilterChipButton(
                    label: 'Disetujui',
                    selected: selectedStatus == 'approved',
                    onTap: () => onStatusChange('approved'),
                  ),
                  _FilterChipButton(
                    label: 'Ditolak',
                    selected: selectedStatus == 'rejected',
                    onTap: () => onStatusChange('rejected'),
                  ),
                  _FilterChipButton(
                    label: 'Semua',
                    selected: selectedStatus == 'all',
                    onTap: () => onStatusChange('all'),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              if (items.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada pengajuan',
                  message: 'Tidak ada data izin pada filter ini.',
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
                        Builder(
                          builder: (context) {
                            final hasAttachment =
                                (item['file_url'] as String?)
                                        ?.trim()
                                        .isNotEmpty ==
                                    true;
                            final itemId = (item['id'] as num?)?.toInt();
                            final openingThisAttachment =
                                openingAttachmentId != null &&
                                openingAttachmentId == itemId;

                            return Container(
                              width: double.infinity,
                              margin: const EdgeInsets.only(bottom: 12),
                              padding: const EdgeInsets.symmetric(
                                horizontal: 12,
                                vertical: 10,
                              ),
                              decoration: BoxDecoration(
                                color: hasAttachment
                                    ? const Color(0xFFFFF4E8)
                                    : const Color(0xFFF5F7F7),
                                borderRadius: BorderRadius.circular(14),
                              ),
                              child: Row(
                                children: [
                                  Icon(
                                    hasAttachment
                                        ? Icons.attach_file_rounded
                                        : Icons.file_present_outlined,
                                    size: 18,
                                    color: hasAttachment
                                        ? _manageIzinPrimary
                                        : _manageIzinMuted,
                                  ),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      hasAttachment
                                          ? 'Lampiran izin sudah diunggah'
                                          : 'Belum ada lampiran izin',
                                      style: TextStyle(
                                        color: hasAttachment
                                            ? _manageIzinText
                                            : _manageIzinMuted,
                                        fontSize: 12,
                                        fontWeight: FontWeight.w700,
                                      ),
                                    ),
                                  ),
                                  if (hasAttachment)
                                    TextButton.icon(
                                      onPressed: openingThisAttachment
                                          ? null
                                          : () => onOpenAttachment(item),
                                      icon: openingThisAttachment
                                          ? const SizedBox(
                                              width: 14,
                                              height: 14,
                                              child: CircularProgressIndicator(
                                                strokeWidth: 2,
                                              ),
                                            )
                                          : const Icon(
                                              Icons.visibility_outlined,
                                              size: 16,
                                            ),
                                      label: Text(
                                        openingThisAttachment
                                            ? 'Membuka...'
                                            : 'Lihat',
                                      ),
                                      style: TextButton.styleFrom(
                                        foregroundColor: _manageIzinPrimary,
                                        padding: EdgeInsets.zero,
                                        visualDensity: VisualDensity.compact,
                                      ),
                                    ),
                                ],
                              ),
                            );
                          },
                        ),
                        Row(
                          children: [
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    item['requester_name'] as String? ??
                                        'Tenaga Pendidik',
                                    style: const TextStyle(
                                      color: _manageIzinText,
                                      fontWeight: FontWeight.w800,
                                    ),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    item['title'] as String? ?? 'Izin',
                                    style: const TextStyle(
                                      color: _manageIzinMuted,
                                      fontSize: 12,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            _ManagedStatusPill(
                              status: item['status'] as String? ?? '-',
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        _ManagedMetaLine(
                          icon: Icons.calendar_today_rounded,
                          text: item['submitted_at_label'] as String? ?? '-',
                        ),
                        if ((item['end_date_label'] as String?)?.isNotEmpty ==
                            true) ...[
                          const SizedBox(height: 6),
                          _ManagedMetaLine(
                            icon: Icons.date_range_rounded,
                            text: 'Sampai ${item['end_date_label']}',
                          ),
                        ],
                        if ((item['reason'] as String?)?.isNotEmpty == true) ...[
                          const SizedBox(height: 10),
                          Text(
                            item['reason'] as String,
                            style: const TextStyle(
                              color: _manageIzinText,
                              height: 1.45,
                            ),
                          ),
                        ],
                        if ((item['approval_notes'] as String?)?.isNotEmpty ==
                            true) ...[
                          const SizedBox(height: 10),
                          Container(
                            width: double.infinity,
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFFFF3E3),
                              borderRadius: BorderRadius.circular(14),
                            ),
                            child: Text(
                              item['approval_notes'] as String,
                              style: const TextStyle(
                                color: _manageIzinText,
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ),
                        ],
                        if ((item['approved_at_label'] as String?)?.isNotEmpty ==
                            true) ...[
                          const SizedBox(height: 8),
                          _ManagedMetaLine(
                            icon: Icons.verified_rounded,
                            text:
                                '${item['approver_name'] ?? '-'} • ${item['approved_at_label']}',
                          ),
                        ],
                        if (item['can_approve'] == true ||
                            item['can_reject'] == true) ...[
                          const SizedBox(height: 14),
                          Row(
                            children: [
                              Expanded(
                                child: OutlinedButton(
                                  onPressed: () => onReject(item),
                                  style: OutlinedButton.styleFrom(
                                    foregroundColor: const Color(0xFFB42318),
                                    side: const BorderSide(
                                      color: Color(0xFFE7B5AF),
                                    ),
                                    padding: const EdgeInsets.symmetric(
                                      vertical: 13,
                                    ),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(16),
                                    ),
                                  ),
                                  child: const Text(
                                    'Tolak',
                                    style:
                                        TextStyle(fontWeight: FontWeight.w800),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: ElevatedButton(
                                  onPressed: () => onApprove(item),
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: const Color(0xFF2E8B57),
                                    foregroundColor: Colors.white,
                                    padding: const EdgeInsets.symmetric(
                                      vertical: 13,
                                    ),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(16),
                                    ),
                                    elevation: 0,
                                  ),
                                  child: const Text(
                                    'Setujui',
                                    style:
                                        TextStyle(fontWeight: FontWeight.w800),
                                  ),
                                ),
                              ),
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

class _FilterChipButton extends StatelessWidget {
  const _FilterChipButton({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final bool selected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return ChoiceChip(
      label: Text(label),
      selected: selected,
      onSelected: (_) => onTap(),
      selectedColor: _manageIzinPrimary.withOpacity(0.18),
      labelStyle: TextStyle(
        color: selected ? _manageIzinPrimary : _manageIzinMuted,
        fontWeight: FontWeight.w700,
      ),
    );
  }
}

class _ManagedStatusPill extends StatelessWidget {
  const _ManagedStatusPill({
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

class _ManagedMetaLine extends StatelessWidget {
  const _ManagedMetaLine({
    required this.icon,
    required this.text,
  });

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(icon, size: 15, color: _manageIzinMuted),
        const SizedBox(width: 6),
        Expanded(
          child: Text(
            text,
            style: const TextStyle(
              color: _manageIzinMuted,
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
      ],
    );
  }
}
