@extends('layouts.master')

@section('title')Pengaturan UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pengaturan UPPM</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="bx bx-plus"></i> Tambah Pengaturan
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tahun Anggaran</th>
                                <th>Nominal Siswa</th>
                                <th>Nominal PNS Sertifikasi</th>
                                <th>Nominal PNS Non Sertifikasi</th>
                                <th>Nominal GTY Sertifikasi</th>
                                <th>Nominal GTY Sertifikasi Inpassing</th>
                                <th>Nominal GTY Non Sertifikasi</th>
                                <th>Nominal GTT</th>
                                <th>Nominal PTY</th>
                                <th>Nominal PTT</th>
                                <th>Jatuh Tempo</th>
                                <th>Skema Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $setting)
                            <tr>
                                <td>{{ $setting->tahun_anggaran }}</td>
                                <td>Rp {{ number_format($setting->nominal_siswa) }}</td>
                                <td>Rp {{ number_format($setting->nominal_pns_sertifikasi) }}</td>
                                <td>Rp {{ number_format($setting->nominal_pns_non_sertifikasi) }}</td>
                                <td>Rp {{ number_format($setting->nominal_gty_sertifikasi) }}</td>
                                <td>Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing) }}</td>
                                <td>Rp {{ number_format($setting->nominal_gty_non_sertifikasi) }}</td>
                                <td>Rp {{ number_format($setting->nominal_gtt) }}</td>
                                <td>Rp {{ number_format($setting->nominal_pty) }}</td>
                                <td>Rp {{ number_format($setting->nominal_ptt) }}</td>
                                <td>{{ $setting->jatuh_tempo }}</td>
                                <td>{{ ucfirst($setting->skema_pembayaran) }}</td>
                                <td>
                                    <span class="badge bg-{{ $setting->aktif ? 'success' : 'secondary' }}">
                                        {{ $setting->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSettingModal{{ $setting->id }}">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('uppm.pengaturan.destroy', $setting->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaturan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSettingModal{{ $setting->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pengaturan UPPM {{ $setting->tahun_anggaran }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('uppm.pengaturan.update', $setting->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                @include('uppm.form', ['setting' => $setting])
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="16" class="text-center">Belum ada pengaturan UPPM</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSettingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengaturan UPPM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('uppm.pengaturan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('uppm.form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
