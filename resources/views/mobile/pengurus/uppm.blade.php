@extends('layouts.mobile')

@section('title', 'UPPM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">UPPM (Unit Pengelola Program Madrasah)</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi UPPM</h5>
                    <p class="card-text">
                        Halaman ini berisi informasi terkait Unit Pengelola Program Madrasah (UPPM).
                        Fitur ini sedang dalam pengembangan.
                    </p>

                    @if(count($uppmData) > 0)
                        <div class="row">
                            @foreach($uppmData as $item)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $item['title'] ?? 'Judul' }}</h6>
                                            <p class="card-text">{{ $item['description'] ?? 'Deskripsi' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline"></i>
                            Data UPPM sedang dalam proses pengembangan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
