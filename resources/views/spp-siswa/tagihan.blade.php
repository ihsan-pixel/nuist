@extends('layouts.master')

@section('title')Tagihan SPP Siswa @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SPP Siswa @endslot
    @slot('title') Tagihan @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Terjadi kesalahan.</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($userRole === 'admin' && !$hasActiveBniVaSetting)
    <div class="alert alert-warning">
        Admin sekolah hanya dapat membuat tagihan setelah tersedia pengaturan aktif dengan provider `BNI Virtual Account`.
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
            <div>
                <h4 class="mb-1">Tagihan SPP Siswa</h4>
                <p class="text-muted mb-0">Semua tagihan di halaman ini memakai tabel baru `spp_siswa_bills` dan relasi ke data siswa.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkTagihanModal" {{ $userRole === 'admin' && !$hasActiveBniVaSetting ? 'disabled' : '' }}><i class="bx bx-layer-plus me-1"></i>Buat Tagihan Massal</button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTagihanModal" {{ $userRole === 'admin' && !$hasActiveBniVaSetting ? 'disabled' : '' }}><i class="bx bx-plus me-1"></i>Buat Tagihan</button>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-3 align-items-end">
                @if($userRole !== 'admin')
                    <div class="col-md-3">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahOptions as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2"><label class="form-label">Kelas</label><input type="text" name="kelas" value="{{ request('kelas') }}" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Jurusan</label><input type="text" name="jurusan" value="{{ request('jurusan') }}" class="form-control"></div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" @selected(request('status') === 'belum_lunas')>Belum Lunas</option>
                        <option value="sebagian" @selected(request('status') === 'sebagian')>Sebagian</option>
                        <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Pencarian</label><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="No tagihan, nama, NIS"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Tagihan</th>
                        <th>Siswa</th>
                        <th>Periode</th>
                        <th>Jatuh Tempo</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Virtual Account</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($bills as $index => $bill)
                    @php($latestVa = $bill->transactions->firstWhere('payment_channel', 'bni_va'))
                    <tr>
                        <td>{{ $bills->firstItem() + $index }}</td>
                        <td>{{ $bill->nomor_tagihan }}</td>
                        <td>
                            <div class="fw-semibold">{{ $bill->siswa->nama_lengkap ?? '-' }}</div>
                            <small class="text-muted">{{ $bill->siswa->nis ?? '-' }} | {{ $bill->siswa->kelas ?? '-' }}</small>
                        </td>
                        <td>{{ $bill->periode }}</td>
                        <td>{{ optional($bill->jatuh_tempo)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</td>
                        <td><span class="badge bg-{{ $bill->status === 'lunas' ? 'success' : ($bill->status === 'sebagian' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $bill->status)) }}</span></td>
                        <td>
                            @if(($bill->setting->payment_provider ?? 'manual') === 'bni_va' && $bill->status !== 'lunas')
                                @if($latestVa && $latestVa->va_number)
                                    <div class="fw-semibold">{{ $latestVa->va_number }}</div>
                                    <small class="text-muted d-block">{{ optional($latestVa->va_expired_at)->format('d M Y H:i') ?? 'Belum ada expiry' }}</small>
                                @else
                                    <small class="text-muted d-block">VA akan diterbitkan saat siswa mencetak billing.</small>
                                @endif
                            @else
                                <span class="text-muted">Manual</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('spp-siswa.tagihan.destroy', $bill) }}" onsubmit="return confirm('Hapus tagihan ini? Data yang sudah punya transaksi pembayaran tidak bisa dihapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">Belum ada tagihan SPP siswa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $bills->links() }}
    </div>
</div>

<div class="modal fade" id="bulkTagihanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('spp-siswa.tagihan.bulk-store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Tagihan Massal SPP Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Tagihan massal dibuat untuk semua siswa di sekolah terpilih. Jurusan dan kelas bisa dikosongkan jika ingin membuat tagihan untuk seluruh siswa sekolah tersebut.
                    </div>
                    <div class="row g-3">
                        @if($userRole !== 'admin')
                            <div class="col-md-6">
                                <label class="form-label">Madrasah</label>
                                <select name="madrasah_id" class="form-select" required>
                                    @foreach($madrasahOptions as $madrasah)
                                        <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Pengaturan</label>
                            <select name="setting_id" class="form-select" {{ $userRole === 'admin' ? 'required' : '' }}>
                                @if($userRole !== 'admin')
                                    <option value="">Manual tanpa pengaturan</option>
                                @endif
                                @foreach($settings as $setting)
                                    <option value="{{ $setting->id }}">{{ $setting->tahun_ajaran }} - {{ strtoupper(str_replace('_', ' ', $setting->payment_provider ?? 'manual')) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusanOptions as $jurusan)
                                    <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasOptions as $kelas)
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Periode</label><input type="month" name="periode" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Jatuh Tempo</label><input type="date" name="jatuh_tempo" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Nominal</label><input type="number" min="0" name="nominal" class="form-control" placeholder="Isi nominal tagihan" required></div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="belum_lunas">Belum Lunas</option>
                                <option value="sebagian">Sebagian</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Catatan</label><input type="text" name="catatan" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Buat Tagihan Massal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createTagihanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('spp-siswa.tagihan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Tagihan SPP Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @if($userRole !== 'admin')
                            <div class="col-md-6">
                                <label class="form-label">Madrasah</label>
                                <select name="madrasah_id" class="form-select" required>
                                    @foreach($madrasahOptions as $madrasah)
                                        <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Siswa</label>
                            <select name="siswa_id" class="form-select" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->nama_lengkap }} - {{ $student->nis }} - {{ $student->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pengaturan</label>
                            <select name="setting_id" class="form-select" {{ $userRole === 'admin' ? 'required' : '' }}>
                                @if($userRole !== 'admin')
                                    <option value="">Manual tanpa pengaturan</option>
                                @endif
                                @foreach($settings as $setting)
                                    <option value="{{ $setting->id }}">{{ $setting->tahun_ajaran }} - {{ strtoupper(str_replace('_', ' ', $setting->payment_provider ?? 'manual')) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Periode</label><input type="month" name="periode" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Jatuh Tempo</label><input type="date" name="jatuh_tempo" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Nominal</label><input type="number" min="0" name="nominal" class="form-control" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="belum_lunas">Belum Lunas</option>
                                <option value="sebagian">Sebagian</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Catatan</label><input type="text" name="catatan" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
