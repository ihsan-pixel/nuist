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
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm rounded border-0 h-100">
                            <div class="card-body text-center p-3">
                                @if($madrasah->logo)
                                <img src="{{ asset('storage/' . $madrasah->logo) }}" class="rounded-circle mx-auto d-block mb-3" alt="{{ $madrasah->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                <div class="rounded-circle mx-auto d-block mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bx bx-school bx-lg text-muted"></i>
                                </div>
                                @endif
                                <h5 class="card-title mb-2">{{ $madrasah->name }}</h5>
                                <p class="card-text text-muted small mb-2">{{ Str::limit($madrasah->alamat ?? 'Alamat tidak tersedia', 50) }}</p>
                                <span class="badge bg-success">
                                    <i class="bx bx-user me-1"></i>
                                    {{ $madrasah->tenaga_pendidik_count }} Tenaga Pendidik
                                </span>
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
