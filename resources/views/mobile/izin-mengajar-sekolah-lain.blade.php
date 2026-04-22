@extends('layouts.mobile')

@section('title', 'Mengajar Sekolah Lain')
@section('subtitle', 'Pengajuan Jadwal Presensi')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .izin-form {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 12px;
        }

        .day-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .day-option {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 9px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #333;
        }

        .day-option input {
            margin: 0;
        }

        .hint {
            color: #6c757d;
            font-size: 11px;
            line-height: 1.45;
            margin-top: 6px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            font-size: 14px;
        }
    </style>

    <form id="form-mengajar-sekolah-lain" action="{{ route('mobile.izin.store') }}" method="POST" enctype="multipart/form-data" class="izin-form">
        @csrf
        <input type="hidden" name="type" value="mengajar_sekolah_lain">

        <div class="form-group">
            <label class="form-label">Sekolah Lain</label>
            <input type="text" class="form-control" value="{{ auth()->user()->madrasahTambahan->name ?? 'Belum diatur' }}" readonly>
        </div>

        <div class="form-group">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
        </div>

        <div class="form-group">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
        </div>

        @php
            $days = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
        @endphp

        <div class="form-group">
            <label class="form-label">Hari Aktif Presensi di Sekolah Utama</label>
            <div class="day-grid">
                @foreach($days as $value => $label)
                    <label class="day-option">
                        <input type="checkbox" name="hari_presensi[]" value="{{ $value }}">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <div class="hint">Pilih hari ketika guru tetap wajib presensi di sekolah utama.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Hari Diizinkan Tidak Presensi Masuk</label>
            <div class="day-grid">
                @foreach($days as $value => $label)
                    <label class="day-option">
                        <input type="checkbox" name="hari_tidak_presensi[]" value="{{ $value }}">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <div class="hint">Pada hari ini, jika tidak presensi, sistem akan mencatat keterangan mengajar di sekolah lain setelah disetujui kepala sekolah.</div>
        </div>

        <div class="form-group">
            <label for="alasan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Tambahkan keterangan jadwal atau dasar pengajuan..."></textarea>
        </div>

        <div class="form-group">
            <label for="file_izin" class="form-label">Upload Surat Pendukung (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="file_izin" name="file_izin" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn-submit">Kirim Pengajuan</button>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        var isSubmitting = false;

        function selectedValues(selector) {
            return Array.from(document.querySelectorAll(selector + ':checked')).map(function(input) {
                return input.value;
            });
        }

        $('#form-mengajar-sekolah-lain').on('submit', function(e) {
            e.preventDefault();

            if (isSubmitting) {
                return;
            }

            var hariPresensi = selectedValues('input[name="hari_presensi[]"]');
            var hariTidakPresensi = selectedValues('input[name="hari_tidak_presensi[]"]');
            var hasOverlap = hariPresensi.some(function(day) {
                return hariTidakPresensi.includes(day);
            });

            if (hariPresensi.length === 0 || hariTidakPresensi.length === 0) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pilih minimal satu hari aktif presensi dan satu hari izin tidak presensi.' });
                return;
            }

            if (hasOverlap) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Hari aktif presensi dan hari izin tidak presensi tidak boleh sama.' });
                return;
            }

            isSubmitting = true;
            var fd = new FormData(this);
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();

            submitBtn.html('<i class="bx bx-loader-alt bx-spin"></i> Mengirim...').prop('disabled', true);

            $.ajax({
                url: '{{ route("mobile.izin.store") }}',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message || 'Pengajuan berhasil dikirim.'
                        }).then(function() {
                            window.location.href = '{{ route("mobile.riwayat-presensi") }}';
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Pengajuan gagal dikirim.' });
                        submitBtn.html(originalText).prop('disabled', false);
                        isSubmitting = false;
                    }
                },
                error: function(xhr) {
                    var msg = 'Pengajuan gagal dikirim.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            });
        });
    })();
</script>
@endsection
