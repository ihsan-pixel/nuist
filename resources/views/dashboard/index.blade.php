{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.master')

@section('title') Dasbor @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-success-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p>Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        {{-- <img src="{{ URL::asset('build/images/logo 1.png') }}" alt="" class="img-fluid"> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
<img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-0 text-truncate">Nuist ID : {{ Auth::user()->nuist_id ?? '-' }}</p>
                    </div>
                    <div class="col-sm-8">
                        {{-- <div class="pt-4"> --}}
                            {{-- <div class="row">
                                <div class="col-6">
                                    <h5 class="font-size-15">{{ Auth::user()->status_kepegawaian ?? '-' }}</h5>
                                    <p class="text-muted mb-0">Status Kepegawaian</p>
                                </div>
                                <div class="col-6">
                                    <h5 class="font-size-15">{{ Auth::user()->masa_kerja ?? '-' }}</h5>
                                    <p class="text-muted mb-0">Masa Kerja</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="" class="btn btn-success waves-effect waves-light btn-sm">Lihat Profil <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div> --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role === 'tenaga_pendidik')
        {{-- Keaktifan --}}
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Keaktifan</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted">Bulan ini</p>
                        <h3>{{ round($attendanceData['kehadiran'] ?? 0) }}%</h3>
                        <p class="text-muted">
                            <span class="text-success me-2"> {{ round($attendanceData['kehadiran'] ?? 0) }}% <i class="mdi mdi-arrow-up"></i> </span> Kehadiran
                        </p>
                        <div class="row mt-3">
                            {{-- <div class="col-6">
                                <small class="text-muted">Hari Kerja</small>
                                <h6>{{ $attendanceData['total_hari_kerja'] ?? 0 }}</h6>
                            </div> --}}
                            <div class="col-6">
                                <small class="text-muted">Total Presensi</small>
                                <h6>{{ $attendanceData['total_presensi'] ?? 0 }}</h6>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('presensi.index') }}" class="btn btn-success waves-effect waves-light btn-sm">Lihat Detail <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mt-4 mt-sm-0">
                            <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts"></div>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0">Persentase kehadiran berdasarkan hari kerja (Senin-Sabtu, exclude hari libur).</p>
            </div>
        </div>
        @endif
    </div>

    @if(!in_array(Auth::user()->role, ['admin', 'super_admin']))
    <div class="col-xl-8">
        {{-- Tambah kartu info detail user di sebelah kanan --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi User</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">Asal Madrasah/Sekolah :</small>
                        <h6>{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tempat Lahir</small>
                        <h6>{{ Auth::user()->tempat_lahir ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Lahir</small>
                        <h6>{{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">TMT</small>
                        <h6>{{ Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-' }}</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">NUPTK</small>
                        <h6>{{ Auth::user()->nuptk ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NPK</small>
                        <h6>{{ Auth::user()->npk ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Kartanu</small>
                        <h6>{{ Auth::user()->kartanu ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NIP Ma'arif</small>
                        <h6>{{ Auth::user()->nip_maarif ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Status Kepegawaian</small>
                        <h6>{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Ketugasan</small>
                        <h6>{{ Auth::user()->ketugasan ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-info">Pendidikan Terakhir, Tahun Lulus</small>
                        <h6>{{ Auth::user()->pendidikan_terakhir ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-warning">Program Studi</small>
                        <h6>{{ Auth::user()->program_studi ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel daftar users --}}
        @if($showUsers)
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Rekan Guru/Pegawai Se-Madrasah/Sekolah</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Ketugasan</th>
                                <th>Status Kepegawaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>
<img src="{{ isset($user->avatar) ? asset('storage/' . $user->avatar) : asset('build/images/users/avatar-1.jpg') }}" alt="Foto {{ $user->name }}" class="rounded-circle" width="40" height="40">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->ketugasan ?? '-' }}</td>
                                <td>{{ $user->statusKepegawaian ? $user->statusKepegawaian->name : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Social Source, Activity, Top Cities --}}
{{-- <div class="row">
    <div class="col-xl-4">@include('dashboard.partials.social')</div>
    <div class="col-xl-4">@include('dashboard.partials.activity')</div>
    <div class="col-xl-4">@include('dashboard.partials.cities')</div>
</div> --}}

{{-- Latest Transaction --}}
{{-- <div class="row">
    <div class="col-lg-12">@include('dashboard.partials.transactions')</div>
</div> --}}

{{-- Email Verification Modal --}}
@if($showEmailVerificationModal ?? false)
<div class="modal fade" id="emailVerificationModal" tabindex="-1" aria-labelledby="emailVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark" id="emailVerificationModalLabel">
                    <i class="mdi mdi-email-alert-outline me-2"></i>Verifikasi Email Diperlukan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="mdi mdi-email-off-outline text-warning" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-center mb-3">Email Anda Belum Terverifikasi</h4>
                <p class="text-muted text-center mb-4">
                    Untuk keamanan akun dan kelancaran penggunaan sistem, mohon verifikasi email Anda.
                    Email verifikasi telah dikirim ke <strong>{{ Auth::user()->email }}</strong>
                </p>
                <div class="alert alert-info">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <strong>Langkah-langkah verifikasi:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Periksa kotak masuk email Anda</li>
                        <li>Klik link verifikasi yang telah dikirim</li>
                        <li>Kembali ke halaman ini dan refresh</li>
                    </ol>
                </div>
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-email-send me-2"></i>Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="mdi mdi-refresh me-2"></i>Refresh Halaman
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted">
                    <i class="mdi mdi-clock-outline me-1"></i>
                    Modal ini akan muncul setiap kali Anda login sampai email terverifikasi
                </small>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Modals --}}
{{-- @include('dashboard.partials.modals') --}}

@endsection

@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = @json($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]);

        var options = {
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '14px',
                            formatter: function (val) {
                                return val + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return 100 + "%";
                            }
                        }
                    }
                }
            },
            colors: ['#198754', '#ffc107', '#dc3545'],
            series: [
                attendanceData.kehadiran,
                attendanceData.izin_sakit,
                attendanceData.alpha
            ],
            labels: ['Kehadiran', 'Izin/Sakit', 'Tidak Hadir'],
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
                }
            }
        };

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                element,
                options
            );

            chart.render();
        }

        // Show email verification modal if needed
        @if($showEmailVerificationModal ?? false)
        var emailVerificationModal = new bootstrap.Modal(document.getElementById('emailVerificationModal'));
        emailVerificationModal.show();
        @endif
    });
</script>
@endsection
