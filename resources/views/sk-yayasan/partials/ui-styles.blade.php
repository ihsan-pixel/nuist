<style>
    .sky-page {
        --sky-ink: #102d28;
        --sky-muted: #6b7b75;
        --sky-line: #e5eee9;
        --sky-soft: #f5faf7;
        --sky-green: #0e8549;
        --sky-teal: #004b4c;
        --sky-gold: #efaa0c;
    }

    .sky-page .card,
    .sky-page .accordion-item,
    .sky-page .modal-content {
        border: 0 !important;
        border-radius: 18px !important;
        box-shadow: 0 12px 34px rgba(16, 45, 40, 0.08) !important;
    }

    .sky-page .modal-header,
    .sky-page .card-header {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.05), rgba(14, 133, 73, 0.08));
        border-bottom: 1px solid var(--sky-line);
    }

    .sky-page .card-header:first-child {
        border-radius: 18px 18px 0 0 !important;
    }

    .sky-page h4,
    .sky-page h5,
    .sky-page h6,
    .sky-page .card-title,
    .sky-page .modal-title {
        color: var(--sky-ink);
    }

    .sky-hero-strip {
        background:
            radial-gradient(circle at top right, rgba(239, 170, 12, 0.28), transparent 28%),
            linear-gradient(135deg, var(--sky-teal), var(--sky-green));
        border-radius: 22px;
        box-shadow: 0 18px 42px rgba(0, 75, 76, 0.20);
        color: #fff;
        overflow: hidden;
        padding: 24px;
        position: relative;
    }

    .sky-hero-strip::after {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 999px;
        content: "";
        height: 180px;
        position: absolute;
        right: -70px;
        top: -70px;
        width: 180px;
    }

    .sky-hero-strip > * {
        position: relative;
        z-index: 1;
    }

    .sky-hero-strip h4,
    .sky-hero-strip h5,
    .sky-hero-strip p {
        color: #fff;
    }

    .sky-kicker {
        color: rgba(255, 255, 255, 0.75);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .sky-stat-card {
        border: 1px solid var(--sky-line) !important;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .sky-stat-card:hover,
    .sky-soft-card:hover,
    .sky-document-card:hover {
        transform: translateY(-3px);
    }

    .sky-page .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    .sky-page .btn-primary,
    .sky-page .btn-success {
        background: linear-gradient(135deg, var(--sky-teal), var(--sky-green));
        border: 0;
    }

    .sky-page .btn-outline-primary {
        border-color: rgba(14, 133, 73, .35);
        color: var(--sky-green);
    }

    .sky-page .btn-outline-primary:hover {
        background: var(--sky-green);
        border-color: var(--sky-green);
        color: #fff;
    }

    .sky-page .form-control,
    .sky-page .form-select {
        border-color: #dce7e2;
        border-radius: 12px;
        min-height: 44px;
    }

    .sky-page textarea.form-control {
        min-height: auto;
    }

    .sky-page .table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .sky-page .table thead th {
        background: #f4f8f6 !important;
        border-bottom: 1px solid var(--sky-line) !important;
        color: var(--sky-ink);
        font-size: 12px;
        letter-spacing: .03em;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .sky-page .table tbody td {
        border-bottom: 1px solid #eef4f1;
        color: #2d423c;
        vertical-align: middle;
    }

    .sky-chip {
        background: rgba(14, 133, 73, .10);
        border: 1px solid rgba(14, 133, 73, .16);
        border-radius: 999px;
        color: var(--sky-green);
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
    }

    .sky-panel-label {
        color: var(--sky-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .sky-summary-stack {
        display: grid;
        gap: 10px;
    }

    .sky-summary-row {
        align-items: center;
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        display: flex;
        justify-content: space-between;
        padding: 12px 14px;
    }

    .sky-soft-card {
        background: linear-gradient(180deg, #ffffff 0%, #f9fcfb 100%);
        border: 1px solid var(--sky-line);
        border-radius: 18px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .sky-document-card {
        background: #fff;
        border: 1px solid var(--sky-line);
        border-radius: 18px;
        padding: 16px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .sky-document-meta {
        color: var(--sky-muted);
        font-size: 12px;
    }

    .sky-empty-state {
        align-items: center;
        color: var(--sky-muted);
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 32px 12px;
        text-align: center;
    }

    .sky-empty-state i {
        color: rgba(14, 133, 73, .35);
        font-size: 42px;
    }

    .sky-page .accordion-button {
        background: #fff;
        border-radius: 18px !important;
        box-shadow: none !important;
        color: var(--sky-ink);
        font-weight: 600;
        padding: 18px 20px;
    }

    .sky-page .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.03), rgba(14, 133, 73, 0.08));
        color: var(--sky-ink);
    }

    .sky-page .accordion-body {
        border-top: 1px solid var(--sky-line);
        padding: 20px;
    }

    .sky-page .alert {
        border: 0;
        border-radius: 14px;
    }

    .sky-page .badge {
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        padding: 7px 10px;
    }

    .sky-compact-table thead th,
    .sky-compact-table tbody td {
        font-size: 12px;
        padding: 10px 12px;
        white-space: nowrap;
    }

    .sky-compact-table tbody td.wrap {
        white-space: normal;
    }

    .sky-modal-table-wrap {
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        max-height: 420px;
        overflow: auto;
    }

    .sky-mini-stat {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .sky-mini-stat .value {
        color: var(--sky-ink);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.1;
    }

    .sky-mini-stat .label {
        color: var(--sky-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .sky-file-meta {
        color: var(--sky-muted);
        font-size: 12px;
    }

    .sky-metric {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        padding: 14px;
        text-align: center;
    }

    .sky-metric .value {
        color: var(--sky-ink);
        font-size: 26px;
        font-weight: 700;
        line-height: 1.1;
    }

    .sky-metric .label {
        color: var(--sky-muted);
        font-size: 12px;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    @media (max-width: 768px) {
        .sky-hero-strip {
            padding: 18px;
        }
    }
</style>
