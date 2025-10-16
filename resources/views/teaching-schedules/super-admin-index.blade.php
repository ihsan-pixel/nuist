@extends('layouts.master')

@section('title') Jadwal Mengajar - Super Admin @endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Jadwal Mengajar @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-calendar me-2"></i>Daftar Madrasah
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

                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama madrasah..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="kabupatenFilter" class="form-select">
                            <option value="">Semua Kabupaten</option>
                            @foreach($kabupatens as $kabupaten)
                            <option value="{{ $kabupaten }}" {{ request('kabupaten') == $kabupaten ? 'selected' : '' }}>{{ $kabupaten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="filterBtn" class="btn btn-primary w-100">
                            <i class="bx bx-filter-alt me-1"></i>Filter
                        </button>
                    </div>
                </div>

                <div id="schoolsContainer">
                    <div class="row">
                    @forelse($schoolsByKabupaten as $kabupaten => $schools)
                    <div class="col-12 mb-4">
                        <h4 class="mb-3">
                            <i class="bx bx-map-pin me-2"></i>{{ $kabupaten }}
                        </h4>
                        <div class="row">
                            @foreach($schools as $school)
                            <div class="mb-4" style="width: 20%;">
                                <div class="card h-100 border">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <i class="bx bx-building-house font-size-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-1">{{ $school->name }}</h5>
                                                <p class="text-muted mb-0">
                                                    <i class="bx bx-map-pin me-1"></i>{{ $school->kabupaten }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted mb-1">SCOD</p>
                                                    <h6 class="mb-0">{{ $school->scod }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted mb-1">Kabupaten</p>
                                                    <h6 class="mb-0">{{ $school->kabupaten }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('teaching-schedules.school-schedules', $school->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bx bx-calendar me-1"></i> Lihat Jadwal
                                            </a>
                                            <a href="{{ route('teaching-schedules.school-classes', $school->id) }}" class="btn btn-info btn-sm">
                                                <i class="bx bx-group me-1"></i> Lihat Kelas Berjalan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light rounded-circle">
                                    <i class="bx bx-building-house font-size-24 text-muted"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">Tidak ada data madrasah</h5>
                            <p class="text-muted">Belum ada madrasah yang terdaftar dalam sistem.</p>
                        </div>
                    </div>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filterBtn').on('click', function() {
        filterSchools();
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which == 13) { // Enter key
            filterSchools();
        }
    });

    $('#kabupatenFilter').on('change', function() {
        filterSchools();
    });

    function filterSchools() {
        const search = $('#searchInput').val();
        const kabupaten = $('#kabupatenFilter').val();

        $.ajax({
            url: '{{ route("teaching-schedules.filter") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                search: search,
                kabupaten: kabupaten
            },
            success: function(data) {
                updateSchoolsList(data);
            },
            error: function(xhr, status, error) {
                console.error('Error filtering schools:', error);
            }
        });
    }

    function updateSchoolsList(schoolsByKabupaten) {
        let html = '';

        if (Object.keys(schoolsByKabupaten).length === 0) {
            html = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="bx bx-building-house font-size-24 text-muted"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Tidak ada data madrasah</h5>
                        <p class="text-muted">Tidak ada madrasah yang sesuai dengan filter.</p>
                    </div>
                </div>
            `;
        } else {
            for (const [kabupaten, schools] of Object.entries(schoolsByKabupaten)) {
                html += `
                    <div class="col-12 mb-4">
                        <h4 class="mb-3">
                            <i class="bx bx-map-pin me-2"></i>${kabupaten}
                        </h4>
                        <div class="row">
                `;

                schools.forEach(function(school) {
                    html += `
                        <div class="mb-4" style="width: 20%;">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                <i class="bx bx-building-house font-size-18"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">${school.name}</h5>
                                            <p class="text-muted mb-0">
                                                <i class="bx bx-map-pin me-1"></i>${school.kabupaten}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <p class="text-muted mb-1">SCOD</p>
                                                <h6 class="mb-0">${school.scod}</h6>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <p class="text-muted mb-1">Kabupaten</p>
                                                <h6 class="mb-0">${school.kabupaten}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('teaching-schedules.school-schedules', ':schoolId') }}".replace(':schoolId', school.id) class="btn btn-warning btn-sm">
                                            <i class="bx bx-calendar me-1"></i> Lihat Jadwal
                                        </a>
                                        <a href="{{ route('teaching-schedules.school-classes', ':schoolId') }}".replace(':schoolId', school.id) class="btn btn-info btn-sm">
                                            <i class="bx bx-group me-1"></i> Lihat Kelas Berjalan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            }
        }

        $('#schoolsContainer .row').html(html);
    }
});
</script>
@endsection
