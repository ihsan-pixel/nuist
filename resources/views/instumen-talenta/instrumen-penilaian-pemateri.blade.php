@extends('layouts.master')

@section('title', 'Instrumen Penilaian - Pemateri')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Instrumen Penilaian - Pemateri</h4>
            <p class="text-muted">Lihat penilaian pemateri per evaluator.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    @if(empty($pemateri_details) || $pemateri_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data pemateri atau penilaian.</div>
                    @else
                        <div class="mb-2 d-flex justify-content-end">
                            <a href="{{ route('instumen-talenta.instrumen-penilaian.pemateri.export_all') }}" class="btn btn-sm btn-success me-2">Export Semua (Excel)</a>
                        </div>
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter pemateri">
                                @foreach($pemateri_details as $md_btn)
                                    @php $mbtn = $md_btn['pemateri']; $mbtnId = $mbtn->id; @endphp
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-pemateri" data-pemateri-id="{{ $mbtnId }}">{{ $mbtn->nama ?? 'Pemateri ID:'.$mbtnId }}</button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($pemateri_details as $md)
                            @php $m = $md['pemateri']; $evaluators = $md['evaluators']; @endphp
                            <div class="mb-3 pemateri-block" data-pemateri-id="{{ $m->id }}">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0"><strong>{{ $m->nama ?? 'Pemateri ID:'.$m->id }}</strong></h6>
                                    <div>
                                        <a href="{{ route('instumen-talenta.instrumen-penilaian.pemateri.export', $m->id) }}" class="btn btn-sm btn-outline-success">Export (Excel)</a>
                                    </div>
                                </div>
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

    <script>
        (function(){
            const pButtons = document.querySelectorAll('.btn-pemateri');
            const pBlocks = document.querySelectorAll('.pemateri-block');
            pButtons.forEach(b => b.addEventListener('click', function(){
                const id = this.dataset.pemateriId;
                pButtons.forEach(x=> x.classList.toggle('active', x===this));
                pBlocks.forEach(bl => bl.style.display = (bl.dataset.pemateriId===id ? '' : 'none'));
            }));
            // initial: show all
            pBlocks.forEach(bl => bl.style.display = '');
        })();
    </script>
</div>
@endsection
