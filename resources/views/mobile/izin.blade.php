@extends('layouts.mobile')

@section('title', 'Izin')
@section('subtitle', 'Pengajuan Izin')

@section('content')
<div class="container py-3" style="max-width:420px;margin:auto;">
    <div class="card shadow-sm mb-3">
        <div class="card-body text-center py-3">
            <div class="avatar-lg mx-auto mb-2">
                <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                    <i class="bx bx-time fs-1"></i>
                </div>
            </div>
            <h6 class="mb-0">Pengajuan Izin</h6>
            <p class="text-muted small mb-0">Pilih tipe izin atau lengkapi formulir di bawah.</p>
        </div>
    </div>

    @php $type = request('type'); @endphp

    @if(!$type)
        <div class="d-grid gap-2">
            <a href="{{ route('mobile.izin', ['type' => 'tidak_masuk']) }}" class="btn btn-outline-secondary">Izin Tidak Masuk</a>
            <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="btn btn-outline-warning">Izin Terlambat</a>
            <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="btn btn-outline-primary">Izin Tugas Diluar</a>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <a href="{{ route('mobile.izin') }}" class="btn btn-sm btn-link mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>

                @if($type === 'tidak_masuk')
                    <h6>Izin Tidak Masuk</h6>
                    <form id="izinForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="tidak_masuk">
                        <div class="mb-3">
                            <label class="form-label">Alasan</label>
                            <textarea name="alasan" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Izin / Foto (opsional)</label>
                            <input type="file" name="file_izin" class="form-control" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Wajib diupload. Maks 5MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Izin</button>
                    </form>

                @elseif($type === 'terlambat')
                    <h6>Izin Terlambat Masuk</h6>
                    <form id="izinForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="terlambat">
                        <div class="mb-3">
                            <label class="form-label">Alasan Terlambat</label>
                            <textarea name="alasan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Izin / Foto</label>
                            <input type="file" name="file_izin" class="form-control" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOC. Maks 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu Masuk yang Diminta</label>
                            <input type="time" name="waktu_masuk" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Kirim Izin Terlambat</button>
                    </form>

                @elseif($type === 'tugas_luar')
                    <h6>Izin Tugas Diluar</h6>
                    <form id="izinForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="tugas_luar">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Tugas</label>
                            <textarea name="deskripsi_tugas" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Tugas</label>
                            <input type="text" name="lokasi_tugas" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Tugas / Foto</label>
                            <input type="file" name="file_tugas" class="form-control" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOC. Maks 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estimasi Waktu Keluar</label>
                            <input type="time" name="waktu_keluar" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Izin Tugas Diluar</button>
                    </form>

                @else
                    <div class="alert alert-warning">Tipe izin tidak dikenali.</div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
        $('#izinForm').on('submit', function(e){
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);

        let type = formData.get('type');

        // Basic client-side validation for required fields per type
        if (type === 'terlambat' || type === 'tidak_masuk') {
            if (!formData.get('alasan') || (!formData.get('file_izin') || formData.get('file_izin').size === 0)) {
                Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Lengkapi semua field dan upload file izin.'});
                return;
            }
            if (type === 'terlambat' && !formData.get('waktu_masuk')) {
                Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Waktu masuk yang diminta harus diisi.'});
                return;
            }
        }

        if (type === 'tugas_luar') {
            if (!formData.get('deskripsi_tugas') || !formData.get('lokasi_tugas') || !formData.get('waktu_keluar') || (!formData.get('file_tugas') || formData.get('file_tugas').size === 0)) {
                Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Lengkapi semua field untuk izin tugas diluar.'});
                return;
            }
        }

        // show loading
        let submitBtn = $(form).find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Mengirim...');

        $.ajax({
            url: '{{ route("mobile.izin.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 30000,
            success: function(res){
                if (res.success) {
                    Swal.fire({icon:'success', title:'Berhasil', text: res.message, timer:2000, timerProgressBar:true}).then(()=>{
                        window.location = '{{ route("mobile.izin.history") }}';
                    });
                } else {
                    Swal.fire({icon:'error', title:'Gagal', text: res.message || 'Terjadi kesalahan.'});
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status){
                let msg = 'Terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({icon:'error', title:'Kesalahan', text: msg});
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endsection
