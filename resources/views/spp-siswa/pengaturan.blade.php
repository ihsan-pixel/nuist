@extends('layouts.master')

@section('title')Pengaturan SPP Siswa @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SPP Siswa @endslot
    @slot('title') Pengaturan @endslot
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

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Form Pengaturan</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('spp-siswa.pengaturan.store') }}">
                    @csrf
                    <div class="row g-3">
                        @if($userRole !== 'admin')
                            <div class="col-12">
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
                        <div class="col-md-6"><label class="form-label">Tahun Ajaran</label><input type="text" name="tahun_ajaran" class="form-control" placeholder="2025/2026" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Provider Pembayaran</label>
                            <select name="payment_provider" class="form-select" required>
                                <option value="manual">Manual</option>
                                <option value="bni_va">BNI Virtual Account</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">VA Expired (jam)</label><input type="number" min="1" max="720" name="va_expired_hours" class="form-control" value="24"></div>
                        <div class="col-12"><label class="form-label">Catatan</label><textarea name="catatan" rows="3" class="form-control"></textarea></div>
                        <div class="col-12"><label class="form-label">Catatan Pembayaran</label><textarea name="payment_notes" rows="3" class="form-control" placeholder="Contoh: Pembayaran hanya melalui Virtual Account BNI."></textarea></div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                                <label class="form-check-label" for="is_active">Aktifkan pengaturan ini</label>
                            </div>
                        </div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Simpan Pengaturan</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Daftar Pengaturan</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Madrasah</th>
                                <th>Tahun Ajaran</th>
                                <th>Provider</th>
                                <th>VA Expired</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($settings as $setting)
                            <tr>
                                <td>{{ $setting->madrasah->name ?? '-' }}</td>
                                <td>{{ $setting->tahun_ajaran }}</td>
                                <td>{{ strtoupper(str_replace('_', ' ', $setting->payment_provider ?? 'manual')) }}</td>
                                <td>{{ $setting->va_expired_hours ?? 24 }} jam</td>
                                <td><span class="badge bg-{{ $setting->is_active ? 'success' : 'secondary' }}">{{ $setting->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada pengaturan SPP siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $settings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
