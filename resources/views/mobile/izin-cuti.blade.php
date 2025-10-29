@extends('layouts.mobile')

@section('title', 'Izin Cuti')
@section('subtitle', 'Pengajuan Izin Cuti')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <!-- Back Button -->
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

        .btn-submit:hover {
            opacity: 0.9;
        }
    </style>

    <form id="form-izin-cuti" action="{{ route('mobile.izin.store') }}" method="POST" enctype="multipart/form-data" class="izin-form">
        @csrf
        <input type="hidden" name="type" value="cuti">

        <div class="form-group">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai Cuti</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
        </div>

        <div class="form-group">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai Cuti</label>
            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
        </div>

        <div class="form-group">
            <label for="alasan" class="form-label">Alasan Cuti</label>
            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Jelaskan alasan cuti..." required></textarea>
        </div>

        <div class="form-group">
            <label for="file_izin" class="form-label">Upload Surat Izin (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="file_izin" name="file_izin" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn-submit">Kirim Izin Cuti</button>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-izin-cuti').on('submit', function(e){
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Izin cuti berhasil diajukan.',
                        confirmButtonText: 'Oke'
                    }).then(function(){
                        window.location.href = '{{ route("mobile.riwayat-presensi") }}';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Pengajuan izin cuti gagal.' });
                }
            },
            error: function(xhr){
                var msg = 'Pengajuan izin cuti gagal.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
            }
        });
    });
</script>
@endsection
