<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        padding: 100px 20px;
        color: white;
        text-align: center;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin: 65px auto 0;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: white;
    }

    .hero-subtitle {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* MAP SECTION */
    .map-section {
        padding: 80px 0;
        background: #ffffff;
    }

    #map {
        height: 500px;
        width: 1400px;
        margin: 0 auto;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* SEKOLAH LIST */
    .sekolah-list {
        padding: 80px 0;
        background: #f8fafc;
    }

    .kabupaten-section {
        margin-bottom: 60px;
    }

    .kabupaten-header {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #eda711;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kabupaten-icon {
        font-size: 28px;
    }

    .kabupaten-count {
        font-size: 16px;
        font-weight: 400;
        color: #6b7280;
        margin-left: auto;
    }

    .scod-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 12px;
        background: #004b4c;
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .school-card-link {
        text-decoration: none;
        color: inherit;
    }

    .school-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .school-logo {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 20px;
    }

    .school-logo img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .school-info {
        padding: 20px;
        text-align: center;
    }

    .school-info h3 {
        font-size: 20px;
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
    }

    .school-info p {
        color: #6b7280;
        font-size: 14px;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 60px;
        color: #004b4c;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        width: 0;
        height: 3px;
        background-color: #eda711;
        transition: width 0.3s ease, left 0.3s ease;
    }

    .section-title.active::after {
        width: 50%;
        left: 25%;
    }

    section:hover .section-title::after {
        width: 100%;
        left: 0;
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

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 80px 20px;
            margin-top: 80px;
            min-height: auto;
        }

        .hero h1 {
            font-size: 32px;
        }

        .hero-subtitle {
            font-size: 28px;
        }

        .hero p {
            font-size: 16px;
        }

        .schools-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .school-card {
            margin: 0 10px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 40px;
        }
    }

    /* Custom Cursor Effect */
    .cursor-small {
        position: fixed;
        width: 10px;
        height: 10px;
        background-color: #00ff00;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: transform 0.1s ease;
    }

    .cursor-large {
        position: fixed;
        width: 30px;
        height: 30px;
        background-color: #00ff88;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9998;
        transition: transform 0.15s ease;
        opacity: 0.5;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
