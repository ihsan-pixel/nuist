<style>
    :root {
        --auth-bg-start: #0d8e89;
        --auth-bg-end: #08756f;
        --text-main: #1f4f4c;
        --text-muted: #6d7f7d;
        --border-soft: #f6a92b;
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
        background: linear-gradient(180deg, #8fe6e0 0%, #78d7d1 100%);
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
        box-shadow: 0 10px 24px rgba(0, 98, 93, 0.16);
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
        color: #67d3cc;
        text-shadow: 0 3px 10px rgba(103, 211, 204, 0.2);
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
        filter: drop-shadow(0 12px 20px rgba(127, 224, 219, 0.22));
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
        font-size: 0.88rem;
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
        font-size: 1rem;
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
        background: linear-gradient(180deg, #7dded8 0%, #61cbc4 100%);
        color: #fff;
        box-shadow: 0 14px 28px rgba(97, 203, 196, 0.28);
    }

    .action-btn-secondary {
        background: #fff;
        color: #78bdb8;
        border: 2px solid var(--border-soft);
    }

    .login-panel {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #edf6f5;
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
        font-size: 0.96rem;
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
        font-size: 0.88rem;
        font-weight: 600;
    }

    .input-control,
    .select-control {
        width: 100%;
        min-height: 46px;
        border-radius: 14px;
        border: 1px solid #d8ece9;
        background: #f8fcfb;
        padding: 10px 14px;
        color: #244744;
        font-size: 0.94rem;
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .input-control:focus,
    .select-control:focus {
        border-color: #72d5cf;
        box-shadow: 0 0 0 4px rgba(114, 213, 207, 0.16);
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
        color: #6ca7a2;
        font-size: 1.05rem;
        cursor: pointer;
    }

    .submit-btn,
    .secondary-btn {
        width: 100%;
        min-height: 46px;
        border: 0;
        border-radius: 14px;
        font-size: 0.96rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .submit-btn {
        margin-top: 8px;
        background: linear-gradient(180deg, #169892 0%, #0b7d77 100%);
        color: #fff;
        box-shadow: 0 12px 24px rgba(11, 125, 119, 0.24);
    }

    .secondary-btn {
        margin-top: 10px;
        background: #fff;
        color: #78bdb8;
        border: 2px solid var(--border-soft);
    }

    .field-error {
        margin-top: 6px;
        color: #c44f4f;
        font-size: 0.78rem;
    }

    .panel-footer {
        margin-top: 12px;
        text-align: center;
        font-size: 0.84rem;
        color: var(--text-muted);
    }

    .panel-footer a {
        color: #15918b;
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
        border: 1px solid #d8ece9;
        background: #f8fcfb;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        text-align: center;
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        line-height: 1.2;
        letter-spacing: 0.01em;
    }

    .role-option input:checked + .role-label {
        background: linear-gradient(180deg, #7dded8 0%, #61cbc4 100%);
        border-color: #61cbc4;
        color: #fff;
        box-shadow: 0 12px 24px rgba(97, 203, 196, 0.24);
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
            font-size: 0.72rem;
            padding: 5px 6px;
        }
    }
</style>
