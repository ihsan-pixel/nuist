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
                    <h5 class="card-title">Penilaian Peserta (per materi)</h5>

                    {{-- Materi navigation --}}
                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $selected_materi_id === 'all' ? 'active' : '' }}" href="{{ route('instumen-talenta.instrumen-penilaian', ['materi_id' => 'all']) }}">Semua Materi</a>
                        </li>
                        @foreach($materis as $materi)
                            <li class="nav-item">
                                <a class="nav-link {{ (string)$selected_materi_id === (string)$materi->id ? 'active' : '' }}" href="{{ route('instumen-talenta.instrumen-penilaian', ['materi_id' => $materi->id]) }}">{{ $materi->judul_materi }} @if($materi->tanggal_materi) <small class="text-muted">({{ \Carbon\Carbon::parse($materi->tanggal_materi)->format('d M Y') }})</small>@endif</a>
                            </li>
                        @endforeach
                    </ul>

                    @if(empty($evaluator_details) || $evaluator_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data penilaian peserta untuk materi yang dipilih.</div>
                    @else
                        {{-- Buttons: filter by evaluator --}}
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter evaluator">
                                {{-- removed 'Semua Penilai' button --}}
                                @foreach($evaluator_details as $ed_btn)
                                    @php $evbtn = $ed_btn['evaluator']; $evbtnId = $ed_btn['evaluator_id']; @endphp
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-evaluator" data-evaluator-id="{{ $evbtnId }}">{{ $evbtn ? $evbtn->name : 'User ID:'.$evbtnId }}</button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($evaluator_details as $ed)
                            @php $evaluator = $ed['evaluator']; $by_peserta = $ed['by_peserta']; @endphp
                            <div class="mb-4 evaluator-block" data-evaluator-id="{{ $ed['evaluator_id'] }}">
                                <h6><strong>{{ $evaluator ? $evaluator->name : 'User ID:'.$ed['evaluator_id'] }}</strong></h6>
                                <div class="text-muted small mb-2">{{ $evaluator ? $evaluator->email : '' }}</div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Peserta</th>
                                                @if($by_peserta->isNotEmpty())
                                                    @foreach(array_keys($by_peserta->first()['scores']) as $field)
                                                        <th class="text-center">{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                                                    @endforeach
                                                @else
                                                    <th class="text-center">-</th>
                                                @endif
                                                <th class="text-center">Entri</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($by_peserta as $bp)
                                                <tr>
                                                    <td>{{ $bp['peserta'] ? ($bp['peserta']->nama ?? ($bp['peserta']->user ? $bp['peserta']->user->name : 'ID:'.$bp['peserta_id'])) : 'ID:'.$bp['peserta_id'] }}</td>
                                                    @foreach($bp['scores'] as $val)
                                                        <td class="text-center">{{ $val !== null ? $val : '-' }}</td>
                                                    @endforeach
                                                    <td class="text-center">{{ $bp['count'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <script>
                        (function(){
                            const buttons = document.querySelectorAll('.btn-evaluator');
                            const blocks = document.querySelectorAll('.evaluator-block');

                            function setActive(id){
                                buttons.forEach(b => {
                                    if(b.dataset.evaluatorId === id) b.classList.add('active');
                                    else b.classList.remove('active');
                                });

                                blocks.forEach(bl => {
                                    if(id === 'all') bl.style.display = '';
                                    else bl.style.display = (bl.dataset.evaluatorId === id) ? '' : 'none';
                                });
                            }

                            buttons.forEach(btn => {
                                btn.addEventListener('click', function(){
                                    const id = this.dataset.evaluatorId;
                                    setActive(id);
                                });
                            });

                            // initial state: show all
                            setActive('all');
                        })();
                    </script>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Fasilitator</h5>
                    @if(empty($fasilitator_details) || $fasilitator_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data fasilitator atau penilaian.</div>
                    @else
                        {{-- Buttons: filter by fasilitator (show blocks per fasilitator) --}}
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter fasilitator">
                                {{-- removed 'Semua Fasilitator' button --}}
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

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Pemateri</h5>
                    @if(empty($pemateri_details) || $pemateri_details->isEmpty())
                        <div class="alert alert-warning">Tidak ada data pemateri atau penilaian.</div>
                    @else
                        {{-- Buttons: filter by pemateri --}}
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Filter pemateri">
                                {{-- removed 'Semua Pemateri' button --}}
                                @foreach($pemateri_details as $md_btn)
                                    @php $mbtn = $md_btn['pemateri']; $mbtnId = $mbtn->id; @endphp
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-pemateri" data-pemateri-id="{{ $mbtnId }}">{{ $mbtn->nama ?? 'Pemateri ID:'.$mbtnId }}</button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($pemateri_details as $md)
                            @php $m = $md['pemateri']; $evaluators = $md['evaluators']; @endphp
                            <div class="mb-3 pemateri-block" data-pemateri-id="{{ $m->id }}">
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

            <script>
                (function(){
                    // fasilitator filter
                    const fButtons = document.querySelectorAll('.btn-fasilitator');
                    const fBlocks = document.querySelectorAll('.fasilitator-block');
                    fButtons.forEach(b => b.addEventListener('click', function(){
                        const id = this.dataset.fasilitatorId;
                        fButtons.forEach(x=> x.classList.toggle('active', x===this));
                        fBlocks.forEach(bl => bl.style.display = (id==='all' ? '' : (bl.dataset.fasilitatorId===id ? '' : 'none')));
                    }));

                    // pemateri filter
                    const pButtons = document.querySelectorAll('.btn-pemateri');
                    const pBlocks = document.querySelectorAll('.pemateri-block');
                    pButtons.forEach(b => b.addEventListener('click', function(){
                        const id = this.dataset.pemateriId;
                        pButtons.forEach(x=> x.classList.toggle('active', x===this));
                        pBlocks.forEach(bl => bl.style.display = (id==='all' ? '' : (bl.dataset.pemateriId===id ? '' : 'none')));
                    }));
                })();
            </script>
        </div>
    </div>
</div>
@endsection
