import 'dart:async';

import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

class TeacherSchedulePage extends StatefulWidget {
  const TeacherSchedulePage({
    super.key,
    required this.repository,
    required this.onBackToHome,
    required this.isActive,
    required this.onScheduleChanged,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;
  final bool isActive;
  final VoidCallback onScheduleChanged;

  @override
  State<TeacherSchedulePage> createState() => _TeacherSchedulePageState();
}

class _TeacherSchedulePageState extends State<TeacherSchedulePage>
    with WidgetsBindingObserver {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _future = _loadSchedule();
  }

  @override
  void didUpdateWidget(covariant TeacherSchedulePage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (!oldWidget.isActive && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  Future<Map<String, dynamic>> _loadSchedule() {
    return widget.repository.getSchedule();
  }

  Future<void> _refresh() async {
    final future = _loadSchedule();
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _openScheduleForm({
    Map<String, dynamic>? item,
  }) async {
    try {
      final options = await widget.repository.getScheduleOptions();
      if (!mounted) {
        return;
      }

      final saved = await showModalBottomSheet<bool>(
        context: context,
        isScrollControlled: true,
        useSafeArea: true,
        backgroundColor: Colors.transparent,
        builder: (context) {
          return _ScheduleFormSheet(
            initialItem: item,
            options: options,
            onSubmit: (payload) async {
              if (item == null) {
                await widget.repository.createSchedule(payload: payload);
              } else {
                final scheduleId = (item['id'] as num?)?.toInt();
                if (scheduleId == null) {
                  throw 'ID jadwal tidak ditemukan.';
                }
                await widget.repository.updateSchedule(
                  scheduleId: scheduleId,
                  payload: payload,
                );
              }
            },
          );
        },
      );

      if (saved == true) {
        await _refresh();
        widget.onScheduleChanged();
        if (!mounted) {
          return;
        }
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              item == null
                  ? 'Jadwal berhasil ditambahkan.'
                  : 'Jadwal berhasil diperbarui.',
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
          content: Text(error.toString()),
        ),
      );
    }
  }

  Future<void> _deleteSchedule(Map<String, dynamic> item) async {
    final scheduleId = (item['id'] as num?)?.toInt();
    if (scheduleId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('ID jadwal tidak ditemukan.'),
        ),
      );
      return;
    }

    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) {
        return AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
          ),
          title: const Text(
            'Hapus Jadwal',
            style: TextStyle(
              fontWeight: FontWeight.w800,
            ),
          ),
          content: Text(
            'Hapus jadwal ${(item['subject'] as String?) ?? 'ini'}?',
            style: const TextStyle(
              height: 1.4,
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFB42318),
                foregroundColor: Colors.white,
              ),
              child: const Text('Hapus'),
            ),
          ],
        );
      },
    );

    if (confirmed != true) {
      return;
    }

    try {
      await widget.repository.deleteSchedule(scheduleId: scheduleId);
      await _refresh();
      widget.onScheduleChanged();
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Jadwal berhasil dihapus.'),
        ),
      );
    } catch (error) {
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString()),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        return RefreshIndicator(
          onRefresh: _refresh,
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              if (snapshot.connectionState == ConnectionState.waiting)
                const _PageLoading()
              else if (snapshot.hasError)
                _PageError(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                )
              else
                _ScheduleContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                  onBackToHome: widget.onBackToHome,
                  onCreate: () => _openScheduleForm(),
                  onEdit: (item) => _openScheduleForm(item: item),
                  onDelete: _deleteSchedule,
                ),
            ],
          ),
        );
      },
    );
  }
}

class _ScheduleContent extends StatelessWidget {
  const _ScheduleContent({
    required this.data,
    required this.onBackToHome,
    required this.onCreate,
    required this.onEdit,
    required this.onDelete,
  });

  final Map<String, dynamic> data;
  final VoidCallback onBackToHome;
  final VoidCallback onCreate;
  final ValueChanged<Map<String, dynamic>> onEdit;
  final ValueChanged<Map<String, dynamic>> onDelete;

  @override
  Widget build(BuildContext context) {
    final items = ((data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final canManage = data['can_manage'] == true;

    final grouped = <String, List<Map<String, dynamic>>>{};
    final schools = <String>{};
    for (final item in items) {
      final day = item['day'] as String? ?? 'Lainnya';
      grouped.putIfAbsent(day, () => <Map<String, dynamic>>[]).add(item);
      final schoolName = item['school_name'] as String?;
      if (schoolName != null && schoolName.trim().isNotEmpty) {
        schools.add(schoolName.trim());
      }
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        TeacherPageHeader(
          title: 'Jadwal',
          onBack: onBackToHome,
        ),
        const SizedBox(height: 18),
        Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            const Expanded(
              child: _PageSectionHeading(
                eyebrow: 'Agenda Mengajar',
                title: 'Kelola Jadwal Mengajar',
              ),
            ),
            const SizedBox(width: 12),
            _ScheduleActionButton(
              label: 'Tambah',
              icon: Icons.add_rounded,
              onTap: canManage ? onCreate : null,
            ),
          ],
        ),
        // const SizedBox(height: 12),
        // _ScheduleHeroCard(
        //   totalSchedules: items.length,
        //   totalDays: grouped.length,
        //   totalSchools: schools.length,
        // ),
        if (!canManage) ...[
          const SizedBox(height: 14),
          const AppSectionCard(
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Icon(
                  Icons.info_outline_rounded,
                  color: Color(0xFFF49637),
                ),
                SizedBox(width: 10),
                Expanded(
                  child: Text(
                    'Akun ini belum terhubung ke madrasah, sehingga jadwal belum bisa dikelola.',
                    style: TextStyle(
                      color: Color(0xFF6D7F7D),
                      fontSize: 12,
                      height: 1.4,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
        const SizedBox(height: 18),
        if (items.isEmpty)
          AppSectionCard(
            child: Column(
              children: [
                const AppEmptyState(
                  title: 'Jadwal belum tersedia',
                  message: 'Belum ada jadwal mengajar untuk akun ini.',
                  icon: Icons.calendar_month_outlined,
                ),
                if (canManage) ...[
                  const SizedBox(height: 10),
                  _InlineActionButton(
                    label: 'Tambah Jadwal Baru',
                    onTap: onCreate,
                  ),
                ],
              ],
            ),
          )
        else
          ...grouped.entries.map(
            (entry) => Padding(
              padding: const EdgeInsets.only(bottom: 16),
              child: AppSectionCard(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: 10,
                          height: 10,
                          decoration: const BoxDecoration(
                            color: Color(0xFFF49637),
                            shape: BoxShape.circle,
                          ),
                        ),
                        const SizedBox(width: 10),
                        Text(
                          entry.key,
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: Color(0xFF7A4212),
                          ),
                        ),
                        const Spacer(),
                        Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 6,
                          ),
                          decoration: BoxDecoration(
                            color: const Color(0xFFFFF4E8),
                            borderRadius: BorderRadius.circular(999),
                          ),
                          child: Text(
                            '${entry.value.length} sesi',
                            style: const TextStyle(
                              fontSize: 11,
                              color: Color(0xFFF49637),
                              fontWeight: FontWeight.w800,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    ...entry.value.asMap().entries.map(
                          (itemEntry) => Padding(
                            padding: EdgeInsets.only(
                              bottom: itemEntry.key == entry.value.length - 1
                                  ? 0
                                  : 12,
                            ),
                            child: _ScheduleAgendaTile(
                              item: itemEntry.value,
                              onEdit: canManage
                                  ? () => onEdit(itemEntry.value)
                                  : null,
                              onDelete: canManage
                                  ? () => onDelete(itemEntry.value)
                                  : null,
                            ),
                          ),
                        ),
                  ],
                ),
              ),
            ),
          ),
      ],
    );
  }
}

// class _ScheduleHeroCard extends StatelessWidget {
//   const _ScheduleHeroCard({
//     required this.totalSchedules,
//     required this.totalDays,
//     required this.totalSchools,
//   });
//
//   final int totalSchedules;
//   final int totalDays;
//   final int totalSchools;
//
//   @override
//   Widget build(BuildContext context) {
//     return Container(
//       padding: const EdgeInsets.all(18),
//       decoration: BoxDecoration(
//         borderRadius: BorderRadius.circular(28),
//         gradient: const LinearGradient(
//           colors: [
//             Color(0xFFF49637),
//             Color(0xFFC96A19),
//           ],
//           begin: Alignment.topLeft,
//           end: Alignment.bottomRight,
//         ),
//         boxShadow: const [
//           BoxShadow(
//             color: Color(0x14003B39),
//             blurRadius: 22,
//             offset: Offset(0, 10),
//           ),
//         ],
//       ),
//       child: Column(
//         crossAxisAlignment: CrossAxisAlignment.start,
//         children: [
//           Text(
//             'Jadwal Mengajar',
//             style: TextStyle(
//               color: Colors.white.withOpacity(0.88),
//               fontSize: 13,
//               fontWeight: FontWeight.w700,
//             ),
//           ),
//           const SizedBox(height: 6),
//           const Text(
//             'Kelola Jadwal Anda',
//             style: TextStyle(
//               color: Colors.white,
//               fontSize: 24,
//               fontWeight: FontWeight.w800,
//               height: 1.05,
//             ),
//           ),
//           const SizedBox(height: 16),
//           Row(
//             children: [
//               Expanded(
//                 child: _ScheduleStatPill(
//                   label: 'Total Sesi',
//                   value: '$totalSchedules',
//                 ),
//               ),
//               const SizedBox(width: 10),
//               Expanded(
//                 child: _ScheduleStatPill(
//                   label: 'Hari Aktif',
//                   value: '$totalDays',
//                 ),
//               ),
//               const SizedBox(width: 10),
//               Expanded(
//                 child: _ScheduleStatPill(
//                   label: 'Madrasah',
//                   value: '$totalSchools',
//                 ),
//               ),
//             ],
//           ),
//         ],
//       ),
//     );
//   }
// }

class _ScheduleAgendaTile extends StatelessWidget {
  const _ScheduleAgendaTile({
    required this.item,
    this.onEdit,
    this.onDelete,
  });

  final Map<String, dynamic> item;
  final VoidCallback? onEdit;
  final VoidCallback? onDelete;

  @override
  Widget build(BuildContext context) {
    final schoolName = item['school_name'] as String?;

    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFFCFDFC),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFE2ECE5)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 72,
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 12),
            decoration: BoxDecoration(
              color: const Color(0xFFFFF4E8),
              borderRadius: BorderRadius.circular(18),
            ),
            child: Column(
              children: [
                const Icon(
                  Icons.schedule_rounded,
                  size: 18,
                  color: Color(0xFFF49637),
                ),
                const SizedBox(height: 8),
                Text(
                  item['start_time'] as String? ?? '-',
                  style: const TextStyle(
                    color: Color(0xFFA65612),
                    fontWeight: FontWeight.w800,
                    fontSize: 12,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  item['end_time'] as String? ?? '-',
                  style: const TextStyle(
                    color: Color(0xFFC96A19),
                    fontWeight: FontWeight.w700,
                    fontSize: 11,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Text(
                        item['subject'] as String? ?? '-',
                        style: const TextStyle(
                          color: Color(0xFF7A4212),
                          fontWeight: FontWeight.w800,
                          fontSize: 15,
                        ),
                      ),
                    ),
                    if (onEdit != null || onDelete != null) ...[
                      const SizedBox(width: 8),
                      _ScheduleItemActions(
                        onEdit: onEdit,
                        onDelete: onDelete,
                      ),
                    ],
                  ],
                ),
                const SizedBox(height: 5),
                Row(
                  children: [
                    const Icon(
                      Icons.groups_rounded,
                      size: 15,
                      color: Color(0xFFF49637),
                    ),
                    const SizedBox(width: 6),
                    Expanded(
                      child: Text(
                        item['class_name'] as String? ?? '-',
                        style: const TextStyle(
                          color: Color(0xFF4D6663),
                          fontWeight: FontWeight.w700,
                          fontSize: 12,
                        ),
                      ),
                    ),
                  ],
                ),
                if (schoolName != null && schoolName.trim().isNotEmpty) ...[
                  const SizedBox(height: 5),
                  Row(
                    children: [
                      const Icon(
                        Icons.location_on_outlined,
                        size: 15,
                        color: Color(0xFF7A8F8C),
                      ),
                      const SizedBox(width: 6),
                      Expanded(
                        child: Text(
                          schoolName,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                            color: Color(0xFF7A8F8C),
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _ScheduleItemActions extends StatelessWidget {
  const _ScheduleItemActions({
    this.onEdit,
    this.onDelete,
  });

  final VoidCallback? onEdit;
  final VoidCallback? onDelete;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        if (onEdit != null)
          _ItemActionIcon(
            icon: Icons.edit_outlined,
            color: const Color(0xFFF49637),
            background: const Color(0xFFFFF4E8),
            onTap: onEdit!,
          ),
        if (onEdit != null && onDelete != null) const SizedBox(width: 6),
        if (onDelete != null)
          _ItemActionIcon(
            icon: Icons.delete_outline_rounded,
            color: const Color(0xFFB42318),
            background: const Color(0xFFFFF1EF),
            onTap: onDelete!,
          ),
      ],
    );
  }
}

class _ItemActionIcon extends StatelessWidget {
  const _ItemActionIcon({
    required this.icon,
    required this.color,
    required this.background,
    required this.onTap,
  });

  final IconData icon;
  final Color color;
  final Color background;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: background,
      borderRadius: BorderRadius.circular(12),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: SizedBox(
          width: 34,
          height: 34,
          child: Icon(
            icon,
            color: color,
            size: 18,
          ),
        ),
      ),
    );
  }
}

class _ScheduleActionButton extends StatelessWidget {
  const _ScheduleActionButton({
    required this.label,
    required this.icon,
    this.onTap,
  });

  final String label;
  final IconData icon;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: onTap == null ? const Color(0xFFF4F4F4) : const Color(0xFFF49637),
      borderRadius: BorderRadius.circular(16),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(
                icon,
                size: 18,
                color: onTap == null ? const Color(0xFF9AA4A2) : Colors.white,
              ),
              const SizedBox(width: 6),
              Text(
                label,
                style: TextStyle(
                  color: onTap == null ? const Color(0xFF9AA4A2) : Colors.white,
                  fontSize: 12,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _InlineActionButton extends StatelessWidget {
  const _InlineActionButton({
    required this.label,
    required this.onTap,
  });

  final String label;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return OutlinedButton.icon(
      onPressed: onTap,
      icon: const Icon(
        Icons.add_rounded,
        color: Color(0xFFF49637),
      ),
      label: const Text(
        'Tambah Jadwal Baru',
        style: TextStyle(
          color: Color(0xFFF49637),
          fontWeight: FontWeight.w800,
        ),
      ),
      style: OutlinedButton.styleFrom(
        side: const BorderSide(color: Color(0xFFF49637)),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
      ),
    );
  }
}

class _ScheduleFormSheet extends StatefulWidget {
  const _ScheduleFormSheet({
    required this.options,
    required this.onSubmit,
    this.initialItem,
  });

  final Map<String, dynamic> options;
  final Map<String, dynamic>? initialItem;
  final Future<void> Function(Map<String, dynamic> payload) onSubmit;

  @override
  State<_ScheduleFormSheet> createState() => _ScheduleFormSheetState();
}

class _ScheduleFormSheetState extends State<_ScheduleFormSheet> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _subjectNewController;
  late final TextEditingController _classNewController;
  late final TextEditingController _startTimeController;
  late final TextEditingController _endTimeController;

  late String _day;
  late String _subject;
  late String _className;
  bool _submitting = false;
  String? _errorMessage;

  List<String> get _days => ((widget.options['days'] as List?) ?? const [])
      .whereType<String>()
      .toList();

  List<String> get _subjects =>
      ((widget.options['subjects'] as List?) ?? const [])
          .whereType<String>()
          .toList();

  List<String> get _classes => ((widget.options['classes'] as List?) ?? const [])
      .whereType<String>()
      .toList();

  String get _newValue =>
      (widget.options['new_value'] as String?) ?? '__new__';

  bool get _isEditing => widget.initialItem != null;

  @override
  void initState() {
    super.initState();
    final initial = widget.initialItem ?? const <String, dynamic>{};
    final initialSubject = (initial['subject'] as String?)?.trim();
    final initialClass = (initial['class_name'] as String?)?.trim();

    _day = (initial['day'] as String?)?.trim().isNotEmpty == true
        ? (initial['day'] as String).trim()
        : (_days.isNotEmpty ? _days.first : 'Senin');
    _subject = initialSubject != null && _subjects.contains(initialSubject)
        ? initialSubject
        : _newValue;
    _className = initialClass != null && _classes.contains(initialClass)
        ? initialClass
        : _newValue;

    _subjectNewController = TextEditingController(
      text: _subject == _newValue ? (initialSubject ?? '') : '',
    );
    _classNewController = TextEditingController(
      text: _className == _newValue ? (initialClass ?? '') : '',
    );
    _startTimeController = TextEditingController(
      text: (initial['start_time'] as String?) ?? '',
    );
    _endTimeController = TextEditingController(
      text: (initial['end_time'] as String?) ?? '',
    );
  }

  @override
  void dispose() {
    _subjectNewController.dispose();
    _classNewController.dispose();
    _startTimeController.dispose();
    _endTimeController.dispose();
    super.dispose();
  }

  Future<void> _pickTime(TextEditingController controller) async {
    final initial = _parseTime(controller.text) ?? const TimeOfDay(hour: 7, minute: 0);
    final picked = await showTimePicker(
      context: context,
      initialTime: initial,
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: Color(0xFFF49637),
            ),
          ),
          child: child!,
        );
      },
    );

    if (picked == null) {
      return;
    }

    controller.text = _formatTimeOfDay(picked);
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _submitting = true;
      _errorMessage = null;
    });

    try {
      await widget.onSubmit(<String, dynamic>{
        'day': _day,
        'subject': _subject,
        'subject_new': _subject == _newValue
            ? _subjectNewController.text.trim()
            : null,
        'class_name': _className,
        'class_name_new': _className == _newValue
            ? _classNewController.text.trim()
            : null,
        'start_time': _startTimeController.text.trim(),
        'end_time': _endTimeController.text.trim(),
      });

      if (!mounted) {
        return;
      }
      Navigator.of(context).pop(true);
    } catch (error) {
      setState(() {
        _submitting = false;
        _errorMessage = error.toString();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final bottomInset = MediaQuery.viewInsetsOf(context).bottom;

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(30)),
      ),
      child: Padding(
        padding: EdgeInsets.fromLTRB(18, 18, 18, bottomInset + 18),
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(
                  child: Container(
                    width: 46,
                    height: 5,
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8E8E8),
                      borderRadius: BorderRadius.circular(999),
                    ),
                  ),
                ),
                const SizedBox(height: 18),
                Center(
                  child: Text(
                    _isEditing ? 'Edit Jadwal' : 'Tambah Jadwal',
                    style: const TextStyle(
                      color: Color(0xFF7A4212),
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  '',
                  style: TextStyle(
                    color: Color(0xFF6D7F7D),
                    fontSize: 12,
                  ),
                ),
                if (_errorMessage != null) ...[
                  const SizedBox(height: 14),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF1EF),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFFF6C8C1)),
                    ),
                    child: Text(
                      _errorMessage!,
                      style: const TextStyle(
                        color: Color(0xFFB42318),
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
                const SizedBox(height: 18),
                const _FieldLabel('Hari'),
                const SizedBox(height: 8),
                _FormDropdown<String>(
                  value: _day,
                  items: _days,
                  itemLabel: (item) => item,
                  onChanged: (value) {
                    if (value == null) {
                      return;
                    }
                    setState(() {
                      _day = value;
                    });
                  },
                ),
                const SizedBox(height: 14),
                const _FieldLabel('Mata Pelajaran'),
                const SizedBox(height: 8),
                _FormDropdown<String>(
                  value: _subject,
                  items: [
                    ..._subjects,
                    _newValue,
                  ],
                  itemLabel: (item) =>
                      item == _newValue ? '+ Tambah Mata Pelajaran Baru' : item,
                  onChanged: (value) {
                    if (value == null) {
                      return;
                    }
                    setState(() {
                      _subject = value;
                    });
                  },
                ),
                if (_subject == _newValue) ...[
                  const SizedBox(height: 10),
                  _FormTextField(
                    controller: _subjectNewController,
                    hintText: 'Tulis mata pelajaran baru',
                    validator: (value) {
                      if (_subject == _newValue &&
                          (value == null || value.trim().isEmpty)) {
                        return 'Mata pelajaran baru wajib diisi.';
                      }
                      return null;
                    },
                  ),
                ],
                const SizedBox(height: 14),
                const _FieldLabel('Kelas'),
                const SizedBox(height: 8),
                _FormDropdown<String>(
                  value: _className,
                  items: [
                    ..._classes,
                    _newValue,
                  ],
                  itemLabel: (item) =>
                      item == _newValue ? '+ Tambah Kelas Baru' : item,
                  onChanged: (value) {
                    if (value == null) {
                      return;
                    }
                    setState(() {
                      _className = value;
                    });
                  },
                ),
                if (_className == _newValue) ...[
                  const SizedBox(height: 10),
                  _FormTextField(
                    controller: _classNewController,
                    hintText: 'Tulis nama kelas baru',
                    validator: (value) {
                      if (_className == _newValue &&
                          (value == null || value.trim().isEmpty)) {
                        return 'Kelas baru wajib diisi.';
                      }
                      return null;
                    },
                  ),
                ],
                const SizedBox(height: 14),
                Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const _FieldLabel('Jam Mulai'),
                          const SizedBox(height: 8),
                          _TimeField(
                            controller: _startTimeController,
                            hintText: '07:00',
                            onTap: () => _pickTime(_startTimeController),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const _FieldLabel('Jam Selesai'),
                          const SizedBox(height: 8),
                          _TimeField(
                            controller: _endTimeController,
                            hintText: '08:00',
                            onTap: () => _pickTime(_endTimeController),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 18),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: _submitting
                            ? null
                            : () => Navigator.of(context).pop(false),
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          side: const BorderSide(color: Color(0xFFE6E6E6)),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: const Text(
                          'Batal',
                          style: TextStyle(
                            color: Color(0xFF6D7F7D),
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: _submitting ? null : _submit,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFFF49637),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          elevation: 0,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: _submitting
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                            : Text(
                                _isEditing ? 'Simpan' : 'Tambah',
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
      ),
    );
  }

  TimeOfDay? _parseTime(String value) {
    final parts = value.split(':');
    if (parts.length != 2) {
      return null;
    }

    final hour = int.tryParse(parts[0]);
    final minute = int.tryParse(parts[1]);
    if (hour == null || minute == null) {
      return null;
    }

    return TimeOfDay(hour: hour, minute: minute);
  }

  String _formatTimeOfDay(TimeOfDay value) {
    final hour = value.hour.toString().padLeft(2, '0');
    final minute = value.minute.toString().padLeft(2, '0');
    return '$hour:$minute';
  }
}

class _FieldLabel extends StatelessWidget {
  const _FieldLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        color: Color(0xFF7A4212),
        fontSize: 12,
        fontWeight: FontWeight.w800,
      ),
    );
  }
}

class _FormDropdown<T> extends StatelessWidget {
  const _FormDropdown({
    required this.value,
    required this.items,
    required this.itemLabel,
    required this.onChanged,
  });

  final T value;
  final List<T> items;
  final String Function(T item) itemLabel;
  final ValueChanged<T?> onChanged;

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField<T>(
      value: value,
      icon: const Icon(
        Icons.keyboard_arrow_down_rounded,
        color: Color(0xFF9AA4A2),
      ),
      decoration: InputDecoration(
        filled: true,
        fillColor: const Color(0xFFFCFDFC),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 14,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFF49637)),
        ),
      ),
      items: items
          .map(
            (item) => DropdownMenuItem<T>(
              value: item,
              child: Text(
                itemLabel(item),
                style: const TextStyle(
                  fontSize: 13,
                  color: Color(0xFF3E4A48),
                ),
              ),
            ),
          )
          .toList(),
      onChanged: onChanged,
    );
  }
}

class _FormTextField extends StatelessWidget {
  const _FormTextField({
    required this.controller,
    required this.hintText,
    this.validator,
  });

  final TextEditingController controller;
  final String hintText;
  final String? Function(String?)? validator;

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      validator: validator,
      decoration: InputDecoration(
        hintText: hintText,
        filled: true,
        fillColor: const Color(0xFFFCFDFC),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 14,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFF49637)),
        ),
      ),
    );
  }
}

class _TimeField extends StatelessWidget {
  const _TimeField({
    required this.controller,
    required this.hintText,
    required this.onTap,
  });

  final TextEditingController controller;
  final String hintText;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      readOnly: true,
      onTap: onTap,
      validator: (value) {
        if (value == null || value.trim().isEmpty) {
          return 'Wajib diisi';
        }
        return null;
      },
      decoration: InputDecoration(
        hintText: hintText,
        suffixIcon: const Icon(
          Icons.access_time_rounded,
          color: Color(0xFFF49637),
        ),
        filled: true,
        fillColor: const Color(0xFFFCFDFC),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 14,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFE3ECE6)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFF49637)),
        ),
      ),
    );
  }
}

class _PageLoading extends StatelessWidget {
  const _PageLoading();

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Padding(
        padding: EdgeInsets.only(top: 120),
        child: CircularProgressIndicator(
          color: Color(0xFFF49637),
        ),
      ),
    );
  }
}

class _PageError extends StatelessWidget {
  const _PageError({
    required this.message,
    required this.onRetry,
  });

  final String message;
  final Future<void> Function() onRetry;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        children: [
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF9F1239),
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 12),
          OutlinedButton(
            onPressed: onRetry,
            child: const Text('Coba Lagi'),
          ),
        ],
      ),
    );
  }
}

class _PageSectionHeading extends StatelessWidget {
  const _PageSectionHeading({
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
            color: Color(0xFFF49637),
            fontSize: 11,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: Color(0xFF7A4212),
            fontSize: 17,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}
