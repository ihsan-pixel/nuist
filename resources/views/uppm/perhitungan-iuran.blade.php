@extends('layouts.master')

@section('title')Perhitungan Iuran UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Perhitungan Iuran UPPM - Tahun {{ request('tahun', date('Y')) }}</h4>
                <div class="card-tools">
                    <form method="GET" class="d-inline">
                        <div class="input-group">
                            <select name="tahun" class="form-control" onchange="this.form.submit()">
                                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sekolah</th>
                                <th>Jumlah Siswa</th>
                                <th>Jumlah Guru Tetap</th>
                                <th>Jumlah Guru Tidak Tetap</th>
                                <th>Jumlah Guru PNS</th>
                                <th>Jumlah Guru PPPK</th>
                                <th>Jumlah Karyawan Tetap</th>
                                <th>Jumlah Karyawan Tidak Tetap</th>
                                <th>Nominal Bulanan</th>
                                <th>Total Tahunan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perhitungan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['madrasah']->name }}</td>
                                <td>{{ number_format($item['data']->jumlah_siswa) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_tidak_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_pns) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_pppk) }}</td>
                                <td>{{ number_format($item['data']->jumlah_karyawan_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_karyawan_tidak_tetap) }}</td>
                                <td>Rp {{ number_format($item['nominal_bulanan']) }}</td>
                                <td>Rp {{ number_format($item['total_tahunan']) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data perhitungan iuran untuk tahun ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif
</script>
@endsection
