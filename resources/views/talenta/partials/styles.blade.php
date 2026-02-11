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

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        max-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
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
        width: 100%;
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
        position: absolute;
        left: 0;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: white;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Tab Navigation */
    .tabs-container {
        position: relative;
        margin-top: -60px;
        z-index: 10;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 30px;
        padding: 0 20px;
    }

    .tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        background: #ffffff;
        padding: 15px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .tab-btn {
        padding: 14px 28px;
        border: none;
        background: transparent;
        color: #666;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tab-btn:hover {
        background: rgba(0, 75, 76, 0.1);
        color: #004b4c;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
    }

    .tab-btn i {
        font-size: 20px;
    }

    /* CONTENT */
    .talenta-data {
        padding: 50px 0 80px;
        background: #f8fafc;
        margin-top: -30px;
    }

    .data-section {
        margin-bottom: 0;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-content.active {
        display: block;
    }

    /* Area Content */
    .area-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .area-content.active {
        display: block;
    }

    /* Sub Tabs */
    .sub-tabs {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .sub-tab-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .sub-tab-btn:hover {
        background: rgba(0, 75, 76, 0.05);
        color: #004b4c;
    }

    .sub-tab-btn.active {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        border-color: #004b4c;
    }

    /* Sub Tab Content */
    .sub-tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .sub-tab-content.active {
        display: block;
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

    .fade-up {
        transform: translateY(30px);
    }

    .fade-up.delay-1 {
        transition-delay: 0.2s;
    }

    .fade-up.delay-2 {
        transition-delay: 0.4s;
    }

    .fade-up.delay-3 {
        transition-delay: 0.6s;
    }

    .fade-up.delay-4 {
        transition-delay: 0.8s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Cards */
    .card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #004b4c;
    }

    .card-subtitle {
        font-size: 14px;
        color: #888;
        margin-top: 4px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #004b4c;
        box-shadow: 0 0 0 4px rgba(0, 75, 76, 0.1);
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23004b4c' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 45px;
    }

    /* Checkbox & Radio */
    .form-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f9f9f9;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check:hover {
        background: #f0f0f0;
    }

    .form-check input[type="radio"],
    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #004b4c;
    }

    .form-check-label {
        font-size: 14px;
        color: #444;
        cursor: pointer;
    }

    /* Info Box */
    .info-box {
        padding: 20px 25px;
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.05), rgba(0, 102, 102, 0.05));
        border-radius: 14px;
        border-left: 4px solid #004b4c;
        margin-bottom: 25px;
    }

    .info-box p {
        font-size: 14px;
        color: #555;
        line-height: 1.7;
    }

    .info-box ul {
        margin-top: 12px;
        padding-left: 20px;
    }

    .info-box ul li {
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        line-height: 1.6;
    }

    /* Group Selection */
    .group-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .group-card {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 12px;
        text-align: center;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .group-card:hover {
        background: #f0f0f0;
    }

    .group-card.selected {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.05);
    }

    .group-card i {
        font-size: 32px;
        color: #004b4c;
        margin-bottom: 10px;
    }

    .group-card h4 {
        font-size: 15px;
        color: #333;
        margin-bottom: 5px;
    }

    .group-card p {
        font-size: 12px;
        color: #888;
    }

    /* Buttons */
    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 75, 76, 0.4);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #555;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    /* Section Title */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title::before {
        content: '';
        width: 5px;
        height: 28px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 3px;
    }

    /* Deadline Badge */
    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #fff3cd;
        color: #856404;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .deadline-badge i {
        font-size: 16px;
    }

    /* Warning Badge */
    .warning-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        margin-left: 8px;
        animation: pulse 2s infinite;
    }

    .warning-badge i {
        font-size: 14px;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            margin-top: 80px;
            padding: 35px 20px;
            border-radius: 20px;
        }

        .page-header h1 {
            font-size: 26px;
        }

        .tabs {
            gap: 8px;
            padding: 10px;
        }

        .tab-btn {
            padding: 12px 18px;
            font-size: 13px;
        }

        .tab-btn i {
            font-size: 18px;
        }

        .card {
            padding: 25px 20px;
        }

        .card-title {
            font-size: 18px;
        }

        .group-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .page-header h1 {
            font-size: 22px;
        }

        .group-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
