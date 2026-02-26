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
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Peserta (per evaluator)</h5>
                    @if(empty($participant_details) || $participant_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data peserta atau penilaian.</div>
                    @else
                        @foreach($participant_details as $pd)
                            @php $peserta = $pd['peserta']; $evaluators = $pd['evaluators']; @endphp
                            <div class="mb-3">
                                <h6><strong>{{ $peserta->nama ?? ($peserta->user ? $peserta->user->name : 'ID:'.$peserta->id) }}</strong></h6>
                                <div class="text-muted small mb-2">{{ $peserta->email ?? ($peserta->user ? $peserta->user->email : '') }}</div>

                                @if($evaluators->isEmpty())
                                    <div class="text-muted">Belum ada penilaian untuk peserta ini.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Evaluator</th>
                                                    @foreach(array_keys($evaluators->first()['scores']) as $field)
                                                        <th class="text-center">{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                                                    @endforeach
                                                    <th class="text-center">Entri</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($evaluators as $ev)
                                                <tr>
                                                    <td>{{ $ev['evaluator'] ? $ev['evaluator']->name : 'User ID:'.$ev['evaluator_id'] }}</td>
                                                    @foreach($ev['scores'] as $val)
                                                        <td class="text-center">{{ $val !== null ? $val : '-' }}</td>
                                                    @endforeach
                                                    <td class="text-center">{{ $ev['count'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Fasilitator</h5>
                    @if(empty($fasilitator_details) || $fasilitator_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data fasilitator atau penilaian.</div>
                    @else
                        @foreach($fasilitator_details as $fd)
                            @php $f = $fd['fasilitator']; $evaluators = $fd['evaluators']; @endphp
                            <div class="mb-3">
                                <h6><strong>{{ $f->nama ?? 'Fasilitator ID:'.$f->id }}</strong></h6>
                                @if($evaluators->isEmpty())
                                    <div class="text-muted">Belum ada penilaian untuk fasilitator ini.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Evaluator</th>
                                                    @foreach(array_keys($evaluators->first()['scores']) as $field)
                                                        <th class="text-center">{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                                                    @endforeach
                                                    <th class="text-center">Entri</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($evaluators as $ev)
                                                <tr>
                                                    <td>{{ $ev['evaluator'] ? $ev['evaluator']->name : 'User ID:'.$ev['evaluator_id'] }}</td>
                                                    @foreach($ev['scores'] as $val)
                                                        <td class="text-center">{{ $val !== null ? $val : '-' }}</td>
                                                    @endforeach
                                                    <td class="text-center">{{ $ev['count'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Pemateri</h5>
                    @if(empty($pemateri_details) || $pemateri_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data pemateri atau penilaian.</div>
                    @else
                        @foreach($pemateri_details as $md)
                            @php $m = $md['pemateri']; $evaluators = $md['evaluators']; @endphp
                            <div class="mb-3">
                                <h6><strong>{{ $m->nama ?? 'Pemateri ID:'.$m->id }}</strong></h6>
                                @if($evaluators->isEmpty())
                                    <div class="text-muted">Belum ada penilaian untuk pemateri ini.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Evaluator</th>
                                                    @foreach(array_keys($evaluators->first()['scores']) as $field)
                                                        <th class="text-center">{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                                                    @endforeach
                                                    <th class="text-center">Entri</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($evaluators as $ev)
                                                <tr>
                                                    <td>{{ $ev['evaluator'] ? $ev['evaluator']->name : 'User ID:'.$ev['evaluator_id'] }}</td>
                                                    @foreach($ev['scores'] as $val)
                                                        <td class="text-center">{{ $val !== null ? $val : '-' }}</td>
                                                    @endforeach
                                                    <td class="text-center">{{ $ev['count'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
