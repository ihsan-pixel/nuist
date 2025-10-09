@extends('layouts.master')

@section('title') Jadwal Mengajar @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Jadwal Mengajar @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jadwal Mengajar</h4>
                <p class="card-title-desc">
                    Halaman untuk mengelola jadwal mengajar tenaga pendidik.
                </p>

                <!-- Placeholder content -->
                <div class="alert alert-info">
                    <i class="mdi mdi-information-outline me-2"></i>
                    Fitur Jadwal Mengajar sedang dalam pengembangan.
                </div>

                <!-- TODO: Implement jadwal mengajar functionality -->
                <div class="text-center py-5">
                    <i class="mdi mdi-calendar-clock fs-1 text-primary mb-3"></i>
                    <h5>Jadwal Mengajar</h5>
                    <p class="text-muted">Fitur ini akan memungkinkan pengelolaan jadwal mengajar untuk tenaga pendidik.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
