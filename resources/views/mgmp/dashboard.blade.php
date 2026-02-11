@extends('layouts.master-without-nav')

@section('title', 'Dashboard MGMP')

@section('content')
@include('mgmp.navbar')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        position: relative;
        margin-top: 65px;
        margin-bottom: 30px;
        padding: 50px 20px 120px;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin-left: auto;
        margin-right: auto;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero h1 {
        font-size: 56px;
        font-weight: 800;
        line-height: 1.15;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #eda711;
    }

    .hero p {
        font-size: 18px;
        max-width: 720px;
        margin: 0 auto 40px;
        opacity: 0.9;
        color: white;
    }

    /* SECTION BACKGROUNDS */
    .section-clean {
        padding: 100px 0;
        background: #ffffff;
    }

    .section-soft {
        padding: 100px 0;
        background: #f8fafc;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 20px;
        color: #0f172a;
    }

    .section-subtitle {
        text-align: center;
        font-size: 16px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto 60px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .card {
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        transition: all 0.3s ease;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .card h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #0f172a;
    }

    .card p {
        font-size: 15px;
        color: #64748b;
        line-height: 1.7;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 40px 20px 120px;
            min-height: auto;
            margin-top: -10px;
        }

        .hero-title {
            font-size: 28px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 24px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .section-description {
            font-size: 16px;
            margin-bottom: 60px;
        }

        .grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            padding: 30px 20px;
        }

        .card h3 {
            font-size: 20px;
        }

        .card p {
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 15px;
        }

        .card {
            padding: 25px 15px;
        }
    }
</style>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <h1 class="hero-title animate fade-up">
            Dashboard MGMP
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">Musyawarah Guru Mata Pelajaran</h1>
        <p class="animate fade-up delay-2">Platform untuk mengelola dan memantau kegiatan MGMP di LP Ma'arif NU DIY.</p>
    </div>
</section>

<!-- PENGENALAN -->
<section id="pendahuluan" class="section-clean">
    <div class="container">
        <h2 class="section-title animate fade-up">Selamat Datang di MGMP</h2>
        <p class="section-subtitle">Musyawarah Guru Mata Pelajaran untuk meningkatkan kualitas pembelajaran.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <h3>Kegiatan MGMP</h3>
                <p>Kelola dan pantau berbagai kegiatan MGMP seperti workshop, seminar, dan pelatihan untuk guru mata pelajaran.</p>
            </div>
            <div class="card">
                <h3>Laporan Kegiatan</h3>
                <p>Akses laporan lengkap mengenai kegiatan MGMP, partisipasi guru, dan hasil evaluasi.</p>
            </div>
            <div class="card">
                <h3>Data Guru</h3>
                <p>Kelola data guru peserta MGMP berdasarkan mata pelajaran dan wilayah.</p>
            </div>
        </div>
    </div>
</section>

@include('landing.footer')

<script>
document.addEventListener("DOMContentLoaded", function() {
    const animatedElements = document.querySelectorAll(".animate");

    animatedElements.forEach(el => {
        el.classList.add("show");
    });
});
</script>

@endsection
