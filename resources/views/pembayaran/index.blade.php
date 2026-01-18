@extends('layouts.master')

@section('title')Dashboard Pembayaran @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.menu-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.menu-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #48bb78, #38a169);
}

.menu-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.menu-icon.payment { background: linear-gradient(135deg, #667eea, #764ba2); }
.menu-icon.cash { background: linear-gradient(135deg, #4299e1, #3182ce); }
.menu-icon.gateway { background: linear-gradient(135deg, #ed8936, #dd6b20); }
.menu-icon.history { background: linear-gradient(135deg, #004b4c, #0e8549); }

.menu-card:hover .menu-icon {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

/* .menu-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
} */

.menu-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 20px;
}

.btn-modern {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 500;
    color: white;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
    color: white;
    text-decoration: none;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}



/* Responsive Design */
@media (max-width: 768px) {
    .menu-card {
        margin-bottom: 1rem;
    }

    .dashboard-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .menu-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .menu-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Dashboard Pembayaran @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-credit-card me-2"></i>
                    Dashboard Pembayaran
                </h4>
                <p class="text-white-50 mb-0">
                    Sistem pembayaran terintegrasi dengan data. Kelola pembayaran iuran, pantau status pembayaran, dan proses transaksi dengan mudah.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Lunas</p>
                        <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'lunas')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-time"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Lunas</p>
                        <h5 class="mb-0">Rp {{ number_format($totalLunasNominal, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Belum Bayar</p>
                        <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'belum_lunas')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Tagihan</p>
                        <h5 class="mb-0">Rp {{ number_format($totalTagihanNominal, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tagihan Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-list-ul me-2"></i>Data Tagihan Pembayaran
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Madrasah</th>
                                <th>Nomor Invoice</th>
                                <th>Jenis Tagihan</th>
                                <th>Total Tagihan</th>
                                <th>Status Pembayaran</th>
                                <th>Nominal Dibayar</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $item->madrasah->name }}</h6>
                                        <small class="text-muted">{{ $item->madrasah->address ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>{{ $item->nomor_invoice ?? '-' }}</td>
                                <td>{{ $item->jenis_tagihan ?? '-'}}</td>
                                <td>Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-modern bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status_pembayaran == 'lunas')
                                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                    @else
                                        Rp 0
                                    @endif
                                </td>
                                <td>{{ $item->tanggal_pembayaran ? $item->tanggal_pembayaran->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <button type="button" onclick="checkTagihan({{ $item->madrasah->id }}, {{ $tahun }}, '{{ $item->madrasah->name }}')"
                                            class="btn btn-sm btn-primary me-1">
                                        <i class="bx bx-detail me-1"></i>Detail
                                    </button>
                                    @if($item->status_pembayaran != 'lunas')
                                    <button type="button" onclick="showPaymentModal({{ $item->madrasah->id }}, {{ $tahun }}, '{{ $item->madrasah->name }}', {{ $item->total_nominal }})"
                                            class="btn btn-sm btn-success">
                                        <i class="bx bx-money me-1"></i>Bayar
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="bx bx-info-circle display-4 text-muted"></i>
                                        <h5 class="mt-3">Tidak Ada Data Tagihan</h5>
                                        <p class="text-muted">Belum ada data tagihan untuk tahun {{ $tahun }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary text-center">
                            <div class="card-body">
                                <i class="bx bx-money display-4 text-primary"></i>
                                <h5 class="card-title">Manual</h5>
                                <p class="card-text">Input data pembayaran manual</p>
                                <button type="button" class="btn btn-primary" onclick="showManualPayment()">Pilih Manual</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success text-center">
                            <div class="card-body">
                                <i class="bx bx-credit-card display-4 text-success"></i>
                                <h5 class="card-title">Online</h5>
                                <p class="card-text">Bayar melalui Midtrans</p>
                                <button type="button" class="btn btn-success" onclick="payOnline()">Pilih Online</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Payment Modal -->
<div class="modal fade" id="manualPaymentModal" tabindex="-1" aria-labelledby="manualPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualPaymentModalLabel">Pembayaran Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manualPaymentForm" action="{{ route('uppm.pembayaran.cash') }}" method="POST" onsubmit="return submitManualPayment(event)">
                    @csrf
                    <input type="hidden" name="madrasah_id" id="manual_madrasah_id">
                    <input type="hidden" name="tahun" id="manual_tahun">
                    <div class="mb-3">
                        <label for="manual_nominal" class="form-label">Nominal Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="manual_nominal" name="nominal" required min="0" step="1000" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="manual_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="manual_keterangan" name="keterangan" rows="3" placeholder="Catatan pembayaran (opsional)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Midtrans Snap.js -->
<script src="{{ App\Models\AppSetting::getSettings()->midtrans_is_production ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ App\Models\AppSetting::getSettings()->midtrans_client_key ?? config('services.midtrans.client_key') }}"></script>

<script>
function checkTagihan(madrasahId, tahun, madrasahName) {
    // Show loading
    Swal.fire({
        title: 'Memeriksa Tagihan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // AJAX request to check tagihan
    fetch(`/uppm/pembayaran/check-tagihan?madrasah_id=${madrasahId}&tahun=${tahun}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            Swal.close();

            if (data.exists) {
                // Redirect to detail page
                window.location.href = `/uppm/pembayaran/${madrasahId}?tahun=${tahun}`;
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Tagihan Tidak Ditemukan',
                    text: `Tagihan belum dibuat untuk madrasah ${madrasahName} pada tahun ${tahun}. Silakan buat tagihan terlebih dahulu.`,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal memeriksa tagihan. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        });
}

function showNoTagihanAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Tidak Ada Data Tagihan',
        text: 'Belum ada data tagihan untuk tahun ini.',
        confirmButtonText: 'OK'
    });
}

let currentMadrasahId = null;
let currentTahun = null;
let currentMadrasahName = null;
let currentTotalNominal = null;

function showPaymentModal(madrasahId, tahun, madrasahName, totalNominal) {
    currentMadrasahId = madrasahId;
    currentTahun = tahun;
    currentMadrasahName = madrasahName;
    currentTotalNominal = totalNominal;

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function showManualPayment() {
    // Close the payment modal
    const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    paymentModal.hide();

    // Set form values
    document.getElementById('manual_madrasah_id').value = currentMadrasahId;
    document.getElementById('manual_tahun').value = currentTahun;
    document.getElementById('manual_nominal').value = currentTotalNominal;

    // Show manual payment modal
    const manualModal = new bootstrap.Modal(document.getElementById('manualPaymentModal'));
    manualModal.show();
}

function payOnline(madrasahId, tahun, madrasahName, totalNominal) {
    // Show loading
    Swal.fire({
        title: 'Memproses...',
        text: 'Menghubungkan ke Midtrans',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Make AJAX request to Midtrans endpoint
    fetch('{{ route("uppm.pembayaran.midtrans") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            madrasah_id: madrasahId,
            tahun: tahun,
            nominal: totalNominal
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.success) {
            // Open Midtrans Snap popup
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    sendResultToBackend(result);
                },
                onPending: function(result) {
                    sendResultToBackend(result);
                },
                onError: function(result) {
                    console.log(result);
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message || 'Terjadi kesalahan saat memproses pembayaran'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan koneksi'
        });
    });
}

function sendResultToBackend(result) {
    fetch('{{ route("pembayaran.midtrans.result") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            result_data: JSON.stringify(result)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: 'Pembayaran Anda telah berhasil diproses'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: data.message || 'Terjadi kesalahan saat memproses pembayaran'
            });
        }
    })
    .catch(error => {
        console.error('Error sending result to backend:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat menyimpan data pembayaran'
        });
    });
}

function submitManualPayment(event) {
    event.preventDefault();

    // Show loading
    Swal.fire({
        title: 'Memproses Pembayaran...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Get form data
    const formData = new FormData(document.getElementById('manualPaymentForm'));

    // Make AJAX request
    fetch('{{ route("uppm.pembayaran.cash") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            // Close modal
            const manualModal = bootstrap.Modal.getInstance(document.getElementById('manualPaymentModal'));
            manualModal.hide();

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload page to update status
                location.reload();
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: data.message || 'Terjadi kesalahan saat memproses pembayaran'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan koneksi. Silakan coba lagi.'
        });
    });
}
</script>
@endsection
