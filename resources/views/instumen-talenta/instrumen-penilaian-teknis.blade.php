@extends('layouts.master')

@section('title', 'Instrumen Penilaian - Teknis')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0">Instrumen Penilaian - Teknis</h4>
                <p class="text-muted mb-0">Lihat penilaian teknis per evaluator.</p>
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
                    @if(empty($teknis_details) || $teknis_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data teknis atau penilaian.</div>
                    @else
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter teknis">
                                @foreach($teknis_details as $td_btn)
                                    @php $tbtn = $td_btn['layanan']; $tbtnId = $tbtn->id; @endphp
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-teknis" data-teknis-id="{{ $tbtnId }}">{{ $tbtn->nama_layanan_teknis ?? 'Layanan ID:'.$tbtnId }}</button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($teknis_details as $td)
                            @php $t = $td['layanan']; $evaluators = $td['evaluators']; @endphp
                            <div class="mb-3 teknis-block" data-teknis-id="{{ $t->id }}">
                                <h6><strong>{{ $t->nama_layanan_teknis ?? 'Layanan ID:'.$t->id }}</strong></h6>
                                @if($evaluators->isEmpty())
                                    <div class="text-muted">Belum ada penilaian untuk layanan teknis ini.</div>
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
            const tButtons = document.querySelectorAll('.btn-teknis');
            const tBlocks = document.querySelectorAll('.teknis-block');
            tButtons.forEach(b => b.addEventListener('click', function(){
                const id = this.dataset.teknisId;
                tButtons.forEach(x=> x.classList.toggle('active', x===this));
                tBlocks.forEach(bl => bl.style.display = (bl.dataset.teknisId===id ? '' : 'none'));
            }));
            // initial: show all
            tBlocks.forEach(bl => bl.style.display = '');
        })();
    </script>
</div>
@endsection
