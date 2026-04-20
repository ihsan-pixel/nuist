<style>
    .mgmp-page {
        --mgmp-ink: #102d28;
        --mgmp-muted: #6b7b75;
        --mgmp-line: #e5eee9;
        --mgmp-soft: #f5faf7;
        --mgmp-green: #0e8549;
        --mgmp-teal: #004b4c;
        --mgmp-gold: #efaa0c;
    }

    .mgmp-page .card,
    .modal-content {
        border: 0 !important;
        border-radius: 18px !important;
        box-shadow: 0 12px 34px rgba(16, 45, 40, 0.08) !important;
    }

    .modal-header {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.06), rgba(14, 133, 73, 0.08));
        border-bottom: 1px solid var(--mgmp-line);
        border-radius: 18px 18px 0 0;
    }

    .mgmp-page .modal-title,
    .mgmp-page .card-title,
    .mgmp-page h4,
    .mgmp-page h5,
    .mgmp-page h6 {
        color: var(--mgmp-ink);
    }

    .mgmp-hero-strip {
        background:
            radial-gradient(circle at top right, rgba(239, 170, 12, 0.28), transparent 28%),
            linear-gradient(135deg, var(--mgmp-teal), var(--mgmp-green));
        border-radius: 22px;
        box-shadow: 0 18px 42px rgba(0, 75, 76, 0.20);
        color: #fff;
        overflow: hidden;
        padding: 24px;
        position: relative;
    }

    .mgmp-hero-strip::after {
        background: rgba(255,255,255,0.12);
        border-radius: 999px;
        content: "";
        height: 180px;
        position: absolute;
        right: -70px;
        top: -70px;
        width: 180px;
    }

    .mgmp-hero-strip h4,
    .mgmp-hero-strip h5,
    .mgmp-hero-strip p {
        color: #fff;
        position: relative;
        z-index: 1;
    }

    .mgmp-hero-strip .btn {
        position: relative;
        z-index: 1;
    }

    .mgmp-kicker {
        color: rgba(255,255,255,0.75);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .mgmp-panel {
        background: #fff;
        border-radius: 18px;
    }

    .mgmp-stat-card {
        border: 1px solid var(--mgmp-line) !important;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .mgmp-stat-card:hover {
        transform: translateY(-3px);
    }

    .mgmp-icon-bubble {
        align-items: center;
        background: linear-gradient(135deg, rgba(0,75,76,0.10), rgba(14,133,73,0.14));
        border-radius: 16px;
        color: var(--mgmp-green);
        display: inline-flex;
        height: 46px;
        justify-content: center;
        width: 46px;
    }

    .mgmp-page .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    .mgmp-page .btn-primary,
    .mgmp-page .btn-success {
        background: linear-gradient(135deg, var(--mgmp-teal), var(--mgmp-green));
        border: 0;
    }

    .mgmp-page .btn-outline-primary {
        border-color: rgba(14, 133, 73, .35);
        color: var(--mgmp-green);
    }

    .mgmp-page .btn-outline-primary:hover {
        background: var(--mgmp-green);
        border-color: var(--mgmp-green);
        color: #fff;
    }

    .mgmp-page .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .mgmp-page .table thead th {
        background: #f4f8f6 !important;
        border-bottom: 1px solid var(--mgmp-line) !important;
        color: var(--mgmp-ink);
        font-size: 12px;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .mgmp-page .table tbody td {
        border-bottom: 1px solid #eef4f1;
        color: #2d423c;
    }

    .mgmp-empty-state {
        align-items: center;
        color: var(--mgmp-muted);
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 32px 12px;
        text-align: center;
    }

    .mgmp-empty-state i {
        color: rgba(14, 133, 73, .35);
        font-size: 42px;
    }

    .mgmp-chip {
        background: rgba(14, 133, 73, .10);
        border: 1px solid rgba(14, 133, 73, .16);
        border-radius: 999px;
        color: var(--mgmp-green);
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
    }

    .mgmp-page .form-control,
    .mgmp-page .form-select,
    .mgmp-page .select2-container--default .select2-selection--multiple {
        border-color: #dce7e2;
        border-radius: 12px;
    }

    .mgmp-page .alert {
        border: 0;
        border-radius: 14px;
    }

    @media (max-width: 768px) {
        .mgmp-hero-strip {
            padding: 18px;
        }
    }
</style>
