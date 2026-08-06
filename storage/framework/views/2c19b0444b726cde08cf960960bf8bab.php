<style>
    :root {
        --siswa-bg: #edf4ef;
        --siswa-surface: rgba(255, 255, 255, 0.78);
        --siswa-surface-strong: rgba(255, 255, 255, 0.94);
        --siswa-stroke: rgba(14, 73, 68, 0.08);
        --siswa-text: #16302d;
        --siswa-text-soft: #6c7d7b;
        --siswa-primary: #0d6b58;
        --siswa-primary-deep: #0a4f42;
        --siswa-primary-soft: #dbf2e7;
        --siswa-warning: #d4851f;
        --siswa-warning-soft: #fff0db;
        --siswa-danger: #cb4c58;
        --siswa-danger-soft: #ffe4e7;
        --siswa-info: #3975f6;
        --siswa-info-soft: #e6eeff;
        --siswa-shadow: 0 22px 44px rgba(10, 49, 43, 0.10);
        --siswa-shadow-soft: 0 12px 28px rgba(10, 49, 43, 0.08);
    }

    body {
        background:
            radial-gradient(circle at top left, rgba(33, 173, 120, 0.20), transparent 24%),
            radial-gradient(circle at top right, rgba(11, 80, 68, 0.14), transparent 18%),
            linear-gradient(180deg, #f7fbf8 0%, #edf4ef 42%, #e8eef6 100%);
        color: var(--siswa-text);
        font-family: 'Poppins', sans-serif;
    }

    .siswa-shell {
        position: relative;
        max-width: 560px;
        margin: 0 auto;
        padding: 16px 14px 108px;
    }

    .siswa-shell::before {
        content: "";
        position: fixed;
        inset: -12% auto auto -20%;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: rgba(76, 175, 123, 0.10);
        filter: blur(8px);
        pointer-events: none;
        z-index: 0;
    }

    .siswa-shell > * {
        position: relative;
        z-index: 1;
    }

    .siswa-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        padding: 14px 16px;
        background: rgba(255, 255, 255, 0.62);
        border: 1px solid rgba(255, 255, 255, 0.65);
        border-radius: 24px;
        box-shadow: var(--siswa-shadow-soft);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .siswa-user {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .siswa-avatar {
        width: 50px;
        height: 50px;
        border-radius: 18px;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0)),
            linear-gradient(135deg, #10a36e 0%, #0a4f42 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 16px 28px rgba(13, 107, 88, 0.26);
        flex-shrink: 0;
    }

    .siswa-user small {
        display: block;
        color: #617473;
        font-size: 11px;
        letter-spacing: 0.02em;
    }

    .siswa-user strong {
        display: block;
        color: #102b29;
        font-size: 15px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .topbar-caption {
        color: #7b8d8b;
        font-size: 11px;
        margin-top: 2px;
    }

    .hero-card,
    .section-card,
    .mini-card,
    .list-card,
    .chat-card,
    .receipt-card {
        border-radius: 26px;
        border: 1px solid var(--siswa-stroke);
        box-shadow: var(--siswa-shadow-soft);
        overflow: hidden;
    }

    .hero-card {
        position: relative;
        padding: 22px 20px;
        margin-bottom: 16px;
        color: #fff;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 24%),
            linear-gradient(135deg, #093c37 0%, #0d6b58 50%, #17a06c 100%);
    }

    .hero-card::before,
    .hero-card::after {
        content: "";
        position: absolute;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.08);
        transform: rotate(22deg);
    }

    .hero-card::before {
        width: 132px;
        height: 132px;
        right: -48px;
        top: -34px;
    }

    .hero-card::after {
        width: 84px;
        height: 84px;
        right: 44px;
        bottom: -42px;
    }

    .hero-card > * {
        position: relative;
        z-index: 1;
    }

    .hero-card h4 {
        color: #fff;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 700;
        margin: 6px 0 8px;
    }

    .hero-card p,
    .hero-card small {
        color: rgba(255, 255, 255, 0.84);
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        color: rgba(255, 255, 255, 0.94);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .hero-meta,
    .chip-row,
    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .hero-meta {
        margin-top: 18px;
    }

    .hero-chip,
    .page-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 16px;
        padding: 10px 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .hero-chip {
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
    }

    .page-chip {
        background: rgba(13, 107, 88, 0.08);
        color: var(--siswa-primary-deep);
    }

    .hero-stat-grid,
    .summary-grid,
    .menu-grid,
    .detail-grid {
        display: grid;
        gap: 12px;
    }

    .hero-stat-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-top: 18px;
    }

    .hero-stat {
        padding: 14px 12px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .hero-stat strong {
        display: block;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .hero-stat small {
        display: block;
        font-size: 11px;
        line-height: 1.4;
    }

    .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-bottom: 16px;
    }

    .menu-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 16px;
    }

    .mini-card,
    .menu-item,
    .detail-box,
    .list-item,
    .notif-item {
        background: var(--siswa-surface-strong);
    }

    .mini-card,
    .menu-item {
        padding: 16px;
    }

    .mini-card {
        border-radius: 22px;
        position: relative;
    }

    .mini-card::after {
        content: "";
        position: absolute;
        inset: auto 14px 0 auto;
        width: 44px;
        height: 44px;
        border-radius: 14px 14px 0 0;
        background: linear-gradient(180deg, rgba(13, 107, 88, 0.14), rgba(13, 107, 88, 0));
    }

    .mini-card h3,
    .mini-card h4 {
        margin: 8px 0 6px;
        color: #112d2b;
        font-size: 22px;
        font-weight: 700;
    }

    .mini-card small,
    .section-subtitle,
    .text-soft {
        color: var(--siswa-text-soft);
    }

    .menu-item {
        display: block;
        text-decoration: none;
        color: inherit;
        text-align: left;
        border-radius: 22px;
        box-shadow: 0 10px 18px rgba(17, 45, 43, 0.05);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .menu-item:active {
        transform: translateY(1px);
    }

    .menu-item i {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        margin-bottom: 12px;
        background: linear-gradient(135deg, rgba(23, 160, 108, 0.14), rgba(13, 107, 88, 0.22));
        color: var(--siswa-primary);
        font-size: 22px;
    }

    .menu-item span {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #193433;
        margin-bottom: 4px;
    }

    .menu-item small {
        display: block;
        font-size: 10px;
        color: #7d8d8b;
        line-height: 1.45;
    }

    .section-card,
    .list-card,
    .chat-card,
    .receipt-card {
        padding: 18px;
        margin-bottom: 16px;
        background: var(--siswa-surface);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .section-title {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .section-title h5 {
        margin: 0;
        font-size: 16px;
        line-height: 1.35;
        font-weight: 700;
        color: #112d2b;
    }

    .section-title p {
        margin: 4px 0 0;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pill-success {
        background: rgba(23, 160, 108, 0.14);
        color: #0d7f54;
    }

    .pill-warning {
        background: rgba(212, 133, 31, 0.14);
        color: var(--siswa-warning);
    }

    .pill-danger {
        background: rgba(203, 76, 88, 0.14);
        color: var(--siswa-danger);
    }

    .pill-info {
        background: rgba(57, 117, 246, 0.12);
        color: var(--siswa-info);
    }

    .list-item {
        padding: 15px;
        border-radius: 20px;
        border: 1px solid rgba(22, 48, 45, 0.06);
        box-shadow: 0 10px 18px rgba(17, 45, 43, 0.04);
        margin-bottom: 12px;
    }

    .list-item:last-child,
    .notif-item:last-child {
        margin-bottom: 0;
    }

    .list-item h6,
    .notif-item h6 {
        margin: 0 0 5px;
        font-size: 14px;
        line-height: 1.45;
        font-weight: 700;
        color: #112d2b;
    }

    .list-item p,
    .list-item small,
    .notif-item p,
    .notif-item small {
        margin: 0;
        color: #6e7f7c;
        font-size: 12px;
        line-height: 1.55;
    }

    .list-kicker {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #8b9a98;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .meta-row,
    .info-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
        font-size: 12px;
        flex-wrap: wrap;
    }

    .info-line {
        color: #617473;
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
        background: linear-gradient(90deg, #0d6b58 0%, #24bf7d 100%);
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
        color: #5f7270;
    }

    .bar-track {
        flex: 1;
        height: 10px;
        background: #edf2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .bar-fill-success {
        height: 100%;
        background: linear-gradient(90deg, #0d6b58, #24bf7d);
    }

    .bar-fill-warning {
        height: 100%;
        background: linear-gradient(90deg, #d4851f, #f7b955);
    }

    .cta-btn,
    .ghost-btn {
        width: 100%;
        border: 0;
        border-radius: 18px;
        padding: 13px 16px;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }

    .cta-btn:active,
    .ghost-btn:active {
        transform: translateY(1px);
    }

    .cta-btn {
        background: linear-gradient(135deg, #0d6b58 0%, #0a4f42 100%);
        color: #fff;
        box-shadow: 0 16px 28px rgba(13, 107, 88, 0.24);
    }

    .ghost-btn {
        background: rgba(13, 107, 88, 0.08);
        color: #173533;
    }

    .detail-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-box {
        padding: 14px;
        border-radius: 18px;
        border: 1px solid rgba(17, 45, 43, 0.05);
        min-height: 86px;
    }

    .detail-box small {
        display: block;
        color: #758583;
        margin-bottom: 7px;
        font-size: 11px;
    }

    .detail-box strong {
        color: #112d2b;
        font-size: 14px;
        line-height: 1.45;
        word-break: break-word;
    }

    .receipt-card {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(242, 250, 246, 0.96));
        border-style: dashed;
        border-color: rgba(13, 107, 88, 0.20);
    }

    .note-card {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(13, 107, 88, 0.06);
        color: #4f6260;
    }

    .note-card.warning {
        background: var(--siswa-warning-soft);
        color: #7d5d22;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 20px;
        border: 1px solid rgba(17, 45, 43, 0.05);
        margin-bottom: 12px;
    }

    .notif-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(23, 160, 108, 0.14), rgba(13, 107, 88, 0.18));
        color: var(--siswa-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .profile-card {
        text-align: center;
        padding: 24px 18px;
    }

    .profile-card .siswa-avatar {
        margin: 0 auto 14px;
        width: 72px;
        height: 72px;
        font-size: 28px;
        border-radius: 24px;
    }

    .profile-meta {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .chat-window {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 14px;
    }

    .bubble {
        max-width: 84%;
        padding: 12px 14px;
        border-radius: 20px;
        margin-bottom: 10px;
        font-size: 13px;
        line-height: 1.52;
        box-shadow: 0 10px 18px rgba(17, 45, 43, 0.05);
    }

    .bubble.me {
        margin-left: auto;
        background: linear-gradient(135deg, #0d6b58 0%, #0a4f42 100%);
        color: #fff;
        border-bottom-right-radius: 8px;
    }

    .bubble.them {
        background: #f4f7fa;
        color: #1b3634;
        border-bottom-left-radius: 8px;
    }

    .composer-card {
        padding: 14px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.76);
        border: 1px solid rgba(17, 45, 43, 0.06);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 10px;
        align-items: end;
    }

    .filter-form .cta-btn {
        min-height: 48px;
    }

    .empty-state {
        padding: 18px 16px;
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(243, 248, 251, 0.92));
        border: 1px dashed rgba(17, 45, 43, 0.12);
        text-align: center;
    }

    .empty-state i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        border-radius: 18px;
        background: rgba(13, 107, 88, 0.10);
        color: var(--siswa-primary);
        font-size: 24px;
        margin-bottom: 12px;
    }

    .empty-state h6 {
        margin: 0 0 6px;
        color: #112d2b;
        font-size: 14px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0;
        color: #6f807d;
        font-size: 12px;
        line-height: 1.55;
    }

    .form-control,
    .form-select,
    select.form-control,
    textarea.form-control {
        border-radius: 16px;
        border: 1px solid rgba(17, 45, 43, 0.10);
        padding: 12px 14px;
        background: rgba(255, 255, 255, 0.92);
        color: #173533;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus,
    select.form-control:focus,
    textarea.form-control:focus {
        border-color: rgba(13, 107, 88, 0.28);
        box-shadow: 0 0 0 4px rgba(13, 107, 88, 0.10);
    }

    .bottom-nav-siswa {
        position: fixed;
        left: 12px;
        right: 12px;
        bottom: 12px;
        padding: 10px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.76);
        box-shadow: 0 20px 34px rgba(16, 43, 41, 0.14);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 6px;
        z-index: 1080;
    }

    .bottom-nav-siswa a {
        text-decoration: none;
        color: #70807e;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        padding: 8px 4px;
        border-radius: 18px;
        transition: background 0.18s ease, color 0.18s ease, transform 0.18s ease;
    }

    .bottom-nav-siswa a:active {
        transform: translateY(1px);
    }

    .bottom-nav-siswa a i {
        display: block;
        font-size: 18px;
        margin-bottom: 3px;
    }

    .bottom-nav-siswa a.active {
        background: linear-gradient(135deg, #0d6b58 0%, #0a4f42 100%);
        color: #fff;
        box-shadow: 0 10px 18px rgba(13, 107, 88, 0.24);
    }

    .topbar-action {
        position: relative;
        width: 48px;
        min-width: 48px;
        height: 48px;
        padding: 0;
        border-radius: 18px;
    }

    .notif-count {
        position: absolute;
        top: -3px;
        right: -2px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        border-radius: 999px;
        background: linear-gradient(135deg, #ff7a4f 0%, #ff4f72 100%);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 16px rgba(255, 79, 114, 0.24);
    }

    .auth-loader {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(22, 33, 32, 0.34);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s ease, visibility 0.22s ease;
        z-index: 9999;
    }

    .auth-loader.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .auth-loader-card {
        min-width: 220px;
        max-width: 280px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 24px 60px rgba(0, 75, 72, 0.16);
        padding: 22px 20px 18px;
        text-align: center;
    }

    .auth-loader-mark {
        width: 68px;
        height: 68px;
        margin: 0 auto 14px;
        position: relative;
        display: grid;
        place-items: center;
    }

    .auth-loader-ring,
    .auth-loader-ring::before,
    .auth-loader-ring::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
    }

    .auth-loader-ring {
        border: 3px solid rgba(0, 75, 72, 0.16);
        border-top-color: var(--siswa-primary);
        animation: auth-loader-spin 0.9s linear infinite;
    }

    .auth-loader-ring::before {
        inset: 8px;
        border: 3px solid rgba(0, 75, 72, 0.12);
        border-bottom-color: var(--siswa-primary-deep);
        animation: auth-loader-spin 1.25s linear infinite reverse;
    }

    .auth-loader-ring::after {
        inset: 18px;
        background: linear-gradient(135deg, rgba(13, 107, 88, 0.14), rgba(10, 79, 66, 0.22));
        box-shadow: inset 0 0 0 1px rgba(13, 107, 88, 0.08);
    }

    .auth-loader-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: #16302d;
    }

    .auth-loader-text {
        margin: 6px 0 0;
        font-size: 0.74rem;
        line-height: 1.5;
        color: #6c7d7b;
    }

    @keyframes auth-loader-spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 420px) {
        .siswa-shell {
            padding-left: 12px;
            padding-right: 12px;
        }

        .hero-card,
        .section-card,
        .list-card,
        .chat-card,
        .receipt-card {
            border-radius: 24px;
        }

        .hero-stat-grid,
        .summary-grid,
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .menu-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/partials/styles.blade.php ENDPATH**/ ?>