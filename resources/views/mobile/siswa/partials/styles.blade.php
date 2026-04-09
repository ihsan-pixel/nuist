<style>
    body {
        background:
            radial-gradient(circle at top left, rgba(14, 133, 73, 0.16), transparent 28%),
            linear-gradient(180deg, #f5fbf7 0%, #f7f8fc 40%, #eef2f7 100%);
        font-family: 'Poppins', sans-serif;
        color: #183153;
    }

    .siswa-shell {
        max-width: 520px;
        margin: 0 auto;
        padding: 14px 14px 104px;
    }

    .siswa-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        gap: 12px;
    }

    .siswa-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .siswa-avatar {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 10px 24px rgba(0, 75, 76, 0.22);
    }

    .siswa-user small {
        display: block;
        color: #5d6b82;
        font-size: 11px;
    }

    .siswa-user strong {
        display: block;
        font-size: 15px;
        color: #12263f;
    }

    .hero-card,
    .section-card,
    .mini-card,
    .list-card,
    .chat-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 22px;
        box-shadow: 0 14px 38px rgba(18, 38, 63, 0.08);
    }

    .hero-card {
        background: linear-gradient(135deg, #0f5f57 0%, #0e8549 100%);
        color: #fff;
        padding: 20px;
        margin-bottom: 16px;
        overflow: hidden;
        position: relative;
    }

    .hero-card::after {
        content: "";
        position: absolute;
        right: -30px;
        top: -20px;
        width: 110px;
        height: 110px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.12);
        transform: rotate(18deg);
    }

    .hero-card h4 {
        color: #fff;
        margin-bottom: 6px;
        font-size: 20px;
        font-weight: 700;
    }

    .hero-card p,
    .hero-card small {
        color: rgba(255, 255, 255, 0.84);
    }

    .hero-stat-grid,
    .summary-grid,
    .menu-grid {
        display: grid;
        gap: 12px;
    }

    .hero-stat-grid {
        grid-template-columns: repeat(3, 1fr);
        margin-top: 18px;
    }

    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
        margin-bottom: 16px;
    }

    .menu-grid {
        grid-template-columns: repeat(3, 1fr);
        margin-bottom: 16px;
    }

    .mini-card,
    .menu-item {
        padding: 14px;
    }

    .mini-card h3,
    .mini-card h4 {
        margin: 0;
        color: #12263f;
        font-size: 20px;
        font-weight: 700;
    }

    .mini-card small,
    .section-subtitle,
    .text-soft {
        color: #6b7b93;
    }

    .menu-item {
        text-decoration: none;
        display: block;
        color: inherit;
        text-align: center;
        border-radius: 18px;
        background: rgba(255,255,255,0.85);
    }

    .menu-item i {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        margin-bottom: 10px;
        background: linear-gradient(135deg, rgba(14, 133, 73, 0.12), rgba(0, 75, 76, 0.18));
        color: #0e8549;
        font-size: 22px;
    }

    .menu-item span {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #21324d;
    }

    .section-card,
    .list-card,
    .chat-card {
        padding: 16px;
        margin-bottom: 16px;
    }

    .section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .section-title h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #12263f;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .pill-success { background: rgba(14, 133, 73, 0.14); color: #0e8549; }
    .pill-warning { background: rgba(255, 159, 67, 0.16); color: #c96b00; }
    .pill-danger { background: rgba(220, 53, 69, 0.14); color: #c62839; }
    .pill-info { background: rgba(13, 110, 253, 0.12); color: #2f6fed; }

    .list-item {
        padding: 14px;
        border-radius: 18px;
        background: #f8fafc;
        margin-bottom: 12px;
    }

    .list-item:last-child {
        margin-bottom: 0;
    }

    .list-item h6 {
        margin: 0 0 4px;
        font-size: 14px;
        font-weight: 700;
        color: #12263f;
    }

    .list-item p,
    .list-item small {
        margin: 0;
        color: #6b7b93;
        font-size: 12px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
        font-size: 12px;
    }

    .chart-track {
        height: 12px;
        border-radius: 999px;
        background: #e8edf5;
        overflow: hidden;
        margin-top: 12px;
    }

    .chart-fill {
        height: 100%;
        background: linear-gradient(90deg, #0e8549 0%, #1fbf75 100%);
        border-radius: 999px;
    }

    .bar-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
    }

    .bar-label {
        width: 88px;
        font-size: 12px;
        color: #5d6b82;
    }

    .bar-track {
        flex: 1;
        height: 10px;
        background: #eef2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .bar-fill-success {
        height: 100%;
        background: linear-gradient(90deg, #0e8549, #2fc27f);
    }

    .bar-fill-warning {
        height: 100%;
        background: linear-gradient(90deg, #f59f00, #ffbf69);
    }

    .cta-btn,
    .ghost-btn {
        width: 100%;
        border: 0;
        border-radius: 16px;
        padding: 13px 16px;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .cta-btn {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: #fff;
        box-shadow: 0 12px 24px rgba(0, 75, 76, 0.22);
    }

    .ghost-btn {
        background: #eff4f9;
        color: #21324d;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .detail-box {
        padding: 12px;
        border-radius: 16px;
        background: #f8fafc;
    }

    .detail-box small {
        display: block;
        color: #6b7b93;
        margin-bottom: 6px;
        font-size: 11px;
    }

    .detail-box strong {
        color: #12263f;
        font-size: 14px;
    }

    .receipt-card {
        border: 1px dashed rgba(14, 133, 73, 0.28);
        background: linear-gradient(180deg, #ffffff 0%, #f4fbf7 100%);
        border-radius: 22px;
        padding: 18px;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 12px;
    }

    .notif-item:last-child {
        margin-bottom: 0;
    }

    .notif-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(14,133,73,0.14), rgba(0,75,76,0.18));
        color: #0e8549;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .profile-card {
        text-align: center;
        padding: 22px 18px;
    }

    .profile-card .siswa-avatar {
        margin: 0 auto 12px;
        width: 64px;
        height: 64px;
        font-size: 24px;
        border-radius: 20px;
    }

    .chat-window {
        max-height: 360px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 14px;
    }

    .bubble {
        max-width: 82%;
        padding: 12px 14px;
        border-radius: 18px;
        margin-bottom: 10px;
        font-size: 13px;
        line-height: 1.45;
    }

    .bubble.me {
        margin-left: auto;
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: #fff;
        border-bottom-right-radius: 6px;
    }

    .bubble.them {
        background: #f1f5f9;
        color: #21324d;
        border-bottom-left-radius: 6px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 10px;
    }

    .bottom-nav-siswa {
        position: fixed;
        left: 12px;
        right: 12px;
        bottom: 12px;
        padding: 10px;
        border-radius: 22px;
        background: rgba(255,255,255,0.96);
        box-shadow: 0 16px 36px rgba(18, 38, 63, 0.14);
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
        z-index: 1080;
    }

    .bottom-nav-siswa a {
        text-decoration: none;
        color: #6b7b93;
        font-size: 10px;
        font-weight: 600;
        text-align: center;
        padding: 8px 4px;
        border-radius: 16px;
    }

    .bottom-nav-siswa a i {
        display: block;
        font-size: 18px;
        margin-bottom: 3px;
    }

    .bottom-nav-siswa a.active {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: #fff;
    }

    @media (max-width: 420px) {
        .hero-stat-grid,
        .menu-grid,
        .summary-grid,
        .detail-grid {
            grid-template-columns: 1fr 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>
