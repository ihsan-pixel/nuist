@extends('layouts.mobile')

@section('title', 'Riwayat Izin')
@section('subtitle', 'Daftar Pengajuan Izin')

@section('content')
<div class="container py-3" style="max-width:420px;margin:auto;">
    <div class="card shadow-sm mb-3">
        <div class="card-body text-center py-3">
            <div class="avatar-lg mx-auto mb-2">
                <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                    <i class="bx bx-file fs-1"></i>
                </div>
            </div>
            <h6 class="mb-0">Riwayat Izin</h6>
            <p class="text-muted small mb-0">Daftar izin yang telah Anda ajukan.</p>
        </div>
    </div>

    @if($izinList->isEmpty())
        <div class="alert alert-secondary">Belum ada pengajuan izin.</div>
    @else
        @foreach($izinList as $izin)
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">{{ ucfirst($izin->status) }} - {{ $izin->status_izin ?? 'pending' }}</h6>
                            <small class="text-muted">{{ $izin->tanggal->format('d M Y') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">{{ $izin->waktu_masuk ? $izin->waktu_masuk->format('H:i') : '' }}</small>
                        </div>
                    </div>
                    <p class="mt-2 mb-0">{{ $izin->keterangan }}</p>
                    @if($izin->surat_izin_path)
                        <p class="mt-2 mb-0"><a href="{{ asset('storage/' . $izin->surat_izin_path) }}" target="_blank">Lihat Surat / Lampiran</a></p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <div class="mt-3">
        <a href="{{ route('mobile.izin') }}" class="btn btn-secondary w-100">Ajukan Izin Baru</a>
    </div>
</div>
@endsection
