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

                    {{-- <div class="mb-2 d-flex justify-content-end">
                        <a href="{{ route('instumen-talenta.instrumen-penilaian.peserta.export_all', ['materi_id' => $selected_materi_id]) }}" class="btn btn-sm btn-success">Export Semua (Excel)</a>
                    </div> --}}

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
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0"><strong>{{ $evaluator ? $evaluator->name : 'User ID:'.$ed['evaluator_id'] }}</strong></h6>
                                    <div>
                                        <a href="{{ route('instumen-talenta.instrumen-penilaian.peserta.export', ['evaluatorId' => $ed['evaluator_id'], 'materi_id' => $selected_materi_id]) }}" class="btn btn-sm btn-outline-success">Export (Excel)</a>
                                    </div>
                                </div>
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
                    <h5 class="card-title">Penilaian Fasilitator & Pemateri</h5>
                    <p class="mb-2">Halaman penilaian Fasilitator, Pemateri, dan Teknis sekarang dipisah menjadi halaman tersendiri agar tampilan seragam.</p>
                    <a href="{{ route('instumen-talenta.instrumen-penilaian-fasilitator') }}" class="btn btn-sm btn-primary me-2">Lihat Penilaian Fasilitator</a>
                    <a href="{{ route('instumen-talenta.instrumen-penilaian-pemateri') }}" class="btn btn-sm btn-primary me-2">Lihat Penilaian Pemateri</a>
                    <a href="{{ route('instumen-talenta.instrumen-penilaian-teknis') }}" class="btn btn-sm btn-primary">Lihat Penilaian Teknis</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
