@extends('layouts.master')

@section('title', 'Instrumen Penilaian - Fasilitator')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0">Instrumen Penilaian - Fasilitator</h4>
                <p class="text-muted mb-0">Lihat penilaian fasilitator per evaluator.</p>
            </div>
            <div>
                <a href="{{ route('instumen-talenta.instrumen-penilaian') }}" class="btn btn-sm btn-secondary">&larr; Kembali</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    @if(empty($fasilitator_details) || $fasilitator_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data fasilitator atau penilaian.</div>
                    @else
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter fasilitator">
                                @foreach($fasilitator_details as $fd_btn)
                                    @php $fbtn = $fd_btn['fasilitator']; $fbtnId = $fbtn->id; @endphp
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-fasilitator" data-fasilitator-id="{{ $fbtnId }}">{{ $fbtn->nama ?? 'Fasilitator ID:'.$fbtnId }}</button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($fasilitator_details as $fd)
                            @php $f = $fd['fasilitator']; $evaluators = $fd['evaluators']; @endphp
                            <div class="mb-3 fasilitator-block" data-fasilitator-id="{{ $f->id }}">
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
        </div>
    </div>
</div>
@endsection
        (function(){
            const fButtons = document.querySelectorAll('.btn-fasilitator');
            const fBlocks = document.querySelectorAll('.fasilitator-block');
            fButtons.forEach(b => b.addEventListener('click', function(){
                const id = this.dataset.fasilitatorId;
                fButtons.forEach(x=> x.classList.toggle('active', x===this));
                fBlocks.forEach(bl => bl.style.display = (bl.dataset.fasilitatorId===id ? '' : 'none'));
            }));
            // initial: show all
            fBlocks.forEach(bl => bl.style.display = '');
        })();
    </script>
</div>
@endsection
