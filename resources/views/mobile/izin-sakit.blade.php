@extends('layouts.mobile')

@section('title', 'Izin Sakit')
@section('subtitle', 'Pengajuan Izin Sakit')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 14px;">Kembali</span>
    </div>
    <style>
        body { background: #f8f9fb; font-family: 'Poppins', sans-serif; font-size: 12px; }
        .izin-form { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-label { font-weight: 600; color: #333; margin-bottom: 5px; display: block; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 8px; font-size: 12px; }
        .btn-submit { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: #fff; border: none; border-radius: 8px; padding: 12px; width: 100%; font-weight: 600; font-size: 14px; }
        .btn-submit:hover { opacity: 0.9; }
    </style>

    <form id="form-izin-sakit" action="{{ route('mobile.izin.store') }}" method="POST" enctype="multipart/form-data" class="izin-form">
        @csrf
        <input type="hidden" name="type" value="sakit">

        <div class="form-group">
            <label for="tanggal" class="form-label">Tanggal Izin</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Jelaskan kondisi kesehatan atau lampirkan keterangan dokter..." required></textarea>
        </div>

        <div class="form-group">
            <label for="surat_izin" class="form-label">Upload Surat / Keterangan Dokter (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="surat_izin" name="surat_izin" accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Jika ada, unggah keterangan dokter. Maks 5MB.</small>
        </div>

        <button type="submit" class="btn-submit">Kirim Izin Sakit</button>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-izin-sakit').on('submit', function(e){
        e.preventDefault();
        var form = this;
        var fd = new FormData(form);

        $.ajax({
            url: '{{ route("mobile.izin.store") }}',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.success){
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Izin sakit berhasil diajukan.' }).then(function(){
                        window.location.href = '{{ route("mobile.riwayat-presensi") }}';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Surat gagal terkirim.' });
                }
            },
            error: function(xhr){
                var msg = 'Surat gagal terkirim.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
            }
        });
    });
</script>
@endsection
