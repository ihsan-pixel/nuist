<?php $__env->startSection('title', 'Laporan Kegiatan MGMP - ' . config('app.name')); ?>

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
        background: #f8fafc;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HEADER */
    .page-header {
        margin-top: 100px;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 15px;
        color: #64748b;
    }

    /* STATS ROW */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .mini-stat {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
    }

    .mini-stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #004b4c;
        display: block;
    }

    .mini-stat-label {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    /* FILTER SECTION */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
    }

    .filter-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 180px;
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #334155;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #004b4c;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 75, 76, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 75, 76, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    /* Laporan LIST */
    .laporan-section {
        margin-bottom: 40px;
    }

    .laporan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .laporan-header h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }

    /* Laporan Cards */
    .laporan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 24px;
    }

    .laporan-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .laporan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }

    .laporan-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #00393a, #005555);
    }

    .laporan-content {
        padding: 24px;
    }

    .laporan-meta {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }

    .laporan-category {
        font-size: 12px;
        font-weight: 600;
        color: #004b4c;
        background: rgba(0, 75, 76, 0.1);
        padding: 4px 10px;
        border-radius: 6px;
    }

    .laporan-date {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .laporan-title {
        font-size: 18px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .laporan-description {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .laporan-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .laporan-stats {
        display: flex;
        gap: 16px;
        font-size: 13px;
        color: #64748b;
    }

    .laporan-stats span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .laporan-actions {
        display: flex;
        gap: 8px;
    }

    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        background: white;
        color: #64748b;
    }

    .action-icon:hover {
        background: #004b4c;
        color: white;
        border-color: #004b4c;
    }

    .action-icon.view:hover {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .action-icon.download:hover {
        background: #10b981;
        border-color: #10b981;
    }

    /* TABLE SECTION */
    .table-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-header h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }

    .laporan-table {
        width: 100%;
        border-collapse: collapse;
    }

    .laporan-table th {
        text-align: left;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .laporan-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
        vertical-align: middle;
    }

    .laporan-table tbody tr {
        transition: all 0.3s ease;
    }

    .laporan-table tbody tr:hover td {
        background: #f8fafc;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.completed {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-badge.ongoing {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-badge.upcoming {
        background: #fef3c7;
        color: #d97706;
    }

    .status-badge.draft {
        background: #f1f5f9;
        color: #64748b;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #94a3b8;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* TABS */
    .tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
    }

    .tab {
        padding: 10px 20px;
        border-radius: 8px 8px 0 0;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .tab:hover {
        color: #004b4c;
        background: rgba(0, 75, 76, 0.05);
    }

    .tab.active {
        color: #004b4c;
        background: rgba(0, 75, 76, 0.1);
        font-weight: 600;
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .laporan-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            margin-top: 80px;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        .laporan-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .tabs {
            overflow-x: auto;
        }

        .laporan-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <h1 class="animate fade-up">Laporan Kegiatan MGMP</h1>
        <p class="animate fade-up delay-1">Kelola dan pantau laporan kegiatan Musyawarah Guru Mata Pelajaran di LP Ma'arif NU DIY</p>
    </div>
</section>

<!-- STATS ROW -->
<section class="container">
    <div class="stats-row animate fade-up delay-1">
        <div class="mini-stat">
            <span class="mini-stat-number">48</span>
            <span class="mini-stat-label">Total Laporan</span>
        </div>
        <div class="mini-stat">
            <span class="mini-stat-number">12</span>
            <span class="mini-stat-label">Bulan Ini</span>
        </div>
        <div class="mini-stat">
            <span class="mini-stat-number">8</span>
            <span class="mini-stat-label">Sedang Berlangsung</span>
        </div>
        <div class="mini-stat">
            <span class="mini-stat-number">5</span>
            <span class="mini-stat-label">Akan Datang</span>
        </div>
    </div>
</section>

<!-- FILTER SECTION -->
<section class="container">
    <div class="filter-section animate fade-up delay-1">
        <form action="" method="GET" class="filter-row">
            <div class="filter-group">
                <label>Cari Laporan</label>
                <input type="text" name="search" placeholder="Judul kegiatan..." value="">
            </div>
            <div class="filter-group">
                <label>Kategori</label>
                <select name="category">
                    <option value="">Semua Kategori</option>
                    <option value="workshop">Workshop</option>
                    <option value="seminar">Seminar</option>
                    <option value="pelatihan">Pelatihan</option>
                    <option value="diskusi">Diskusi</option>
                    <option value="ujian">Ujian</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="completed">Selesai</option>
                    <option value="ongoing">Berlangsung</option>
                    <option value="upcoming">Akan Datang</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" name="date" value="">
            </div>
            <div class="filter-group" style="flex: 0;">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-search'></i> Filter
                </button>
            </div>
        </form>
    </div>
</section>

<!-- LAPORAN LIST -->
<section class="container laporan-section">
    <div class="laporan-header animate fade-up delay-2">
        <h3>Daftar Laporan Kegiatan</h3>
        <button class="btn btn-success">
            <i class='bx bx-plus'></i> Buat Laporan Baru
        </button>
    </div>

    <!-- TABS -->
    <div class="tabs animate fade-up delay-2">
        <a href="#" class="tab active">Semua</a>
        <a href="#" class="tab">Selesai</a>
        <a href="#" class="tab">Berlangsung</a>
        <a href="#" class="tab">Akan Datang</a>
        <a href="#" class="tab">Draft</a>
    </div>

    <div class="laporan-grid animate fade-up delay-2">
        <!-- Laporan Card 1 -->
        <div class="laporan-card">
            <div class="laporan-image" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); display: flex; align-items: center; justify-content: center;">
                <i class='bx bx-mathematics' style="font-size: 48px; color: white;"></i>
            </div>
            <div class="laporan-content">
                <div class="laporan-meta">
                    <span class="laporan-category">Workshop</span>
                    <span class="laporan-date"><i class='bx bx-calendar'></i> 15 Jan 2025</span>
                </div>
                <h3 class="laporan-title">Workshop Pembelajaran Matematika Kreatif</h3>
                <p class="laporan-description">Workshop ini membahas metode pembelajaran matematika yang kreatif dan menyenangkan untuk siswa...</p>
                <div class="laporan-footer">
                    <div class="laporan-stats">
                        <span><i class='bx bx-user'></i> 25 Guru</span>
                        <span><i class='bx bx-message-square'></i> 5</span>
                    </div>
                    <div class="laporan-actions">
                        <button class="action-icon view" title="Lihat Detail"><i class='bx bx-eye'></i></button>
                        <button class="action-icon download" title="Download PDF"><i class='bx bx-download'></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Card 2 -->
        <div class="laporan-card">
            <div class="laporan-image" style="background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                <i class='bx bx-book-open' style="font-size: 48px; color: white;"></i>
            </div>
            <div class="laporan-content">
                <div class="laporan-meta">
                    <span class="laporan-category">Seminar</span>
                    <span class="laporan-date"><i class='bx bx-calendar'></i> 18 Jan 2025</span>
                </div>
                <h3 class="laporan-title">Seminar Bahasa Indonesia dan Sastra</h3>
                <p class="laporan-description">Seminar tentang perkembangan bahasa dan sastra Indonesia di era digital...</p>
                <div class="laporan-footer">
                    <div class="laporan-stats">
                        <span><i class='bx bx-user'></i> 30 Guru</span>
                        <span><i class='bx bx-message-square'></i> 8</span>
                    </div>
                    <div class="laporan-actions">
                        <button class="action-icon view" title="Lihat Detail"><i class='bx bx-eye'></i></button>
                        <button class="action-icon download" title="Download PDF"><i class='bx bx-download'></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Card 3 -->
        <div class="laporan-card">
            <div class="laporan-image" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); display: flex; align-items: center; justify-content: center;">
                <i class='bx bx-flask' style="font-size: 48px; color: white;"></i>
            </div>
            <div class="laporan-content">
                <div class="laporan-meta">
                    <span class="laporan-category">Pelatihan</span>
                    <span class="laporan-date"><i class='bx bx-calendar'></i> 22 Jan 2025</span>
                </div>
                <h3 class="laporan-title">Pelatihan Praktikum IPA Terpadu</h3>
                <p class="laporan-description">Pelatihan praktikum IPA terpadu untuk meningkatkan kompetensi guru dalam...</p>
                <div class="laporan-footer">
                    <div class="laporan-stats">
                        <span><i class='bx bx-user'></i> 28 Guru</span>
                        <span><i class='bx bx-message-square'></i> 3</span>
                    </div>
                    <div class="laporan-actions">
                        <button class="action-icon view" title="Lihat Detail"><i class='bx bx-eye'></i></button>
                        <button class="action-icon download" title="Download PDF"><i class='bx bx-download'></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Card 4 -->
        <div class="laporan-card">
            <div class="laporan-image" style="background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center;">
                <i class='bx bx-chat' style="font-size: 48px; color: white;"></i>
            </div>
            <div class="laporan-content">
                <div class="laporan-meta">
                    <span class="laporan-category">Diskusi</span>
                    <span class="laporan-date"><i class='bx bx-calendar'></i> 25 Jan 2025</span>
                </div>
                <h3 class="laporan-title">Diskusi Kurikulum 2025 MGMP IPS</h3>
                <p class="laporan-description">Forum diskusi untuk membahas implementasi Kurikulum 2025 pada mata pelajaran IPS...</p>
                <div class="laporan-footer">
                    <div class="laporan-stats">
                        <span><i class='bx bx-user'></i> 20 Guru</span>
                        <span><i class='bx bx-message-square'></i> 12</span>
                    </div>
                    <div class="laporan-actions">
                        <button class="action-icon view" title="Lihat Detail"><i class='bx bx-eye'></i></button>
                        <button class="action-icon download" title="Download PDF"><i class='bx bx-download'></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TABLE SECTION -->
<section class="container">
    <div class="table-section animate fade-up delay-3">
        <div class="table-header">
            <h3>Laporan Terbaru</h3>
            <button class="btn btn-secondary">
                <i class='bx bx-download'></i> Export Semua
            </button>
        </div>

        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox"></th>
                    <th>Kegiatan</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Workshop Pembelajaran Matematika Kreatif</strong></td>
                    <td><span class="laporan-category">Workshop</span></td>
                    <td>15 Jan 2025</td>
                    <td>25 Guru</td>
                    <td><span class="status-badge completed">Selesai</span></td>
                    <td>
                        <button class="action-icon"><i class='bx bx-eye'></i></button>
                        <button class="action-icon"><i class='bx bx-edit'></i></button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Seminar Bahasa Indonesia dan Sastra</strong></td>
                    <td><span class="laporan-category">Seminar</span></td>
                    <td>18 Jan 2025</td>
                    <td>30 Guru</td>
                    <td><span class="status-badge completed">Selesai</span></td>
                    <td>
                        <button class="action-icon"><i class='bx bx-eye'></i></button>
                        <button class="action-icon"><i class='bx bx-edit'></i></button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Pelatihan Praktikum IPA Terpadu</strong></td>
                    <td><span class="laporan-category">Pelatihan</span></td>
                    <td>22 Jan 2025</td>
                    <td>28 Guru</td>
                    <td><span class="status-badge ongoing">Berlangsung</span></td>
                    <td>
                        <button class="action-icon"><i class='bx bx-eye'></i></button>
                        <button class="action-icon"><i class='bx bx-edit'></i></button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Diskusi Kurikulum 2025 MGMP IPS</strong></td>
                    <td><span class="laporan-category">Diskusi</span></td>
                    <td>25 Jan 2025</td>
                    <td>20 Guru</td>
                    <td><span class="status-badge upcoming">Akan Datang</span></td>
                    <td>
                        <button class="action-icon"><i class='bx bx-eye'></i></button>
                        <button class="action-icon"><i class='bx bx-edit'></i></button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Pelatihan Bahasa Inggris untuk Guru</strong></td>
                    <td><span class="laporan-category">Pelatihan</span></td>
                    <td>28 Jan 2025</td>
                    <td>22 Guru</td>
                    <td><span class="status-badge upcoming">Akan Datang</span></td>
                    <td>
                        <button class="action-icon"><i class='bx bx-eye'></i></button>
                        <button class="action-icon"><i class='bx bx-edit'></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Animation observer
    const animatedElements = document.querySelectorAll(".animate");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    });

    animatedElements.forEach(el => observer.observe(el));

    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/laporan.blade.php ENDPATH**/ ?>