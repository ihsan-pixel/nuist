<?php $__env->startSection('title', 'MGMP - Musyawarah Guru Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>

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

    /* NAVBAR Styles */
    .navbar {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(10px);
        position: fixed;
        top: 20px;
        width: 1400px;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
        z-index: 1000;
        border-radius: 50px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .navbar.transparent {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(20px);
    }

    .navbar.full-width {
        width: 100%;
        top: 0;
        border-radius: 0 0 28px 28px;
        position: fixed;
        left: 0;
        right: 0;
    }

    .navbar.scrolled {
        top: 0px;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 80px;
        transition: justify-content 0.3s ease;
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 20px;
    }

    .nav-menu a {
        text-decoration: none;
        color: #004b4c;
        font-weight: 500;
        font-size: 18px;
        padding: 8px 16px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
        transform: translateY(0) scale(1);
        box-shadow: 0 0 0 rgba(0, 75, 76, 0);
        cursor: pointer;
    }

    .nav-menu a:hover, .nav-menu a.active {
        color: #fefefe;
        background: linear-gradient(135deg, #004b4c, #006666);
    }

    .btn-primary {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 26px;
        border-radius: 999px;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #004b4c, #006666);
        overflow: hidden;
        z-index: 1;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        min-width: 0;
        min-height: 0;
        background: rgba(2, 2, 2, 0.976);
        border-radius: 50%;
        bottom: -60%;
        right: -60%;
        transition: width 0.55s ease-out, height 0.55s ease-out;
        z-index: -1;
    }

    .btn-primary:hover::before {
        width: 380%;
        height: 380%;
    }

    /* DROPDOWN SUBMENU */
    .dropdown {
        position: relative;
    }

    .dropdown:hover .submenu,
    .dropdown.open .submenu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .arrow {
        display: inline-block;
        transition: transform 0.3s;
        transform: rotate(0deg);
        font-size: 20px;
        vertical-align: middle;
    }

    .dropdown:hover .arrow,
    .dropdown.open .arrow {
        transform: rotate(-180deg);
    }

    .submenu {
        position: absolute;
        top: 110%;
        left: 0;
        min-width: 240px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        padding: 12px;
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .dropdown:hover .submenu,
    .dropdown.open .submenu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .submenu li {
        list-style: none;
    }

    .submenu li a {
        display: block;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 14px;
        color: #004b4c;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .submenu li a:hover {
        background: #f1f5ff;
        color: #eda711;
        padding-left: 18px;
    }

    /* Hamburger Menu */
    .hamburger {
        display: none;
        flex-direction: column;
        cursor: pointer;
        gap: 4px;
    }

    .hamburger span {
        width: 25px;
        height: 3px;
        background: #004b4c;
        transition: 0.3s;
    }

    .hamburger.open span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .hamburger.open span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.open span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar {
            width: 95%;
            margin: 10px auto;
            padding: 0 15px;
            left: 0;
            right: 0;
        }

        .navbar.full-width {
            width: 100%;
            margin: 0;
            border-radius: 0;
            left: 0;
            right: 0;
        }

        .nav-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #ffffff;
            flex-direction: column;
            gap: 0;
            padding: 20px 0;
            border-radius: 0 0 28px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .nav-menu.show {
            display: flex;
        }

        .nav-menu a {
            padding: 15px 20px;
            border-radius: 0;
            text-align: center;
        }

        .hamburger {
            display: flex;
        }

        .nav-flex {
            height: 60px;
        }
    }

    @media (max-width: 480px) {
        .navbar {
            width: 98%;
            margin: 5px auto;
            height: 60px;
            left: 0;
            right: 0;
        }
    }

    /* HERO */
    .hero {
        position: relative;
        margin-top: 65px;
        margin-bottom: 30px;
        padding: 50px 20px 120px;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin-left: auto;
        margin-right: auto;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero h1 {
        font-size: 56px;
        font-weight: 800;
        line-height: 1.15;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #eda711;
    }

    .hero p {
        font-size: 18px;
        max-width: 720px;
        margin: 0 auto 40px;
        opacity: 0.9;
        color: white;
    }

    /* SECTION BACKGROUNDS */
    .section-clean {
        padding: 100px 0;
        background: #ffffff;
    }

    .section-soft {
        padding: 100px 0;
        background: #f8fafc;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 20px;
        color: #0f172a;
    }

    .section-subtitle {
        text-align: center;
        font-size: 16px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto 60px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .card {
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        transition: all 0.3s ease;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .card h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #0f172a;
    }

    .card p {
        font-size: 15px;
        color: #64748b;
        line-height: 1.7;
    }

    /* TABLE MODERN */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-modern th {
        background: #0f172a;
        color: white;
        padding: 14px;
        font-weight: 600;
    }

    .table-modern td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
    }

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/index.blade.php ENDPATH**/ ?>