@extends('layouts.master')

@section('title', 'Kelengkapan Data Madrasah')

@section('css')
<style>
body:has(.admin-madrasah-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}.admin-madrasah-page{position:relative;isolation:isolate;padding:8px 0 24px}.admin-madrasah-page:before,.admin-madrasah-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:admin-madrasah-orb 11s ease-in-out infinite}.admin-madrasah-page:before{width:280px;height:280px;right:-90px;top:-80px;background:rgba(42,190,153,.15)}.admin-madrasah-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.12);animation-direction:reverse;animation-duration:14s}.admin-madrasah-page .admin-madrasah-card{border:1px solid rgba(255,255,255,.88);border-radius:22px;background:rgba(255,255,255,.7);backdrop-filter:blur(18px);box-shadow:0 10px 30px rgba(32,70,78,.09);overflow:hidden;animation:admin-madrasah-in .7s both}.admin-madrasah-page .card-header{border-bottom:1px solid rgba(255,255,255,.35);padding:18px 22px;background:linear-gradient(135deg,rgba(0,91,88,.96),rgba(8,116,90,.9))!important}.admin-madrasah-page .card-header h4{font-size:17px;font-weight:700}.admin-madrasah-page .card-body{padding:20px 24px 24px}.admin-madrasah-page .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}.admin-madrasah-page table.dataTable{width:100%!important;table-layout:auto;min-width:1100px}.admin-madrasah-page table.dataTable thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:#192d37;font-size:11px;padding:13px 14px;white-space:nowrap}.admin-madrasah-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:13px 14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere;word-break:break-word}.admin-madrasah-page table.dataTable tbody tr{transition:background .18s ease,transform .18s ease}.admin-madrasah-page table.dataTable tbody tr:hover{background:rgba(231,246,240,.45);transform:translateX(2px)}.admin-madrasah-page table.dataTable th:first-child,.admin-madrasah-page table.dataTable td:first-child{min-width:100px}.admin-madrasah-page table.dataTable th:nth-child(2),.admin-madrasah-page table.dataTable td:nth-child(2){min-width:220px}.admin-madrasah-page table.dataTable th:nth-child(3),.admin-madrasah-page table.dataTable td:nth-child(3){min-width:170px}.admin-madrasah-page table.dataTable th:last-child,.admin-madrasah-page table.dataTable td:last-child{min-width:120px}.admin-madrasah-page .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.admin-madrasah-page .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem;transition:transform .18s ease,box-shadow .18s ease}.admin-madrasah-page .dt-button:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(32,70,78,.16)}.admin-madrasah-page .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.admin-madrasah-page .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.admin-madrasah-page .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.admin-madrasah-page .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.admin-madrasah-page .buttons-colvis{background:linear-gradient(90deg,#db2777,#f472b6)!important}.admin-madrasah-page div.dt-button-collection{padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}.admin-madrasah-page div.dt-button-collection button.dt-button{display:block!important;width:100%!important;margin-bottom:6px;background:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;border-radius:14px!important;text-align:left}.admin-madrasah-page .badge{border-radius:999px}.admin-madrasah-page .text-success{color:#08745a!important}@keyframes admin-madrasah-orb{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@keyframes admin-madrasah-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(max-width:767px){.admin-madrasah-page .card-body{padding:14px}.admin-madrasah-page .card-header{padding:16px}.admin-madrasah-page .table-responsive{border-radius:12px}}
</style>
@endsection

@section('content')
<div class="admin-madrasah-page">
@foreach($kabupatenOrder as $kabupaten)
<div class="row">
    <div class="col-12">
        <div class="card admin-madrasah-card mb-4">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: {{ $kabupaten }}</h4>
                <a href="{{ route('admin.data_madrasah.export', ['kabupaten' => $kabupaten]) }}" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive"><table id="datatable-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
                            <th>Nama Madrasah</th>
                            <th>Alamat</th>
                            <th>Logo</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Map Link</th>
                            <th>Polygon (koordinat)</th>
                            <th>Hari KBM</th>
                            <th>Status Guru</th>
                            <th>Kelengkapan (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($madrasahs[$kabupaten] ?? [] as $index => $madrasah)
                        <tr>
                            <td>{{ $madrasah->scod }}</td>
                            <td>{{ $madrasah->name }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['alamat'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['logo'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['latitude'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['longitude'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['map_link'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">
                                {{ $madrasah->field_status['polygon_koordinat'] ?? '❌' }}
                                @if($madrasah->enable_dual_polygon && $madrasah->field_status['polygon_koordinat_2'] === '✅')
                                    <br><small class="text-success">+ Dual</small>
                                @endif
                            </td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['hari_kbm'] ?? '❌' }}</td>
                            <td style="font-size: 20px; text-align: center;">{{ $madrasah->field_status['status_guru'] ?? '❌' }}</td>
                            <td style="font-weight: bold; text-align: center;">{{ $madrasah->completeness_percentage }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Table for Jumlah Tenaga Pendidik -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card admin-madrasah-card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">Jumlah Tenaga Pendidik</h4>
            </div>
            <div class="card-body">
                @foreach($kabupatenOrder as $kabupaten)
                <h5 class="mt-4 mb-3">{{ $kabupaten }}</h5>
                <div class="table-responsive"><table id="datatable-tenaga-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-center align-middle">SCOD</th>
                            <th rowspan="2" class="text-center align-middle">Nama Sekolah/Madrasah</th>
                            <th colspan="10" class="text-center">Jumlah Tenaga Pendidik</th>
                        </tr>
                        <tr>
                            <th class="text-center">PNS Sertifikasi</th>
                            <th class="text-center">PNS Non Sertifikasi</th>
                            <th class="text-center">GTY Sertifikasi Inpassing</th>
                            <th class="text-center">GTY Sertifikasi</th>
                            <th class="text-center">GTY</th>
                            <th class="text-center">GTT</th>
                            <th class="text-center">PTY</th>
                            <th class="text-center">PTT</th>
                            <th class="text-center">Tidak Diketahui</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenagaPendidikData[$kabupaten] ?? [] as $data)
                        <tr>
                            <td class="text-center">{{ $data['scod'] }}</td>
                            <td>{{ $data['name'] }}</td>
                            <td class="text-center">{{ $data['pns_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['pns_non_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['gty_sertifikasi_inpassing'] }}</td>
                            <td class="text-center">{{ $data['gty_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['gty'] }}</td>
                            <td class="text-center">{{ $data['gtt'] }}</td>
                            <td class="text-center">{{ $data['pty'] }}</td>
                            <td class="text-center">{{ $data['ptt'] }}</td>
                            <td class="text-center">{{ $data['tidak_diketahui'] }}</td>
                            <td class="text-center font-weight-bold">{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        @foreach($kabupatenOrder as $kabupaten)
        $('#datatable-{{ Str::slug($kabupaten) }}').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        @endforeach
    });
</script>
@endpush

</div>

@endsection
