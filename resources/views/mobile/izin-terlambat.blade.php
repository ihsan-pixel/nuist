@extends('layouts.mobile')

@section('title', 'Izin Terlambat')
@section('subtitle', 'Pengajuan Izin Terlambat')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
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

    <form id="form-izin-terlambat" action="{{ route('mobile.izin.store') }}" method="POST" enctype="multipart/form-data" class="izin-form">
        @csrf
        <input type="hidden" name="type" value="terlambat">

        <div class="form-group">
            <label for="tanggal" class="form-label">Tanggal Izin</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="alasan" class="form-label">Alasan Terlambat</label>
            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Jelaskan alasan terlambat..." required></textarea>
        </div>

        <div class="form-group">
            <label for="waktu_masuk" class="form-label">Waktu Masuk</label>
            <input type="time" class="form-control" id="waktu_masuk" name="waktu_masuk" required>
        </div>

        <div class="form-group">
            <label for="file_izin" class="form-label">Upload Surat Izin (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="file_izin" name="file_izin" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>

        <button type="submit" class="btn-submit">Kirim Izin Terlambat</button>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-izin-terlambat').on('submit', function(e){
        e.preventDefault();
        var fd = new FormData(this);

        $.ajax({
            url: '{{ route("mobile.izin.store") }}',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.success){
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Izin terlambat berhasil diajukan.' }).then(function(){
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
