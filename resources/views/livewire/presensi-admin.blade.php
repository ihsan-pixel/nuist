<div>
    <!-- Header Section - Modern PPDB Style -->
    <div class="welcome-section mb-4">
        <div class="welcome-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="mdi mdi-view-dashboard me-2"></i>
                        Data Presensi
                    </h2>
                    <p class="mb-0 opacity-75">Pantau dan kelola presensi tenaga pendidik di seluruh madrasah Ma'arif</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end">
                        <i class="mdi mdi-calendar-clock me-2"></i>
                        <span class="fw-semibold">{{ $selectedDate->format('d F Y') }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <input type="date" wire:model.live="selectedDate" class="form-control form-control-sm rounded-pill"
                               value="{{ $selectedDate->format('Y-m-d') }}" style="min-width: 140px;">
                        <a href="{{ route('presensi_admin.export', ['date' => $selectedDate->format('Y-m-d')]) }}"
                           class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="bx bx-download me-1"></i>Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview Header -->
    <div class="section-wrapper mb-4">
        <div class="card border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-dark mb-1">Ringkasan Presensi</h4>
                        <p class="text-muted mb-0">Data presensi tenaga pendidik hari ini</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-bar fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Statistics Row -->
    <div class="section-wrapper mb-4">
        <div class="row g-3">
            {{-- Users Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift total-sekolah">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['users_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Users Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-check fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sekolah Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift sekolah-buka">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['sekolah_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Sekolah Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-school fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Belum Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift pending">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['guru_tidak_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Belum Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-clock fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];
    @endphp

    @foreach($kabupatenOrder as $kabupaten)
        @php
            $kabupatenMadrasahData = collect($madrasahData)->filter(function($data) use ($kabupaten) {
                return $data['madrasah']->kabupaten === $kabupaten;
            });
        @endphp

        @if($kabupatenMadrasahData->count() > 0)
            <!-- Kabupaten Header - Modern PPDB Style -->
            <div class="kabupaten-group">
                <div class="kabupaten-header">
                    <i class="mdi mdi-city"></i>
                    <span>{{ $kabupaten }}</span>
                    <div class="ms-auto">
                        <small class="badge bg-primary bg-opacity-10 text-primary me-2">
                            {{ $kabupatenMadrasahData->count() }} Madrasah
                        </small>
                    </div>
                </div>

                <!-- Madrasah Cards - Modern PPDB Style -->
                <div class="kabupaten-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="mdi mdi-school me-1"></i>Nama Madrasah</th>
                                    <th><i class="mdi mdi-account-group me-1"></i>Tenaga Pendidik</th>
                                    <th><i class="mdi mdi-information me-1"></i>Status Presensi</th>
                                    <th><i class="mdi mdi-cog me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kabupatenMadrasahData as $data)
                                <tr>
                                    <td>
                                        <div class="sekolah-name">
                                            <span class="madrasah-detail-link fw-medium" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                {{ $data['madrasah']->name }}
                                            </span>
                                        </div>
                                        <div class="kabupaten-info">{{ $kabupaten }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ count($data['presensi']) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $hadir = collect($data['presensi'])->where('status', 'hadir')->count();
                                            $total = count($data['presensi']);
                                            $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="badge {{ $persentase >= 80 ? 'bg-success' : ($persentase >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $hadir }}/{{ $total }} ({{ $persentase }}%)
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="#" class="btn btn-outline-info btn-sm madrasah-detail-link" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="mdi mdi-eye me-1"></i>Lihat
                                            </a>
                                            @if($user->role === 'super_admin')
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="bx bx-download me-1"></i>Export
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
=======
            </div>
        @endif
    @endforeach
                        <div class="card-body p-3">
                            @if(count($data['presensi']) > 0)
                                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            @foreach($data['presensi'] as $presensi)
                                            <tr class="border-bottom border-light">
                                                <td class="ps-0 py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <div class="avatar-title bg-light text-primary rounded-circle">
                                                                <i class="bx bx-user"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <span class="user-detail-link fw-medium small" data-user-id="{{ $presensi['user_id'] }}" data-user-name="{{ $presensi['nama'] }}">
                                                                {{ $presensi['nama'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="pe-0 py-2 text-end">
                                                    @if($presensi['status'] == 'hadir')
                                                        <span class="status-badge bg-success text-white">Hadir</span>
                                                    @elseif($presensi['status'] == 'terlambat')
                                                        <span class="status-badge bg-warning text-white">Terlambat</span>
                                                    @elseif($presensi['status'] == 'izin')
                                                        <span class="status-badge bg-info text-white">Izin</span>
                                                    @else
                                                        <span class="status-badge bg-secondary text-white">Tidak Hadir</span>
                                                    @endif
                                                    @if(isset($presensi['is_fake_location']) && $presensi['is_fake_location'])
                                                        {{-- <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Fake Location</small> --}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="avatar-sm mx-auto mb-2">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="bx bx-user-x"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Tidak ada tenaga pendidik</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- User Detail Modal - Modern PPDB Style -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="userDetailModalLabel" style="font-weight: 600;">
                        <i class="mdi mdi-account-details me-2"></i>Detail Presensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="userDetailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informasi Pengguna</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Presensi</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userDetailTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Nama:</strong> <span id="detail-name"></span></div>
                                    <div class="mb-2"><strong>Email:</strong> <span id="detail-email" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>Madrasah:</strong> <span id="detail-madrasah"></span></div>
                                    <div class="mb-2"><strong>Status Kepegawaian:</strong> <span id="detail-status"></span></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>NIP:</strong> <span id="detail-nip" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>NUPTK:</strong> <span id="detail-nuptk" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>No HP:</strong> <span id="detail-phone"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive mt-3" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 100px;">Tanggal</th>
                                            <th style="width: 80px;">Masuk</th>
                                            <th style="width: 80px;">Keluar</th>
                                            <th style="width: 80px;">Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-history-body">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal - Modern PPDB Style -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="exportModalLabel" style="font-weight: 600;">
                        <i class="mdi mdi-file-export me-2"></i>Export Data Presensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Pilih jenis export untuk <strong id="exportMadrasahName"></strong>:</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" id="exportAllBtn">
                            <i class="bx bx-download me-2"></i>Export Semua Data
                        </button>
                        <button type="button" class="btn btn-outline-success" id="exportMonthBtn">
                            <i class="bx bx-calendar me-2"></i>Export Per Bulan
                        </button>
                    </div>
                    <div class="mt-3" id="monthSelector" style="display: none;">
                        <label for="exportMonth" class="form-label">Pilih Bulan:</label>
                        <input type="month" class="form-control" id="exportMonth" value="{{ date('Y-m') }}">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Madrasah Detail Modal - Modern PPDB Style -->
    <div class="modal fade" id="madrasahDetailModal" tabindex="-1" aria-labelledby="madrasahDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="madrasahDetailModalLabel" style="font-weight: 600;">
                        <i class="mdi mdi-school me-2"></i>Detail Madrasah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Nama Madrasah:</strong> <span id="madrasah-detail-name"></span></div>
                            <div class="mb-2"><strong>SCOD:</strong> <span id="madrasah-detail-scod"></span></div>
                            <div class="mb-2"><strong>Kabupaten:</strong> <span id="madrasah-detail-kabupaten"></span></div>
                            <div class="mb-2"><strong>Alamat:</strong> <span id="madrasah-detail-alamat"></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Hari KBM:</strong> <span id="madrasah-detail-hari-kbm"></span></div>
                            <div class="mb-2"><strong>Latitude:</strong> <span id="madrasah-detail-latitude"></span></div>
                            <div class="mb-2"><strong>Longitude:</strong> <span id="madrasah-detail-longitude"></span></div>
                            <div class="mb-2"><strong>Map Link:</strong> <a id="madrasah-detail-map-link" href="#" target="_blank">Lihat Peta</a></div>
                            <div class="mb-2"><strong>Polygon Koordinat:</strong> <span id="madrasah-detail-polygon">-</span></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Area Poligon Presensi</label>
                        <div id="madrasah-detail-map" style="height: 250px; width: 100%; margin-top: 15px; border: 1px solid #ddd; border-radius: 4px;"></div>
                        <small class="text-muted">Area poligon presensi madrasah ini.</small>
                    </div>

                    <h6>Daftar Tenaga Pendidik:</h6>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status Kepegawaian</th>
                                    <th>Status Presensi</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                </tr>
                            </thead>
                            <tbody id="madrasah-detail-guru-body">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern PPDB Style CSS -->
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2);
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50px, -50px);
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.total-sekolah {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.sekolah-buka {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-card.pending {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .stat-icon {
            position: relative;
            z-index: 1;
        }

        .kabupaten-group {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .kabupaten-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: between;
        }

        .kabupaten-header i {
            margin-right: 0.5rem;
            opacity: 0.9;
        }

        .kabupaten-table {
            background: white;
        }

        .kabupaten-table .table {
            margin-bottom: 0;
            border-radius: 0;
        }

        .kabupaten-table .table thead th {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
            border-bottom: 2px solid #dee2e6;
        }

        .kabupaten-table .table tbody tr {
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .kabupaten-table .table tbody tr:hover {
            background-color: rgba(0, 75, 76, 0.05);
        }

        .sekolah-name {
            font-weight: 600;
            color: #004b4c;
            margin-bottom: 0.25rem;
        }

        .kabupaten-info {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .action-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: 1px solid #004b4c;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
            color: white;
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .welcome-section {
                padding: 1.5rem;
            }

            .stat-card {
                margin-bottom: 1rem;
                padding: 1rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .kabupaten-header {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }
        }

        .text-dark {
            color: #004b4c !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .fw-medium {
            font-weight: 500;
        }
    </style>

    <!-- Scripts and Styles -->
    <script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
    $(document).ready(function () {
        // Handle user detail modal
        $(document).on('click', '.user-detail-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let userId = $(this).data('user-id');
            let userName = $(this).data('user-name');
            $('#userDetailModalLabel').text('Detail Presensi: ' + userName);

            $.ajax({
                url: '{{ url('/presensi-admin/detail') }}/' + userId,
                type: 'GET',
                success: function(data) {
                    // Populate user info tab
                    $('#detail-name').text(data.user.name);
                    $('#detail-email').text(data.user.email);
                    $('#detail-madrasah').text(data.user.madrasah);
                    $('#detail-status').text(data.user.status_kepegawaian);
                    $('#detail-nip').text(data.user.nip || '-');
                    $('#detail-nuptk').text(data.user.nuptk || '-');
                    $('#detail-phone').text(data.user.no_hp || '-');

                    // Populate history tab
                    let presensiRows = '';
                    data.presensi_history.forEach(function(presensi) {
                        let statusBadge = '';
                        if (presensi.status === 'hadir') {
                            statusBadge = '<span class="badge bg-success">Hadir</span>';
                        } else if (presensi.status === 'terlambat') {
                            statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                        } else if (presensi.status === 'izin') {
                            statusBadge = '<span class="badge bg-info">Izin</span>';
                        } else {
                            statusBadge = '<span class="badge bg-secondary">' + presensi.status + '</span>';
                        }

                        presensiRows += '<tr>' +
                            '<td>' + presensi.tanggal + '</td>' +
                            '<td>' + (presensi.waktu_masuk || '-') + '</td>' +
                            '<td>' + (presensi.waktu_keluar || '-') + '</td>' +
                            '<td>' + statusBadge + '</td>' +
                            '<td>' + (presensi.keterangan || '-') + '</td>' +
                            '</tr>';
                    });
                    $('#detail-history-body').html(presensiRows);

                    // Show modal
                    $('#userDetailModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading user detail:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat detail pengguna'
                    });
                }
            });
            return false;
        });

        // Handle madrasah detail modal
        $(document).on('click', '.madrasah-detail-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let madrasahId = $(this).data('madrasah-id');
            let madrasahName = $(this).data('madrasah-name');
            $('#madrasahDetailModalLabel').text('Detail Madrasah: ' + madrasahName);

            $.ajax({
                url: '{{ url('/presensi-admin/madrasah-detail') }}/' + madrasahId,
                type: 'GET',
                data: { date: '{{ $selectedDate->format('Y-m-d') }}' },
                success: function(data) {
                    // Populate madrasah info
                    $('#madrasah-detail-name').text(data.madrasah.name);
                    $('#madrasah-detail-scod').text(data.madrasah.scod || '-');
                    $('#madrasah-detail-kabupaten').text(data.madrasah.kabupaten || '-');
                    $('#madrasah-detail-alamat').text(data.madrasah.alamat || '-');
                    $('#madrasah-detail-hari-kbm').text(data.madrasah.hari_kbm || '-');
                    $('#madrasah-detail-latitude').text(data.madrasah.latitude || '-');
                    $('#madrasah-detail-longitude').text(data.madrasah.longitude || '-');
                    if (data.madrasah.map_link) {
                        $('#madrasah-detail-map-link').attr('href', data.madrasah.map_link).show();
                    } else {
                        $('#madrasah-detail-map-link').hide();
                    }
                    if (data.madrasah.polygon_koordinat) {
                        let polygonText = 'Ada (Tersimpan)';
                        if (data.madrasah.enable_dual_polygon && data.madrasah.polygon_koordinat_2) {
                            polygonText += ' + Dual Polygon';
                        }
                        $('#madrasah-detail-polygon').text(polygonText);
                    } else {
                        $('#madrasah-detail-polygon').text('Tidak Ada');
                    }

                    // Initialize map for polygon display
                    initializeMadrasahMap(data.madrasah);

                    // Populate guru list
                    let guruRows = '';
                    data.tenaga_pendidik.forEach(function(guru) {
                        let statusBadge = '';
                        if (guru.status === 'hadir') {
                            statusBadge = '<span class="badge bg-success">Hadir</span>';
                        } else if (guru.status === 'terlambat') {
                            statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                        } else if (guru.status === 'izin') {
                            statusBadge = '<span class="badge bg-info">Izin</span>';
                        } else {
                            statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
                        }

                        guruRows += '<tr>' +
                            '<td>' + guru.nama + '</td>' +
                            '<td>' + (guru.status_kepegawaian || '-') + '</td>' +
                            '<td>' + statusBadge + '</td>' +
                            '<td>' + (guru.waktu_masuk || '-') + '</td>' +
                            '<td>' + (guru.waktu_keluar || '-') + '</td>' +
                            '</tr>';
                    });
                    $('#madrasah-detail-guru-body').html(guruRows);

                    // Show modal
                    $('#madrasahDetailModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading madrasah detail:', error);
                    console.log('Response:', xhr.responseText);
                    let errorMessage = 'Gagal memuat detail madrasah';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
            return false;
        });

        // Handle export modal
        let currentMadrasahId = null;
        $(document).on('click', '[data-bs-target="#exportModal"]', function() {
            currentMadrasahId = $(this).data('madrasah-id');
            let madrasahName = $(this).data('madrasah-name');
            $('#exportMadrasahName').text(madrasahName);
            $('#monthSelector').hide();
        });

        $('#exportAllBtn').on('click', function() {
            if (currentMadrasahId) {
                window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=all';
            }
        });

        $('#exportMonthBtn').on('click', function() {
            $('#monthSelector').show();
        });

        $('#exportMonth').on('change', function() {
            if (currentMadrasahId) {
                let month = $(this).val();
                window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=month&month=' + month;
            }
        });

        // Function to initialize map for madrasah detail
        function initializeMadrasahMap(madrasah) {
            // Clear any existing map
            if (window.madrasahMap) {
                window.madrasahMap.remove();
            }

            // Initialize Leaflet map with default center
            let defaultLat = -7.7956;
            let defaultLon = 110.3695;
            let lat = madrasah.latitude || defaultLat;
            let lon = madrasah.longitude || defaultLon;
            window.madrasahMap = L.map('madrasah-detail-map').setView([lat, lon], 16);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(window.madrasahMap);

            let drawnItems = new L.FeatureGroup();
            window.madrasahMap.addLayer(drawnItems);

            // Add marker if coordinates exist
            if (madrasah.latitude && madrasah.longitude) {
                L.marker([lat, lon])
                    .addTo(window.madrasahMap)
                    .bindPopup('<b>' + madrasah.name + '</b><br/>' + (madrasah.alamat || ''));
            }

            // Load existing polygon like in edit modal (read-only)
            if (madrasah.polygon_koordinat) {
                try {
                    let geometry = JSON.parse(madrasah.polygon_koordinat);
                    let layer = L.geoJSON(geometry);
                    layer.eachLayer(function(l) {
                        drawnItems.addLayer(l);
                    });
                    if (drawnItems.getLayers().length > 0) {
                        window.madrasahMap.fitBounds(drawnItems.getBounds());
                    }
                } catch (e) {
                    console.error("Invalid GeoJSON data for polygon:", e);
                }
            }

            // Fit map to show all elements and adjust zoom for better view
            setTimeout(() => {
                window.madrasahMap.invalidateSize();

                // Force a resize to ensure proper rendering
                window.madrasahMap._onResize();

                // If we have both marker and polygon, fit bounds to show everything
                if (drawnItems.getLayers().length > 0 && madrasah.latitude && madrasah.longitude) {
                    // Create a group with both marker and polygon layers
                    let allLayers = new L.FeatureGroup();
                    drawnItems.eachLayer(layer => allLayers.addLayer(layer));

                    // Add a temporary marker to the group for bounds calculation
                    let tempMarker = L.marker([madrasah.latitude, madrasah.longitude]);
                    allLayers.addLayer(tempMarker);

                    // Fit bounds to show all elements
                    window.madrasahMap.fitBounds(allLayers.getBounds(), { padding: [20, 20] });

                    // Remove temporary marker
                    allLayers.removeLayer(tempMarker);
                } else if (drawnItems.getLayers().length > 0) {
                    // Only polygon exists, fit to polygon bounds
                    window.madrasahMap.fitBounds(drawnItems.getBounds(), { padding: [20, 20] });
                } else if (madrasah.latitude && madrasah.longitude) {
                    // Only marker exists, center on marker with appropriate zoom
                    window.madrasahMap.setView([madrasah.latitude, madrasah.longitude], 18);
                } else {
                    // No specific location, use default view
                    window.madrasahMap.setView([defaultLat, defaultLon], 13);
                }

                // Additional invalidateSize after bounds fitting to ensure polygon renders
                setTimeout(() => {
                    window.madrasahMap.invalidateSize();
                }, 200);
            }, 100);
        }
    });
    </script>
</div>
