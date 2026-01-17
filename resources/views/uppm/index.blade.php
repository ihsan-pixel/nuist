@extends('layouts.master')

@section('title')Dashboard UPPM @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.menu-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.menu-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #48bb78, #38a169);
}

.menu-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.menu-icon.school { background: linear-gradient(135deg, #667eea, #764ba2); }
.menu-icon.calculator { background: linear-gradient(135deg, #4299e1, #3182ce); }
.menu-icon.receipt { background: linear-gradient(135deg, #ed8936, #dd6b20); }
.menu-icon.settings { background: linear-gradient(135deg, #48bb78, #38a169); }

.menu-card:hover .menu-icon {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

.menu-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
}

.menu-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 20px;
}

.btn-modern {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 500;
    color: white;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
    color: white;
    text-decoration: none;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-card {
        margin-bottom: 1rem;
    }

    .dashboard-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .menu-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .menu-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

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
