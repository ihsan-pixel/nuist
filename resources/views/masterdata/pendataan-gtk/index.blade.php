@extends('layouts.master')

@section('title', 'Pendataan GTK')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pendataan GTK @endslot
@endcomponent

@section('css')
<style>
    .school-card {
        border: 1px solid #e9edf4;
        border-radius: 1rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        transition: transform .15s ease, box-shadow .15s ease;
        height: 100%;
    }
    .school-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .75rem 1.5rem rgba(15, 23, 42, .08);
    }
</style>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h4 class="card-title mb-1">Pendataan GTK</h4>
                        <p class="text-muted mb-0">Pilih sekolah untuk melihat seluruh tenaga pendidik dan melengkapi data GTK.</p>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-primary fs-6">{{ $madrasahs->count() }} sekolah</span>
                        <a href="{{ route('pendataan-gtk.export') }}" class="btn btn-success">
                            <i class="bx bx-download me-1"></i> Export Excel Semua GTK
                        </a>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($madrasahs as $madrasah)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="school-card p-3">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $madrasah->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($madrasah->logo) ? asset('storage/' . $madrasah->logo) : asset('build/images/logo-light.png') }}" alt="{{ $madrasah->name }}" class="rounded-3 border" style="width:64px;height:64px;object-fit:cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold">{{ $madrasah->name }}</div>
                                                <div class="text-muted small">{{ $madrasah->kabupaten ?: 'Kabupaten belum diisi' }}</div>
                                            </div>
                                            @if($madrasah->scod)
                                                <span class="badge bg-soft-primary text-primary">{{ $madrasah->scod }}</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small mt-2">{{ \Illuminate\Support\Str::limit($madrasah->alamat ?: 'Alamat belum tersedia', 90) }}</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <a href="{{ route('pendataan-gtk.show', $madrasah->id) }}" class="btn btn-primary btn-sm">
                                        Lihat GTK / Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
