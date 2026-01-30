<?php $__env->startSection('title', $madrasah->name . ' - NUIST'); ?>
<?php $__env->startSection('description', 'Profil ' . $madrasah->name . ' Dibawah Naungan LPMNU PWNU DIY'); ?>

<?php $__env->startSection('content'); ?>
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

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        min-height: 280px;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 25px 25px;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .school-logo-large {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    }

    .school-logo-large img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .school-logo-large i {
        font-size: 50px;
        color: #00393a;
    }

    .school-title {
        text-align: left;
    }

    .school-title h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .school-title .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.25);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .school-title .scod {
        display: inline-block;
        margin-top: 10px;
        background: #eda711;
        color: #00393a;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    /* CONTENT */
    .content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 50px 40px;
    }

    /* School Info Section */
    .school-info-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }

    .school-info-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .school-details {
        border-right: 1px solid #e2e8f0;
        padding-right: 30px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 3px solid #eda711;
        display: inline-block;
    }

    .detail-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    .detail-row {
        display: flex;
        flex-direction: column;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
    }

    .detail-row:nth-child(2n) {
        border-right: none;
    }

    .detail-row:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .detail-label-text {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-value-text {
        font-size: 14px;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.5;
    }

    .detail-value-text a {
        color: #00393a;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .detail-value-text a:hover {
        color: #005555;
        text-decoration: underline;
    }

    /* Kepala Sekolah Section */
    .kepala-sekolah-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .ks-title {
        font-size: 14px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ks-photo-container {
        width: 160px;
        height: 180px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ks-photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ks-photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00393a, #005555);
    }

    .ks-photo-placeholder i {
        font-size: 60px;
        color: rgba(255, 255, 255, 0.5);
    }

    .ks-name {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        text-align: center;
        line-height: 1.3;
    }

    .ks-gelar {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
        text-align: center;
    }

    /* PPDB Button */
    .ppdb-button-row .detail-value-text {
        padding-top: 4px;
    }

    .ppdb-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 20px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 57, 58, 0.3);
    }

    .ppdb-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 57, 58, 0.4);
        color: white !important;
        text-decoration: none;
    }

    .ppdb-btn i {
        font-size: 18px;
    }

    /* Stats Cards */
    .stats-section {
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: white;
        padding: 28px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 30px;
        color: white;
    }

    .stat-icon.guru {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.siswa {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.jurusan {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-info {
        flex: 1;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* FOOTER */
    .footer {
        background: linear-gradient(135deg, #1f2937, #374151);
        color: white;
        padding: 40px;
        margin-top: 80px;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-logo img {
        height: 45px;
    }

    .footer-logo span {
        font-size: 20px;
        font-weight: 700;
        color: #eda711;
    }

    .footer-text {
        font-size: 14px;
        opacity: 0.8;
    }

    /* TAB NAVIGATION */
    .tab-navigation {
        background: white;
        border-radius: 24px;
        padding: 10px;
        margin-bottom: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .tab-buttons {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .tab-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex: 1;
        padding: 16px 20px;
        background: transparent;
        border: none;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: #f1f5f9;
        color: #00393a;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #00393a, #005555);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 57, 58, 0.0);
    }

    .tab-btn i {
        font-size: 20px;
    }

    /* TAB CONTENT */
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* SUB TAB NAVIGATION */
    .sub-tab-navigation {
        display: flex;
        gap: 8px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .sub-tab-btn {
        padding: 12px 24px;
        background: #f1f5f9;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sub-tab-btn:hover {
        background: #e2e8f0;
        color: #00393a;
    }

    .sub-tab-btn.active {
        background: linear-gradient(135deg, #00393a, #005555);
        color: white;
    }

    .sub-tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .sub-tab-content.active {
        display: block;
    }

    /* ABOUT SECTION */
    .about-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .about-content {
        font-size: 16px;
        line-height: 1.8;
        color: #475569;
    }

    .about-content h3 {
        font-size: 22px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 20px;
    }

    .about-content p {
        margin-bottom: 16px;
    }

    /* STATS CARDS */
    .stats-detailed-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
    }

    .stat-detailed-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .stat-detailed-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .stat-detailed-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .stat-detailed-icon i {
        font-size: 32px;
        color: white;
    }

    .stat-detailed-number {
        font-size: 42px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-detailed-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-detailed-card.siswa .stat-detailed-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-detailed-card.guru .stat-detailed-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-detailed-card.rombel .stat-detailed-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .stat-detailed-card.jurusan .stat-detailed-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-detailed-card.fasilitas .stat-detailed-icon {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }

    /* FACILITIES GRID */
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
    }

    .facility-card {
        background: white;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .facility-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .facility-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .facility-icon i {
        font-size: 36px;
        color: #00393a;
    }

    .facility-name {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .facility-count {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
    }

    /* GALLERY GRID */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .gallery-item {
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 4/3;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-item .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 50%);
        display: flex;
        align-items: flex-end;
        padding: 16px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay span {
        color: white;
        font-size: 14px;
        font-weight: 600;
    }

    .gallery-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    .gallery-placeholder i {
        font-size: 48px;
        margin-bottom: 12px;
    }

    /* ACHIEVEMENTS */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .achievement-card {
        background: white;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #eda711;
        transition: all 0.3s ease;
    }

    .achievement-card:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .achievement-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f1f5f9;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 16px;
    }

    .achievement-badge.kabupaten {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .achievement-badge.provinsi {
        background: #fef3c7;
        color: #d97706;
    }

    .achievement-badge.nasional {
        background: #dcfce7;
        color: #16a34a;
    }

    .achievement-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .achievement-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
    }

    .achievement-year {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 16px;
        font-size: 13px;
        font-weight: 600;
        color: #00393a;
    }

    /* CONTACT SECTION */
    .contact-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .map-container {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background: white;
        padding: 20px;
    }

    .map-container iframe {
        width: 100%;
        height: 400px;
        border-radius: 16px;
        border: none;
    }

    .contact-form-container {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .contact-form-title {
        font-size: 24px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #00393a;
        background: white;
        box-shadow: 0 0 0 4px rgba(0, 57, 58, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .submit-btn {
        width: 100%;
        padding: 16px 32px;
        background: linear-gradient(135deg, #00393a, #005555);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 57, 58, 0.35);
    }

    .contact-info {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .contact-info-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 0;
    }

    .contact-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00393a, #005555);
    }

    .contact-info-icon i {
        font-size: 20px;
        color: white;
    }

    .contact-info-text {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }

    .contact-info-text a {
        color: #00393a;
        text-decoration: none;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 20px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 15px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .school-info-layout {
            grid-template-columns: 1fr;
        }

        .school-details {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            padding-right: 0;
            padding-bottom: 30px;
        }

        .contact-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .hero {
            padding: 50px 20px;
        }

        .hero-header {
            flex-direction: column;
            text-align: center;
        }

        .school-title {
            text-align: center;
        }

        .school-title h1 {
            font-size: 28px;
        }

        .content {
            padding: 30px 20px;
        }

        .detail-list {
            grid-template-columns: 1fr;
        }

        .detail-row {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row:nth-last-child(-n+1) {
            border-bottom: none;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 28px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
        }

        .stat-icon i {
            font-size: 26px;
        }

        .footer-content {
            flex-direction: column;
            text-align: center;
        }

        .tab-navigation {
            padding: 8px;
        }

        .tab-btn {
            padding: 12px 10px;
            font-size: 11px;
            gap: 6px;
        }

        .tab-btn i {
            font-size: 16px;
        }

        .tab-buttons {
            gap: 6px;
        }

        .stats-detailed-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-detailed-card {
            padding: 24px 16px;
        }

        .stat-detailed-number {
            font-size: 32px;
        }

        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .facility-card {
            padding: 20px 16px;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .achievements-grid {
            grid-template-columns: 1fr;
        }

        .map-container iframe {
            height: 300px;
        }

        .contact-form-container {
            padding: 24px;
        }
    }

    @media (max-width: 480px) {
        .stats-detailed-grid {
            grid-template-columns: 1fr;
        }

        .facilities-grid {
            grid-template-columns: 1fr;
        }

        .stat-detailed-number {
            font-size: 36px;
        }

        .facility-name {
            font-size: 14px;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div style="text-align: left;">
            <a href="<?php echo e(route('landing.sekolah')); ?>" class="back-btn">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sekolah
            </a>
        </div>
        <div class="hero-header">
            <div class="school-logo-large">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>">
                <?php else: ?>
                    <i class="bi bi-building"></i>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="school-title">
                <h1><?php echo e($madrasah->name); ?></h1>
                <span class="badge">
                    <i class="bi bi-geo-alt-fill"></i> <?php echo e($madrasah->kabupaten); ?>

                </span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->scod): ?>
                    <span class="scod">SCOD: <?php echo e($madrasah->scod); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="content">
    <!-- School Info Section -->
    <div class="school-info-section">
        <div class="school-info-layout">
            <!-- Left Side: Informasi Sekolah (2/3) -->
            <div class="school-details">
                <h3 class="section-title">Informasi Sekolah/Madrasah</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <div class="detail-label-text">Akreditasi</div>
                        <div class="detail-value-text"><?php echo e($ppdbSetting->akreditasi ?? ($madrasah->akreditasi ?? '-')); ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Nomor Telepon</div>
                        <div class="detail-value-text"><?php echo e($ppdbSetting->telepon ?? ($madrasah->telepon ?? '-')); ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Email</div>
                        <div class="detail-value-text">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppdbSetting && $ppdbSetting->email): ?>
                                <a href="mailto:<?php echo e($ppdbSetting->email); ?>"><?php echo e($ppdbSetting->email); ?></a>
                            <?php elseif($madrasah->email): ?>
                                <a href="mailto:<?php echo e($madrasah->email); ?>"><?php echo e($madrasah->email); ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Website</div>
                        <div class="detail-value-text">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppdbSetting && $ppdbSetting->website): ?>
                                <a href="<?php echo e($ppdbSetting->website); ?>" target="_blank"><?php echo e($ppdbSetting->website); ?></a>
                            <?php elseif($madrasah->website): ?>
                                <a href="<?php echo e($madrasah->website); ?>" target="_blank"><?php echo e($madrasah->website); ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->alamat): ?>
                    <div class="detail-row" style="grid-column: span 2; border-right: none; border-bottom: 1px solid #e2e8f0;">
                        <div class="detail-label-text">Alamat Lengkap</div>
                        <div class="detail-value-text"><?php echo e($madrasah->alamat); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppdbSlug): ?>
                    <div class="detail-row ppdb-button-row" style="grid-column: span 2;">
                        <div class="detail-label-text">SPMB</div>
                        <div class="detail-value-text">
                            <a href="<?php echo e(route('ppdb.sekolah', $ppdbSlug)); ?>" class="ppdb-btn">
                                Halaman SPMB
                            </a>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Right Side: Kepala Sekolah (1/3) -->
            <div class="kepala-sekolah-section">
                <div class="ks-title">Kepala Sekolah</div>
                <div class="ks-photo-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kepalaSekolah && $kepalaSekolah->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $kepalaSekolah->avatar)); ?>" alt="Foto Kepala Sekolah" class="ks-photo-img">
                    <?php else: ?>
                        <div class="ks-photo-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kepalaSekolah): ?>
                    <div class="ks-name"><?php echo e($kepalaSekolah->name); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kepalaSekolah->gelar_depan || $kepalaSekolah->gelar_belakang): ?>
                        <div class="ks-gelar"><?php echo e($kepalaSekolah->gelar_depan ?? ''); ?> <?php echo e($kepalaSekolah->name); ?> <?php echo e($kepalaSekolah->gelar_belakang ?? ''); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <div class="ks-name">-</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon guru">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo e($jumlahGuru); ?></div>
                    <div class="stat-label">Jumlah Guru</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon siswa">
                    <i class="bi bi-people-fill"></i>
                </div>
                 <div class="stat-info">
                    <div class="stat-number"><?php echo e($jumlahSiswa); ?></div>
                    <div class="stat-label">Jumlah Siswa</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon jurusan">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo e($madrasah->jumlah_jurusan ?? ($ppdbSetting->jumlah_jurusan ?? '-')); ?></div>
                    <div class="stat-label">Jumlah Jurusan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation Section -->
    <div class="tab-navigation">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="tentang">
                <i class="bi bi-building"></i> Tentang Sekolah
            </button>
            <button class="tab-btn" data-tab="statistik">
                <i class="bi bi-bar-chart"></i> Data Statistik
            </button>
            <button class="tab-btn" data-tab="fasilitas">
                <i class="bi bi-building"></i> Fasilitas
            </button>
            <button class="tab-btn" data-tab="galeri">
                <i class="bi bi-images"></i> Galeri Foto
            </button>
            <button class="tab-btn" data-tab="prestasi">
                <i class="bi bi-trophy"></i> Prestasi
            </button>
            <button class="tab-btn" data-tab="kontak">
                <i class="bi bi-geo-alt"></i> Kontak & Lokasi
            </button>
        </div>
    </div>

    <!-- Tab Content Sections -->

    <!-- TENTANG SEKOLAH -->
    <div class="tab-content active" id="tentang">
        <div class="about-section">
            <div class="sub-tab-navigation">
                <button class="sub-tab-btn active" data-subtab="sejarah">Sejarah Singkat</button>
                <button class="sub-tab-btn" data-subtab="visi">Visi & Misi</button>
                <button class="sub-tab-btn" data-subtab="motto">Motto Sekolah</button>
            </div>

            <!-- Sejarah -->
            <div class="sub-tab-content active" id="sejarah">
                <div class="about-content">
                    <h3>Sejarah Singkat <?php echo e($madrasah->name); ?></h3>
                    <p><?php echo e($madrasah->sejarah ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'); ?></p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>

            <!-- Visi & Misi -->
            <div class="sub-tab-content" id="visi">
                <div class="about-content">
                    <h3>Visi</h3>
                    <p><?php echo e($madrasah->visi ?? 'Terwujudnya lembaga pendidikan yang bermutu, berakhlakul karimah, dan mampu bersaing di era global.'); ?></p>

                    <h3 style="margin-top: 30px;">Misi</h3>
                    <ul style="list-style: disc; padding-left: 24px; margin-bottom: 20px;">
                        <li>Meningkatkan kualitas pembelajaran yang efektif dan efisien</li>
                        <li>Mengembangkan potensi siswa secara optimal</li>
                        <li>Membudayakan akhlakul karimah dalam kehidupan sehari-hari</li>
                        <li>Membangun kerjasama dengan orang tua dan masyarakat</li>
                        <li>Menfasilitasi pengembangan bakat dan minat siswa</li>
                    </ul>
                </div>
            </div>

            <!-- Motto -->
            <div class="sub-tab-content" id="motto">
                <div class="about-content">
                    <h3>Motto Sekolah</h3>
                    <div style="text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 16px; margin-top: 20px;">
                        <p style="font-size: 24px; font-weight: 700; color: #00393a; margin-bottom: 10px;">"BERPRESTASI, BERAKHLAK, BERMANFAAT"</p>
                        <p style="font-size: 16px; color: #64748b;"><?php echo e($madrasah->motto ?? 'Motto ini mencerminkan komitmen sekolah dalam membentuk generasi yang berprestasi, berakhlak mulia, dan memberikan manfaat bagi masyarakat.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA STATISTIK -->
    <div class="tab-content" id="statistik">
        <div class="stats-detailed-grid">
            <div class="stat-detailed-card siswa">
                <div class="stat-detailed-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-detailed-number"><?php echo e($jumlahSiswa); ?></div>
                <div class="stat-detailed-label">Jumlah Siswa</div>
            </div>

            <div class="stat-detailed-card guru">
                <div class="stat-detailed-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-detailed-number"><?php echo e($jumlahGuru); ?></div>
                <div class="stat-detailed-label">Jumlah Guru</div>
            </div>

            <div class="stat-detailed-card rombel">
                <div class="stat-detailed-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <div class="stat-detailed-number"><?php echo e($madrasah->jumlah_rombel ?? '-'); ?></div>
                <div class="stat-detailed-label">Jumlah Rombel</div>
            </div>

            <div class="stat-detailed-card jurusan">
                <div class="stat-detailed-icon">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-detailed-number"><?php echo e($madrasah->jumlah_jurusan ?? ($ppdbSetting->jumlah_jurusan ?? '-')); ?></div>
                <div class="stat-detailed-label">Jumlah Jurusan</div>
            </div>

            <div class="stat-detailed-card fasilitas">
                <div class="stat-detailed-icon">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div class="stat-detailed-number">12</div>
                <div class="stat-detailed-label">Fasilitas Utama</div>
            </div>
        </div>
    </div>

    <!-- FASILITAS -->
    <div class="tab-content" id="fasilitas">
        <div class="facilities-grid">
            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <div class="facility-name">Ruang Kelas</div>
                <div class="facility-count">24 Ruang</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-pc-display"></i>
                </div>
                <div class="facility-name">Lab Komputer</div>
                <div class="facility-count">3 Lab</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="facility-name">Perpustakaan</div>
                <div class="facility-count">1 Unit</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="facility-name">Masjid/Musholla</div>
                <div class="facility-count">1 Unit</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-tree"></i>
                </div>
                <div class="facility-name">Lapangan</div>
                <div class="facility-count">2 Lapangan</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <div class="facility-name">UKS</div>
                <div class="facility-count">1 Unit</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-cup-hot"></i>
                </div>
                <div class="facility-name">Kantin</div>
                <div class="facility-count">2 Unit</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="facility-name">Lab Bahasa</div>
                <div class="facility-count">1 Lab</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-flask"></i>
                </div>
                <div class="facility-name">Lab IPA</div>
                <div class="facility-count">2 Lab</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-bus-front"></i>
                </div>
                <div class="facility-name">Parkiran</div>
                <div class="facility-count">Area Luas</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-wifi"></i>
                </div>
                <div class="facility-name">WiFi</div>
                <div class="facility-count">全 Area</div>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="facility-name">Keamanan</div>
                <div class="facility-count">24/7</div>
            </div>
        </div>
    </div>

    <!-- GALERI FOTO -->
    <div class="tab-content" id="galeri">
        <div class="gallery-grid">
            <!-- Placeholder Gallery Items -->
            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Upacara Bendera</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span> Pembelajaran di Kelas</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Lab Komputer</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Perpustakaan</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Olahraga</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Kegiatan Rohis</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Praktikum</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Perayaan Hari Besar</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Extrakurikuler</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Wisuda</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Kunjungan Studi</span>
                </div>
            </div>

            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Foto Kegiatan</span>
                </div>
                <div class="gallery-overlay">
                    <span>Lomba Tingkat Sekolah</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PRESTASI -->
    <div class="tab-content" id="prestasi">
        <div class="achievements-grid">
            <div class="achievement-card">
                <span class="achievement-badge kabupaten">
                    <i class="bi bi-geo-alt"></i> Kabupaten
                </span>
                <div class="achievement-title">Juara 1 Lomba MTQ Tingkat Kabupaten</div>
                <div class="achievement-desc">Prestasi gemilang siswa dalam cabang Musabaqah Tilawatil Quran</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2024
                </div>
            </div>

            <div class="achievement-card">
                <span class="achievement-badge provinsi">
                    <i class="bi bi-globe"></i> Provinsi
                </span>
                <div class="achievement-title">Juara 2 Olympiade Matematika</div>
                <div class="achievement-desc">Siswa berhasil menempati posisi kedua dalam olympiade matematika se-DIY</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2024
                </div>
            </div>

            <div class="achievement-card">
                <span class="achievement-badge nasional">
                    <i class="bi bi-star"></i> Nasional
                </span>
                <div class="achievement-title">Juara 3 Lomba Debat Bahasa Indonesia</div>
                <div class="achievement-desc">Tim debat sekolah meraih peringkat nasional</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2023
                </div>
            </div>

            <div class="achievement-card">
                <span class="achievement-badge kabupaten">
                    <i class="bi bi-geo-alt"></i> Kabupaten
                </span>
                <div class="achievement-title">Juara 1 Voli Putri</div>
                <div class="achievement-desc">Tim voli putri memenangkan kompetisi tingkat kabupaten</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2024
                </div>
            </div>

            <div class="achievement-card">
                <span class="achievement-badge provinsi">
                    <i class="bi bi-globe"></i> Provinsi
                </span>
                <div class="achievement-title">Akreditasi A</div>
                <div class="achievement-desc">Sekolah berhasil mempertahankan akreditasi dengan nilai excellent</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2023
                </div>
            </div>

            <div class="achievement-card">
                <span class="achievement-badge kabupaten">
                    <i class="bi bi-geo-alt"></i> Kabupaten
                </span>
                <div class="achievement-title">Juara 1 Pidato Bahasa Arab</div>
                <div class="achievement-desc">Siswa terbaik dalam kompetisi pidato bahasa arab</div>
                <div class="achievement-year">
                    <i class="bi bi-calendar3"></i> 2024
                </div>
            </div>
        </div>
    </div>

    <!-- KONTAK & LOKASI -->
    <div class="tab-content" id="kontak">
        <div class="contact-section">
            <!-- Google Maps -->
            <div class="map-container">
                <div style="padding: 16px; background: linear-gradient(135deg, #00393a, #005555); border-radius: 12px; margin-bottom: 16px;">
                    <h4 style="color: white; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-geo-alt-fill"></i> Lokasi <?php echo e($madrasah->name); ?>

                    </h4>
                </div>
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <p style="margin: 0; font-size: 14px; color: #64748b;">
                        <i class="bi bi-geo-alt"></i> <?php echo e($madrasah->alamat ?? 'Jl. Raya Km. 5, Yogyakarta'); ?>

                    </p>
                </div>
                <!-- Google Maps Embed Placeholder -->
                <div style="background: linear-gradient(135deg, #e2e8f0, #cbd5e1); height: 300px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b;">
                    <i class="bi bi-map" style="font-size: 48px; margin-bottom: 12px;"></i>
                    <p style="font-size: 14px; font-weight: 600;">Google Maps Embed</p>
                    <p style="font-size: 12px; opacity: 0.7;">(Silakan tambahkan embed code Google Maps)</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-container">
                <h3 class="contact-form-title">
                    <i class="bi bi-send"></i> Kirim Pesan
                </h3>
                <form>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama Anda">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Masukkan email Anda">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <input type="text" class="form-control" placeholder="Masukkan subjek pesan">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" placeholder="Tuliskan pesan Anda..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="bi bi-send-fill"></i> Kirim Pesan
                    </button>
                </form>

                <div class="contact-info">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <?php echo e($ppdbSetting->telepon ?? ($madrasah->telepon ?? '(0274) 123-4567')); ?>

                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppdbSetting && $ppdbSetting->email): ?>
                                <a href="mailto:<?php echo e($ppdbSetting->email); ?>"><?php echo e($ppdbSetting->email); ?></a>
                            <?php elseif($madrasah->email): ?>
                                <a href="mailto:<?php echo e($madrasah->email); ?>"><?php echo e($madrasah->email); ?></a>
                            <?php else: ?>
                                info@sekolah.sch.id
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="contact-info-text">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppdbSetting && $ppdbSetting->website): ?>
                                <a href="<?php echo e($ppdbSetting->website); ?>" target="_blank"><?php echo e($ppdbSetting->website); ?></a>
                            <?php elseif($madrasah->website): ?>
                                <a href="<?php echo e($madrasah->website); ?>" target="_blank"><?php echo e($madrasah->website); ?></a>
                            <?php else: ?>
                                www.sekolah.sch.id
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main Tab Navigation
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active class from all buttons and contents
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Sub Tab Navigation (for About section)
            const subTabBtns = document.querySelectorAll('.sub-tab-btn');
            const subTabContents = document.querySelectorAll('.sub-tab-content');

            subTabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const subTabId = this.getAttribute('data-subtab');

                    // Remove active class from all sub-tab buttons and contents
                    subTabBtns.forEach(b => b.classList.remove('active'));
                    subTabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById(subTabId).classList.add('active');
                });
            });
        });
    </script>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/sekolah-detail.blade.php ENDPATH**/ ?>