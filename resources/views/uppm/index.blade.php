@extends('layouts.master')

@section('title')Dashboard UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Dashboard UPPM (Unit Pengembangan Pendidikan Ma'arif)</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>Selamat datang di Dashboard UPPM. Gunakan menu di bawah ini untuk mengelola data sekolah, melakukan perhitungan iuran, melihat tagihan, dan mengatur preferensi Anda.</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3">
                        <a href="{{ route('uppm.data-sekolah') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bx bx-school"></i><br>
                            Data Sekolah
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('uppm.perhitungan-iuran') }}" class="btn btn-info btn-lg w-100">
                            <i class="bx bx-calculator"></i><br>
                            Perhitungan Iuran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('uppm.tagihan') }}" class="btn btn-warning btn-lg w-100">
                            <i class="bx bx-receipt"></i><br>
                            Tagihan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('uppm.pengaturan') }}" class="btn btn-secondary btn-lg w-100">
                            <i class="bx bx-cog"></i><br>
                            Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
