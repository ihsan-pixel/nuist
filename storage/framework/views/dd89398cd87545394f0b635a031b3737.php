<style>
    :root {
        --auth-bg: #f7f9fc;
        --auth-bg-alt: #eef3f1;
        --text-main: #172a24;
        --text-muted: #6d7f7d;
        --border-soft: #dce7e3;
        --accent-main: #00745a;
        --accent-soft: #009071;
        --accent-deep: #00553f;
        --accent-faint: #eef7f4;
    }

    html,
    body {
        min-height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: var(--auth-bg);
    }

    .mobile-auth-page {
        min-height: 100vh;
        width: 100%;
        background:
            radial-gradient(circle at 18% 16%, rgba(0, 116, 90, 0.22), transparent 24%),
            radial-gradient(circle at 82% 14%, rgba(15, 118, 110, 0.14), transparent 20%),
            radial-gradient(circle at 84% 86%, rgba(245, 158, 11, 0.16), transparent 22%),
            linear-gradient(180deg, #eef5f2 0%, #f7faf8 38%, #ffffff 100%);
        position: relative;
    }

    .mobile-auth-page::before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(23, 42, 36, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(23, 42, 36, 0.03) 1px, transparent 1px);
        background-size: 28px 28px;
        mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.34), transparent 76%);
        opacity: 0.85;
    }

    .login-shell {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        padding: 22px 16px 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 14px;
    }

    .login-backdrop {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .backdrop-orb {
        position: absolute;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(0, 116, 90, 0.24) 0%, rgba(0, 116, 90, 0.09) 44%, transparent 76%);
        filter: blur(0px);
    }

    .backdrop-orb-left {
        top: -54px;
        left: -60px;
        width: 220px;
        height: 220px;
    }

    .backdrop-orb-right {
        right: -66px;
        bottom: -70px;
        width: 240px;
        height: 240px;
    }

    .welcome-card,
    .register-card,
    .forgot-card,
    .reset-card {
        width: 100%;
        max-width: 372px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(220, 231, 227, 0.84);
        border-radius: 28px;
        overflow: hidden;
        position: relative;
        box-shadow:
            0 22px 56px rgba(23, 42, 36, 0.14),
            0 6px 18px rgba(23, 42, 36, 0.06);
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
        display: none;
    }

    .auth-header {
        width: 100%;
        max-width: 372px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .card-body {
        padding: 18px 18px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .brand-card {
        width: 132px;
        height: 96px;
        padding: 14px 16px;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 22px rgba(0, 85, 63, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .brand-card-inline {
        width: 168px;
        height: 82px;
        padding: 8px 14px;
    }

    .brand-card img {
        width: 100%;
        height: auto;
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
    }

    .welcome-title {
        margin: 0;
        font-size: 1.28rem;
        line-height: 1.15;
        font-weight: 600;
        color: var(--accent-main);
        letter-spacing: -0.03em;
    }

    .welcome-subtitle {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 400;
        line-height: 1.45;
        max-width: 26ch;
    }

    .status-stack {
        display: grid;
        gap: 10px;
        margin: 14px 0 0;
        width: 100%;
        text-align: left;
    }

    .status-alert {
        border-radius: 16px;
        padding: 12px 14px;
        font-size: 0.82rem;
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
    .reset-form,
    .login-form {
        text-align: left;
        width: 100%;
    }

    .login-panel {
        margin-top: 18px;
        width: 100%;
        text-align: left;
    }

    .auth-field-group {
        margin-bottom: 12px;
        width: 100%;
    }

    .input-label {
        display: block;
        margin-bottom: 6px;
        color: var(--text-main);
        font-size: 0.72rem;
        font-weight: 700;
    }

    .field-shell {
        position: relative;
        display: flex;
        align-items: center;
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        background: #ffffff;
        min-height: 48px;
        overflow: hidden;
    }

    .field-shell:focus-within {
        border-color: var(--accent-main);
        box-shadow: 0 0 0 4px rgba(0, 116, 90, 0.12);
    }

    .field-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6e807d;
        flex: 0 0 44px;
    }

    .field-icon svg,
    .toggle-password svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }

    .field-shell-password {
        padding-right: 8px;
    }

    .input-control,
    .select-control {
        width: 100%;
        min-height: 48px;
        border: 0;
        background: #ffffff;
        padding: 10px 12px 10px 0;
        color: #244744;
        font-size: 0.8rem;
        outline: none;
        box-shadow: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .input-control::placeholder {
        color: #93a5a2;
    }

    .input-control:-webkit-autofill,
    .input-control:-webkit-autofill:hover,
    .input-control:-webkit-autofill:focus,
    .input-control:-webkit-autofill:active {
        -webkit-text-fill-color: #244744;
        caret-color: #244744;
        box-shadow: 0 0 0 1000px #ffffff inset !important;
        -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
        transition: background-color 9999s ease-out 0s;
    }

    .input-control:focus {
        background: #ffffff;
    }

    .field-error {
        margin-top: 6px;
        color: #c23b3b;
        font-size: 0.68rem;
        font-weight: 500;
        line-height: 1.35;
    }

    .toggle-password {
        border: 0;
        background: transparent;
        color: #6e807d;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .remember-check {
        display: inline-flex;
        align-items: flex-start;
        gap: 8px;
        margin: 2px 0 14px;
        color: var(--text-main);
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        line-height: 1.35;
        padding-top: 1px;
    }

    .remember-check input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: var(--accent-main);
        flex: 0 0 16px;
        margin-top: 1px;
    }

    .form-actions {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin: 8px 0 14px;
    }

    .forgot-link {
        color: var(--accent-main);
        font-size: 0.68rem;
        font-weight: 700;
        text-decoration: none;
        line-height: 1.35;
        padding-top: 1px;
    }

    .submit-btn {
        width: 100%;
        min-height: 48px;
        border: 0;
        border-radius: 18px;
        background: linear-gradient(180deg, var(--accent-soft) 0%, var(--accent-main) 100%);
        color: #fff;
        font-size: 0.82rem;
        font-weight: 800;
        box-shadow: 0 14px 28px rgba(0, 116, 90, 0.20);
        cursor: pointer;
    }

    .page-version {
        margin: 12px auto 0;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.62rem;
        font-weight: 500;
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
        box-shadow: 0 24px 60px rgba(0, 85, 63, 0.16);
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
        border: 3px solid rgba(0, 116, 90, 0.16);
        border-top-color: var(--accent-main);
        animation: auth-loader-spin 0.9s linear infinite;
    }

    .auth-loader-ring::before {
        inset: 8px;
        border: 3px solid rgba(0, 116, 90, 0.12);
        border-bottom-color: var(--accent-deep);
        animation: auth-loader-spin-reverse 1.3s linear infinite;
    }

    .auth-loader-ring::after {
        inset: 18px;
        background: radial-gradient(circle at 30% 30%, var(--accent-soft), var(--accent-main));
        box-shadow: 0 8px 16px rgba(0, 116, 90, 0.22);
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
        .login-shell {
            padding: 14px 12px 14px;
            gap: 12px;
        }

        .auth-header {
            max-width: 100%;
        }

        .card-body {
            padding-left: 16px;
            padding-right: 16px;
        }

        .welcome-title {
            font-size: 1.14rem;
        }

        .welcome-subtitle {
            font-size: 0.74rem;
        }
    }
</style>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/_auth-styles.blade.php ENDPATH**/ ?>