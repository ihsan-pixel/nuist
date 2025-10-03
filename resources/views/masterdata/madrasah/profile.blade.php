@extends('layouts.master')

@section('title', 'Profile Madrasah/Sekolah')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Profile Madrasah/Sekolah @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Profile Madrasah/Sekolah
                </h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($madrasahs->isEmpty())
                <div class="text-center p-4">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="bx bx-info-circle bx-lg me-2"></i>
                        <strong>Belum ada data madrasah</strong><br>
                        <small>Silakan tambahkan data madrasah terlebih dahulu melalui menu Master Data.</small>
                    </div>
                </div>
                @else
                <div class="row">
                    @forelse($madrasahs as $madrasah)
                    <div class="col-xxl-3 col-md-6">
                        <div class="card project-card" style="border: none; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); border-radius: 0.75rem; overflow: hidden;">
                            @if($madrasah->logo)
                            <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" class="card-img-top" alt="{{ $madrasah->name }}" style="height: 200px; object-fit: cover;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bx bx-school bx-lg text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-2">{{ $madrasah->name }}</h5>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($madrasah->alamat ?? 'Alamat tidak tersedia', 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('madrasah.detail', $madrasah->id) }}" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bx bx-user me-1"></i>
                                        Lihat Profile ({{ $madrasah->tenaga_pendidik_count }} TP)
                                    </a>
                                    <div class="d-flex gap-1">
                                        <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
