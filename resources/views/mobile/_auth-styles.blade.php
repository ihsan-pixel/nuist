<style>
    :root {
        --auth-bg-start: #0d8e89;
        --auth-bg-end: #08756f;
        --text-main: #1f4f4c;
        --text-muted: #6d7f7d;
        --border-soft: #004b48;
        --accent-main: #004b48;
        --accent-soft: #2b7a76;
        --accent-deep: #003634;
        --accent-faint: #e5f2f1;
    }

    html,
    body {
        min-height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: var(--auth-bg-start);
    }

    .mobile-auth-page {
        min-height: 100vh;
        width: 100%;
        background: linear-gradient(180deg, var(--auth-bg-start) 0%, var(--auth-bg-end) 100%);
    }

    .welcome-card,
    .register-card,
    .forgot-card,
    .reset-card {
        width: 100%;
        max-width: 100%;
        min-height: 100vh;
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .welcome-card::before,
    .register-card::before,
    .forgot-card::before,
    .reset-card::before {
        content: "";
        position: absolute;
        inset: 0;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        pointer-events: none;
    }

    .card-top {
        position: relative;
        height: 132px;
        background: linear-gradient(180deg, #f4c272 0%, #f6a92b 100%);
        overflow: hidden;
    }

    .card-top::before,
    .card-top::after {
        content: "";
        position: absolute;
        left: -8%;
        right: -8%;
        border-radius: 50%;
    }

    .card-top::before {
        bottom: 22px;
        height: 78px;
        background: rgba(255, 255, 255, 0.45);
    }

    .card-top::after {
        bottom: -18px;
        height: 92px;
        background: #fff;
    }

    .brand-pill {
        position: absolute;
        top: 16px;
        left: 50%;
        transform: translateX(-50%);
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.92);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 24px rgba(0, 75, 72, 0.18);
    }

    .brand-pill img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .card-body {
        padding: 14px 20px 28px;
        text-align: center;
    }

    .welcome-title {
        margin: 0;
        font-size: 2rem;
        line-height: 1.1;
        font-weight: 700;
        color: var(--accent-main);
        text-shadow: 0 3px 10px rgba(0, 75, 72, 0.16);
    }

    .welcome-subtitle {
        margin: 10px 0 0;
        color: var(--text-muted);
        font-size: 0.96rem;
        font-weight: 500;
    }

    .hero-illustration {
        margin: 22px auto 16px;
        width: min(100%, 210px);
        display: block;
        filter: drop-shadow(0 12px 20px rgba(0, 75, 72, 0.14));
    }

    .status-stack {
        display: grid;
        gap: 10px;
        margin-bottom: 12px;
        text-align: left;
    }

    .status-alert {
        border-radius: 16px;
        padding: 12px 14px;
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .status-alert.success {
        background: #e8f8ee;
        color: #1d6b40;
        border: 1px solid #bfe8cb;
    }

    .status-alert.error {
        background: #fdecec;
        color: #a33b3b;
        border: 1px solid #f7c4c4;
    }

    .register-form,
    .forgot-form,
    .reset-form {
        text-align: left;
    }

    .action-stack {
        display: grid;
        gap: 12px;
        margin-top: 8px;
    }

    .action-btn {
        width: 100%;
        border-radius: 14px;
        height: 48px;
        border: 0;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .action-btn-primary {
        background: linear-gradient(180deg, var(--accent-soft) 0%, var(--accent-main) 100%);
        color: #fff;
        box-shadow: 0 14px 28px rgba(0, 75, 72, 0.22);
    }

    .action-btn-secondary {
        background: #fff;
        color: var(--accent-deep);
        border: 2px solid var(--border-soft);
    }

    .login-panel {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #d7e8e7;
        text-align: left;
        display: none;
    }

    .welcome-card.is-open .login-panel {
        display: block;
        animation: revealPanel 0.25s ease;
    }

    @keyframes revealPanel {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .panel-title {
        margin: 0 0 12px;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-main);
        text-align: center;
    }

    .input-group {
        margin-bottom: 12px;
    }

    .input-label {
        display: block;
        margin-bottom: 6px;
        color: var(--text-main);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .input-control,
    .select-control {
        width: 100%;
        min-height: 46px;
        border-radius: 14px;
        border: 1px solid #cfe3e1;
        background: #fbfdfd;
        padding: 10px 14px;
        color: #244744;
        font-size: 0.84rem;
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .input-control:focus,
    .select-control:focus {
        border-color: var(--accent-main);
        box-shadow: 0 0 0 4px rgba(0, 75, 72, 0.16);
    }

    .password-wrap {
        position: relative;
    }

    .password-wrap .input-control {
        padding-right: 48px;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: var(--accent-main);
        font-size: 0.92rem;
        cursor: pointer;
    }

    .submit-btn,
    .secondary-btn {
        width: 100%;
        min-height: 46px;
        border: 0;
        border-radius: 14px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .submit-btn {
        margin-top: 8px;
        background: linear-gradient(180deg, var(--accent-soft) 0%, var(--accent-deep) 100%);
        color: #fff;
        box-shadow: 0 12px 24px rgba(0, 75, 72, 0.24);
    }

    .secondary-btn {
        margin-top: 10px;
        background: #fff;
        color: var(--accent-deep);
        border: 2px solid var(--border-soft);
    }

    .field-error {
        margin-top: 6px;
        color: #c44f4f;
        font-size: 0.72rem;
    }

    .panel-footer {
        margin-top: 12px;
        text-align: center;
        font-size: 0.76rem;
        color: var(--text-muted);
    }

    .panel-footer a {
        color: var(--accent-deep);
        font-weight: 600;
        text-decoration: none;
    }

    .role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
        align-items: stretch;
    }

    .role-option {
        position: relative;
    }

    .role-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .role-label {
        min-height: 38px;
        border-radius: 12px;
        border: 1px solid #cfe3e1;
        background: #fbfdfd;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        text-align: center;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        line-height: 1.2;
        letter-spacing: 0.01em;
    }

    .role-option input:checked + .role-label {
        background: linear-gradient(180deg, var(--accent-soft) 0%, var(--accent-main) 100%);
        border-color: var(--accent-main);
        color: #fff;
        box-shadow: 0 12px 24px rgba(0, 75, 72, 0.2);
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
        border-top-color: var(--accent-main);
        animation: auth-loader-spin 0.9s linear infinite;
    }

    .auth-loader-ring::before {
        inset: 8px;
        border: 3px solid rgba(0, 75, 72, 0.12);
        border-bottom-color: var(--accent-deep);
        animation: auth-loader-spin-reverse 1.3s linear infinite;
    }

    .auth-loader-ring::after {
        inset: 18px;
        background: radial-gradient(circle at 30% 30%, var(--accent-soft), var(--accent-main));
        box-shadow: 0 8px 16px rgba(0, 75, 72, 0.22);
    }

    .auth-loader-title {
        margin: 0;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .auth-loader-text {
        margin: 6px 0 0;
        font-size: 0.74rem;
        line-height: 1.45;
        color: var(--text-muted);
    }

    @keyframes auth-loader-spin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes auth-loader-spin-reverse {
        to {
            transform: rotate(-360deg);
        }
    }

    [hidden] {
        display: none !important;
    }

    @media (max-width: 420px) {
        .card-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .role-label {
            min-height: 36px;
            font-size: 0.66rem;
            padding: 5px 6px;
        }
    }
</style>
