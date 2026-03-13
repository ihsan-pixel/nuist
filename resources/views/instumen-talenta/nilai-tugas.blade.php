@extends('layouts.master')

@section('title', 'Nilai Tugas')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Nilai Tugas</h4>
            <p class="text-muted">Tabel nilai tugas masing-masing peserta. Gunakan halaman ini untuk monitoring dan export data jika dibutuhkan.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Daftar Nilai Tugas</strong>
                        </div>
                        <div class="d-flex align-items-center" style="gap:.5rem;">
                            <a href="{{ route('instumen-talenta.nilai-tugas.export', ['area' => request('area')]) }}" class="btn btn-sm btn-success">
                                Rekap Nilai Tugas (Excel)
                            </a>
                            <form method="GET" class="d-flex" style="gap:.5rem;">
                                <select name="area" class="form-select form-select-sm">
                                    <option value="">-- Semua Area --</option>
                                    @if(isset($areas))
                                        @foreach($areas as $area)
                                            <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <button class="btn btn-sm btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peserta / User</th>
                                    <th>Kelompok</th>
                                    <th>Jenis Tugas</th>
                                    <th>Area</th>
                                    <th>Penilai</th>
                                    <th>Nilai</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($nilai) || ($nilai instanceof \Illuminate\Support\Collection && $nilai->isEmpty()))
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data nilai tugas.</td>
                                    </tr>
                                @else
                                    @foreach($nilai as $index => $n)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if(isset($n->tugas->user))
                                                    {{ $n->tugas->user->name }}<br>
                                                    <small class="text-muted">{{ $n->tugas->user->madrasah->name ?? '-' }}</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $n->tugas->kelompok->nama_kelompok ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $jenis = $n->tugas->jenis_tugas ?? null;
                                                    $jenisLabel = '-';
                                                    if ($jenis === 'on_site') $jenisLabel = 'Tugas Onsite';
                                                    elseif ($jenis === 'terstruktur') $jenisLabel = 'Tugas Terstruktur';
                                                    elseif ($jenis === 'kelompok') $jenisLabel = 'Tugas Kelompok';
                                                    elseif (!empty($jenis)) $jenisLabel = ucfirst($jenis);
                                                @endphp
                                                {{ $jenisLabel }}
                                            </td>
                                            <td>{{ $n->tugas->area ?? '-' }}</td>
                                            <td>{{ $n->penilai->name ?? '-' }}</td>
                                            <td>{{ $n->nilai ?? '-' }}</td>
                                            <td>{{ optional($n->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Pemateri: status penilaian (sudah / belum) --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Status Penilaian Materi</strong>
                            <div class="small text-muted">Menampilkan pemateri yang sudah/ belum memasukkan nilai tugas (sesuai filter area jika dipilih).</div>
                        </div>
                        <div>
                            <a href="{{ route('instumen-talenta.nilai-tugas.status-penilaian-materi.export', ['area' => request('area')]) }}" class="btn btn-sm btn-success">
                                Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Pemateri</th>
                                    <th>Materi yang diampu</th>
                                    <th>Status</th>
                                    <th class="text-center">Jumlah Entri Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($pemateri_status) || ($pemateri_status instanceof \Illuminate\Support\Collection && $pemateri_status->isEmpty()))
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data pemateri.</td>
                                    </tr>
                                @else
                                    @foreach($pemateri_status as $i => $ps)
                                        @php $pem = $ps['pemateri']; $materis = $ps['materis']; @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $pem->nama ?? ($pem->user->name ?? '-') }}</td>
                                            <td>
                                                @if($materis && $materis->isNotEmpty())
                                                    @foreach($materis as $m)
                                                        <div>{{ $m->judul_materi ?? ($m->kode_materi ?? '-') }}</div>
                                                    @endforeach
                                                @else
                                                    <div class="text-muted">-</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ps['status'] === 'sudah')
                                                    <span class="badge bg-success">Sudah</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $ps['count'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
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

    <script>
        $(document).ready(function () {
            let table = $('#datatable-buttons').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ['copy', 'excel', 'csv', 'pdf', 'print', 'colvis'],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
                }
            });

            table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
