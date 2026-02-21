@extends('layouts.master')

@section('title', 'Instrumen Penilaian')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Instrumen Penilaian</h4>
            <p class="text-muted">Lihat akumulasi hasil penilaian dari Peserta, Trainer, Fasilitator, dan Tim Teknis.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(empty($rows) || $rows->isEmpty())
                        <div class="alert alert-warning">Tidak ada data peserta.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Peserta</th>
                                        <th>Terima (avg)</th>
                                        <th>Berikan Trainer (avg)</th>
                                        <th>Berikan Fasilitator (avg)</th>
                                        <th>Berikan Teknis (avg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    @php $peserta = $row['peserta']; @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $peserta->nama ?? ($peserta->user ? $peserta->user->name : 'ID:'.$peserta->id) }}</strong>
                                            <div class="text-muted small">{{ $peserta->email ?? ($peserta->user ? $peserta->user->email : '') }}</div>
                                        </td>
                                        <td>
                                            @if($row['received_overall'] !== null)
                                                <div><strong>{{ $row['received_overall'] }}</strong></div>
                                                <div class="small text-muted">(dari {{ $row['received_count'] }} entri)</div>
                                            @else
                                                <div class="text-muted">-</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row['trainer_overall'] !== null)
                                                <div><strong>{{ $row['trainer_overall'] }}</strong></div>
                                                <div class="small text-muted">(dari {{ $row['trainer_count'] }} entri)</div>
                                            @else
                                                <div class="text-muted">-</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row['fasilitator_overall'] !== null)
                                                <div><strong>{{ $row['fasilitator_overall'] }}</strong></div>
                                                <div class="small text-muted">(dari {{ $row['fasilitator_count'] }} entri)</div>
                                            @else
                                                <div class="text-muted">-</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row['teknis_overall'] !== null)
                                                <div><strong>{{ $row['teknis_overall'] }}</strong></div>
                                                <div class="small text-muted">(dari {{ $row['teknis_count'] }} entri)</div>
                                            @else
                                                <div class="text-muted">-</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
