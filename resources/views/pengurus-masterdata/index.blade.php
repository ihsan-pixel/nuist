@extends('layouts.master')

@section('title')
    Master Data Pengurus
@endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pengurus @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Master Data Pengurus</h4>
                <p class="card-title-desc">Kelola data master untuk peran pengurus.</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Data Yayasan -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-building-house bx-lg text-primary mb-3"></i>
                                <h5 class="card-title">Data Yayasan</h5>
                                <p class="card-text">Kelola data yayasan.</p>
                                <a href="{{ route('yayasan.index') }}" class="btn btn-primary">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Admin -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-user bx-lg text-success mb-3"></i>
                                <h5 class="card-title">Data Admin</h5>
                                <p class="card-text">Kelola data admin.</p>
                                <a href="{{ route('admin.index') }}" class="btn btn-success">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pengurus -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-group bx-lg text-info mb-3"></i>
                                <h5 class="card-title">Data Pengurus</h5>
                                <p class="card-text">Kelola data pengurus.</p>
                                <a href="{{ route('pengurus.index') }}" class="btn btn-info">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Madrasah/Sekolah -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-school bx-lg text-warning mb-3"></i>
                                <h5 class="card-title">Data Madrasah/Sekolah</h5>
                                <p class="card-text">Kelola data madrasah/sekolah.</p>
                                <a href="{{ route('madrasah.index') }}" class="btn btn-warning">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Tenaga Pendidik -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-chalkboard bx-lg text-danger mb-3"></i>
                                <h5 class="card-title">Data Tenaga Pendidik</h5>
                                <p class="card-text">Kelola data tenaga pendidik.</p>
                                <a href="{{ route('tenaga-pendidik.index') }}" class="btn btn-danger">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Status Kepegawaian -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-id-card bx-lg text-secondary mb-3"></i>
                                <h5 class="card-title">Data Status Kepegawaian</h5>
                                <p class="card-text">Kelola data status kepegawaian.</p>
                                <a href="{{ route('status-kepegawaian.index') }}" class="btn btn-secondary">Kelola</a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Tahun Pelajaran -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bx bx-calendar bx-lg text-dark mb-3"></i>
                                <h5 class="card-title">Data Tahun Pelajaran</h5>
                                <p class="card-text">Kelola data tahun pelajaran.</p>
                                <a href="{{ route('tahun-pelajaran.index') }}" class="btn btn-dark">Kelola</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
