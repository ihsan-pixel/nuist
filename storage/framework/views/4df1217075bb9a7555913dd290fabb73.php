<?php $__env->startSection('title', 'Laporan MGMP'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('mgmp.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
        min-height: 40vh;
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

    /* TABLE MODERN */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-modern th {
        background: #0f172a;
        color: white;
        padding: 14px;
        font-weight: 600;
    }

    .table-modern td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-modern tr:hover {
        background: #f1f5f9;
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
        <h1 class="hero-title animate fade-up" style="margin-top:80px;">
            Laporan MGMP
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">Musyawarah Guru Mata Pelajaran</h1>
        <p class="animate fade-up delay-2">Laporan kegiatan dan data MGMP untuk monitoring dan evaluasi.</p>
    </div>
</section>

<!-- LAPORAN KEGIATAN -->
<section id="laporan-kegiatan" class="section-clean">
    <div class="container">
        <h2 class="section-title animate fade-up">Laporan Kegiatan MGMP</h2>
        <p class="section-subtitle">Ringkasan laporan kegiatan MGMP berdasarkan periode dan jenis kegiatan.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <h3>Laporan Bulanan</h3>
                <p>Laporan kegiatan MGMP per bulan, termasuk jumlah peserta, materi yang dibahas, dan hasil evaluasi.</p>
            </div>
            <div class="card">
                <h3>Laporan Tahunan</h3>
                <p>Ringkasan kegiatan MGMP selama satu tahun, dengan analisis partisipasi dan dampak terhadap pembelajaran.</p>
            </div>
            <div class="card">
                <h3>Laporan Khusus</h3>
                <p>Laporan kegiatan khusus seperti workshop, seminar, atau pelatihan intensif yang dilakukan oleh MGMP.</p>
            </div>
        </div>
    </div>
</section>

<!-- DATA STATISTIK -->
<section id="data-statistik" class="section-soft">
    <div class="container">
        <h2 class="section-title animate fade-up">Data Statistik MGMP</h2>
        <p class="section-subtitle">Statistik partisipasi dan kinerja MGMP di LP Ma'arif NU DIY.</p>
        <div class="card animate fade-up delay-1">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Jumlah Guru</th>
                        <th>Kegiatan Bulan Ini</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Matematika</td>
                        <td>25</td>
                        <td>Workshop Aljabar</td>
                        <td>Aktif</td>
                    </tr>
                    <tr>
                        <td>Bahasa Indonesia</td>
                        <td>30</td>
                        <td>Seminar Sastra</td>
                        <td>Aktif</td>
                    </tr>
                    <tr>
                        <td>IPA</td>
                        <td>20</td>
                        <td>Pelatihan Praktikum</td>
                        <td>Aktif</td>
                    </tr>
                    <tr>
                        <td>IPS</td>
                        <td>18</td>
                        <td>Diskusi Kurikulum</td>
                        <td>Aktif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const animatedElements = document.querySelectorAll(".animate");

    animatedElements.forEach(el => {
        el.classList.add("show");
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/laporan.blade.php ENDPATH**/ ?>