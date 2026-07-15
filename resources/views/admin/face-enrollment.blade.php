@extends('layouts.vertical')

@section('title', 'Face Enrollment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Pendaftaran Wajah Guru</h4>

                    <div class="alert alert-info mb-4">
                        Presensi kehadiran mobile sekarang menggunakan scan wajah. Pendaftaran wajah dilakukan dari akun guru pada perangkat mobile agar kamera depan dan descriptor wajah terekam dari perangkat yang dipakai presensi.
                    </div>

                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4">Madrasah</dt>
                        <dd class="col-sm-8">{{ $user->madrasah?->nama ?? $user->madrasah?->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Status Wajah</dt>
                        <dd class="col-sm-8">
                            @if($user->face_registered_at)
                                <span class="badge bg-success">Sudah terdaftar</span>
                                <div class="text-muted small mt-1">
                                    Terdaftar pada {{ $user->face_registered_at->format('d/m/Y H:i') }}
                                </div>
                            @else
                                <span class="badge bg-warning text-dark">Belum terdaftar</span>
                            @endif
                        </dd>
                    </dl>

                    <ol class="ps-3 mb-4">
                        <li>Minta guru login ke menu mobile dengan akun masing-masing.</li>
                        <li>Buka menu `Presensi`, lalu pilih `Daftar Wajah`.</li>
                        <li>Lakukan scan wajah sampai status tersimpan.</li>
                        <li>Setelah berhasil, presensi kehadiran akan memakai scan wajah sebagai pengganti selfie.</li>
                    </ol>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('face.enrollment.list') }}" class="btn btn-secondary">
                            Kembali ke Daftar
                        </a>
                        <a href="{{ url('/mobile/face-enrollment') }}" class="btn btn-primary" target="_blank" rel="noopener">
                            Buka Halaman Mobile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
