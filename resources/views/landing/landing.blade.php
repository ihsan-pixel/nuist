@extends('layouts.master')

@section('title', 'CMS Profesional - Kelola Konten dengan Mudah')
@section('description', 'Platform CMS modern untuk mengelola konten website dengan mudah dan profesional.')

@section('content')
<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-flex">
        <div class="nav-left">
            <img src="{{ asset('images/logo-long-1.png') }}" alt="Logo" style="height: 50px;">
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#" onclick="event.preventDefault(); scrollToTop()">Beranda</a></li>
                <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('about')">Tentang</a></li>
                <li class="dropdown">
                    <a href="#" onclick="toggleSubmenu(event)">Fitur <i class="bx bx-chevron-down arrow"></i></a>
                    <ul class="submenu">
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Performa Tinggi</a></li>
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Responsif Penuh</a></li>
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Keamanan Terjamin</a></li>
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Template Modern</a></li>
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Analytics Terintegrasi</a></li>
                        <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('features')">Dukungan 24/7</a></li>
                    </ul>
                </li>
                <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('sekolah')">Sekolah</a></li>
                <li><a href="#" onclick="event.preventDefault(); smoothScrollToSection('contact')">Kontak</a></li>
                <li class="login-mobile"><a href="#" class="btn-primary">Login<i class='bx bx-arrow-back bx-rotate-180'></i></a></li>
            </ul>
            <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <a href="#" class="btn-primary desktop-login">Login<i class='bx bx-arrow-back bx-rotate-180'></i></a>
    </div>
</nav>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        @if($landing->image_1_hero)
            <img src="{{ asset('storage/' . $landing->image_1_hero) }}" alt="Hero Image 1" class="hero-image animate zoom-soft" style="height: 100px; margin-top: 0px; margin-bottom: 50px;">
        @else
            <img src="{{ asset('images/image-hero-1.png') }}" alt="Logo" class="hero-image animate zoom-soft" style="height: 100px; margin-top: 0px; margin-bottom: 50px;">
        @endif
        <h1 class="hero-title animate fade-up">
            {{ $landing->title_hero ?? 'Nuist - Sistem Informasi Digital' }}
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">{{ $landing->sub_title_hero ?? 'LP. Ma\'arif NU PWNU DIY' }}</h1>
        <p class="animate fade-up delay-2">{{ $landing->content_hero ?? 'Kelola data kelembagaan, aktivitas, sistem informasi dan layanan dalam satu aplikasi yang modern, aman, dan mudah digunakan.' }}</p>
        @if($landing->image_2_hero)
            <img src="{{ asset('storage/' . $landing->image_2_hero) }}" alt="Hero Image 2" class="hero-image animate zoom-soft delay-3" style="height: 500px; margin-top: 0px; margin-bottom: -350px;">
        @else
            <img src="{{ asset('images/hero-2.png') }}" alt="Logo" class="hero-image animate zoom-soft delay-3" style="height: 500px; margin-top: 0px; margin-bottom: -350px;">
        @endif
    </div>
</section>

<!-- SEKOLAH/MADRASAH -->
<section id="sekolah" class="sekolah">
    <div class="container">
        <h1 class="section-title animate fade-up">Sekolah/Madrasah dibawah naungan kami</h1>
        <div class="carousel-container animate fade-up delay-1">
            <div class="carousel-track">
                @foreach($madrasahs as $madrasah)
                    <div class="madrasah-item">
                        <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
                        <p>{{ $madrasah->name }}</p>
                        <p>{{ $madrasah->kabupaten }}</p>
                    </div>
                @endforeach
                @foreach($madrasahs as $madrasah)
                    <div class="madrasah-item">
                        <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
                        <p>{{ $madrasah->name }}</p>
                        <p>{{ $madrasah->kabupaten }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- PROFILE -->
<section id="about" class="profile">
    <h2 class="section-title animate fade-up">{{ $landing->title_profile ?? 'Profile Nuist' }}</h2>
    <div class="container profile-flex animate fade-up delay-1">
        <div class="profile-content" style="text-align: center">
            <p>{{ $landing->content_1_profile ?? 'Nuist menghadirkan ekosistem aplikasi terintegrasi yang dirancang untuk mendukung pengelolaan administrasi sekolah secara menyeluruh. Melalui Nuist Desktop dan Nuist Mobile, sekolah dapat mengelola data, aktivitas, dan kehadiran secara terpusat, akurat, serta mudah diakses oleh administrator, tenaga pendidik, dan kepala sekolah dalam satu sistem yang saling terhubung.' }}</p>
        </div>
    </div>
    <div class="container profile-flex animate fade-up delay-2" style="margin-top: 50px;">
        @if($landing->image_1_profile)
            <img src="{{ asset('storage/' . $landing->image_1_profile) }}" alt="Profile Image 1" class="hero-image animate fade-left delay-1" style="height: 300px; margin-top: 0px; margin-bottom: -0px;">
        @else
            <img src="{{ asset('images/profile-1.png') }}" alt="Logo" class="hero-image animate fade-left delay-1" style="height: 300px; margin-top: 0px; margin-bottom: -0px;">
        @endif
        @if($landing->image_2_profile)
            <img src="{{ asset('storage/' . $landing->image_2_profile) }}" alt="Profile Image 2" class="hero-image animate fade-right delay-2" style="height: 300px; margin-top: 0px; margin-bottom: -0px;">
        @else
            <img src="{{ asset('images/profile-2.png') }}" alt="Logo" class="hero-image animate fade-right delay-2" style="height: 300px; margin-top: 0px; margin-bottom: -0px;">
        @endif
    </div>
    <div class="container profile-flex animate fade-up delay-3" style="margin-top: 50px;">
        <div class="profile-content animate fade-left">
            <h2 class="title-with-dot"><span class="dot"></span>Nuist Dekstop</h2>
            <p>{{ $landing->content_2_profile ?? 'Aplikasi khusus untuk administrator sekolah dalam mengelola data sekolah dan data tenaga pendidik secara terpusat, aman, dan efisien. Dirancang untuk mendukung kebutuhan administrasi modern, Nuist Desktop membantu menyederhanakan pengelolaan data, meningkatkan akurasi informasi, serta mendukung pengambilan keputusan berbasis data.' }}</p>
        </div>
        <div class="profile-content animate fade-right delay-1">
            <h2 class="title-with-dot"><span class="dot"></span>Nuist Mobile</h2>
            <p>{{ $landing->content_3_profile ?? 'Aplikasi berbasis mobile yang dirancang khusus untuk tenaga pendidik dan kepala sekolah dalam melakukan presensi, presensi mengajar, pengajuan izin, serta penyesuaian data pribadi secara praktis dan real-time. Aplikasi ini mendukung kemudahan akses, akurasi data, dan efisiensi administrasi dalam satu platform terpadu.' }}</p>
        </div>
    </div>
    <div class="container profile-flex animate fade-up delay-1" style="margin-top: 50px; justify-content: center;">
        <div class="profile-content animate fade-up delay-1" style="text-align: center">
            <h1 id="count1" style="text-align:center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">36</h1>
            <p>Sekolah/Madrasah</p>
        </div>
        <div class="profile-content animate fade-up delay-2" style="text-align: center">
            <h1 id="count2" style="text-align: center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">750+</h1>
            <P>Tenaga Pendidik Aktif</P>
        </div>
        <div class="profile-content animate fade-up delay-3" style="text-align: center">
            <h1 id="count3" style="text-align: center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">36</h1>
            <p>Admin Operator Aktif</p>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="features">
    <div class="container">
        <h1 class="section-title animate fade-up" style="color:aliceblue; margin-top: -60px;">{{ $landing->title_features ?? 'Fitur Unggulan' }}</h1>
        <p class="section-description animate fade-up delay-1" style="color:aliceblue">{{ $landing->content_features ?? 'Nikmati berbagai fitur canggih yang dirancang untuk memaksimalkan efisiensi dan keamanan dalam pengelolaan sekolah Anda.' }}</p>
        <div class="grid animate fade-up delay-2">
            @if($landing->features)
                @foreach($landing->features as $index => $feature)
                    <div class="card animate fade-up {{ $feature['status'] == 'coming_soon' ? 'coming-soon' : '' }}" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                        {{-- <div class="card-icon">{{ $feature['status'] == 'coming_soon' ? '⏳' : '⚡' }}</div> --}}
                        <h3>{{ $feature['name'] }}</h3>
                        <p>{{ $feature['content'] }}</p>
                        @if($feature['status'] == 'coming_soon')
                            <div class="status-ribbon">Coming Soon</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="card animate fade-up delay-3">
                    <div class="card-icon">⚡</div>
                    <h3>Performa Tinggi</h3>
                    <p>Website loading cepat dengan optimasi SEO otomatis untuk meningkatkan visibilitas.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ABOUT -->
{{-- <section id="about" class="about">
    <div class="container about-flex">
        <img src="https://via.placeholder.com/500x300/2563eb/white?text=CMS+Dashboard" alt="CMS Dashboard">
        <div class="about-content">
            <h2>Tentang CMS Pro</h2>
            <p>CMS Pro adalah platform content management system yang dirancang untuk memudahkan bisnis dan individu dalam membangun website profesional. Dengan antarmuka yang intuitif, Anda dapat mengelola konten, gambar, dan data tanpa pengetahuan teknis yang mendalam.</p>
            <p>Kami berkomitmen untuk memberikan solusi terbaik dengan teknologi terkini, memastikan website Anda selalu up-to-date dan kompetitif di era digital.</p>
        </div>
    </div>
</section> --}}

<!-- TESTIMONIALS -->
<section class="testimonials">
    <div class="container">
        <h2 class="section-title animate fade-up">Apa Kata Pengguna Kami</h2>
        <div class="testimonial-grid animate fade-up delay-1">
            <div class="testimonial animate fade-up delay-1">
                <p>"CMS Pro sangat mudah digunakan. Dalam hitungan jam, website saya sudah online dan terlihat profesional."</p>
                <div class="testimonial-author">- Ahmad S., Pemilik Toko Online</div>
            </div>
            <div class="testimonial animate fade-up delay-2">
                <p>"Fitur analytics-nya sangat membantu. Saya bisa melihat performa website secara real-time."</p>
                <div class="testimonial-author">- Siti R., Konsultan Bisnis</div>
            </div>
            <div class="testimonial animate fade-up delay-3">
                <p>"Dukungan teknisnya luar biasa. Setiap pertanyaan saya dijawab dengan cepat dan jelas."</p>
                <div class="testimonial-author">- Budi K., Developer Freelance</div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 CMS Pro. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Support</a>
        </div>
    </div>
</footer>
@endsection

@push('styles')
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

    /* NAVBAR */
    .navbar {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(10px);
        /* box-shadow: 0 4px 20px rgba(0,0,0,0.1); */
        position: fixed;
        top: 20px;
        width: 1400px;
        margin: 0 auto;
        z-index: 1000;
        border-radius: 40px;
        transition: background 0.3s ease, backdrop-filter 0.3s ease, width 0.3s ease, margin 0.3s ease, border-radius 0.3s ease, top 0.3s ease;
    }

    .navbar.transparent {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(20px);
    }

    .navbar.full-width {
        width: 100%;
        left: 0;
        transform: none;
        border-radius: 0 0 28px 28px;
    }

    .navbar.scrolled {
        top: 0px;
    }

    .navbar.centered .nav-flex {
        justify-content: center;
    }

    .navbar.centered .nav-left {
        opacity: 0;
        pointer-events: none;
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

    .logo {
        font-size: 28px;
        font-weight: 700;
        color: #004b4c;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    .nav-menu a {
        text-decoration: none;
        color: #004b4c;
        font-weight: 300;
        padding: 8px 16px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
        transform: translateY(0) scale(1);
        box-shadow: 0 0 0 rgba(0, 75, 76, 0);
    }

    .nav-menu a:hover {
        color: #eda711;
        background: rgba(255, 95, 95, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #004b4c);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        /* transition: transform 0.3s; */
    }

    .btn-primary:hover {
        /* transform: translateY(-2px); */
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

    /* HERO */
    .hero {
        padding: 120px 0 80px;
        color: white;
        text-align: center;
    }

    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero p {
        font-size: 20px;
        margin-bottom: 40px;
        opacity: 0.9;
    }

    .hero .btn-primary {
        display: inline-block;
        margin-top: 20px;
    }

    /* FEATURES */
    .features {
        padding: 120px 0;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        position: relative;
        overflow: hidden;
    }

    .features::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 60px 60px;
        border-radius: 48px;
        pointer-events: none;
    }

    .section-description {
        text-align: center;
        font-size: 18px;
        color: #6b7280;
        max-width: 800px;
        margin: 0 auto 80px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .section-title {
        text-align: center;
        font-size: 24px;
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

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 32px;
        max-width: 1400px;
        margin: auto;
    }

    .card {
        position: relative;
        padding: 40px;
        border-radius: 28px;
        border: 2px solid #eee;
        background: #fff;
        transition: all 0.3s ease;
        text-align: center;
    }

    .card.active {
        border-color: #7c3aed;
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #9333ea, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 28px;
        color: white;
    }

    .card h3 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111827;
    }

    .card p {
        font-size: 16px;
        line-height: 1.6;
        color: #6b7280;
        max-width: 320px;
        margin: 0 auto;
    }

    .card.active .dot {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 12px;
        height: 12px;
        background: #7c3aed;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 0 6px rgba(124, 58, 237, 0.15);
    }

    .status-ribbon {
        position: absolute;
        top: -3px;
        right: -20px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transform: rotate(30deg);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        z-index: 10;
        border-radius: 4px;
    }

    .status-ribbon::before {
        content: '';
        position: absolute;
        top: 100%;
        left: 0;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid rgba(255, 107, 107, 0.8);
    }

    /* ABOUT */
    .about {
        padding: 80px 0;
        background: #f1f5f9;
    }

    .about-flex {
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .about img {
        width: 100%;
        max-width: 500px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .about-content h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #1f2937;
    }

    .about-content p {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 30px;
    }

    /* PROFILE */
    .profile {
        padding: 80px 0;
        background: #ffffff;
        margin-top: -100px;
    }

    .profile-flex {
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .profile-content h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #004b4c;
    }

    .profile-content h1 {
        font-size: 64px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #000000;
    }

    .profile-content p {
        font-size: 18px;
        color: #6d6b7b;
        margin-bottom: 30px;
    }

    /* TESTIMONIALS */
    .testimonials {
        padding: 80px 0;
        background: white;
    }

    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .testimonial {
        background: #f8fafc;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        text-align: center;
    }

    .testimonial p {
        font-style: italic;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .testimonial-author {
        font-weight: 600;
        color: #1f2937;
    }

    /* PRICING */
    .pricing {
        padding: 80px 0;
        background: #f1f5f9;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .pricing-card {
        background: white;
        padding: 40px 30px;
        text-align: center;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .pricing-card:hover {
        transform: translateY(-10px);
    }

    .pricing-card h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
    }

    .price {
        font-size: 48px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 20px;
    }

    .pricing-card ul {
        list-style: none;
        margin-bottom: 30px;
    }

    .pricing-card li {
        margin-bottom: 10px;
        color: #6b7280;
    }

    /* CONTACT */
    .contact {
        padding: 80px 0;
        background: white;
    }

    .contact-flex {
        display: flex;
        gap: 60px;
    }

    .contact-form {
        flex: 1;
    }

    .contact-form h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #1f2937;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 16px;
    }

    .contact-info {
        flex: 1;
    }

    .contact-info h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
    }

    .contact-info p {
        margin-bottom: 15px;
        color: #6b7280;
    }

    /* FOOTER */
    .footer {
        background: #1f2937;
        color: #9ca3af;
        text-align: center;
        padding: 40px 0;
    }

    .footer .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .footer-links {
        display: flex;
        gap: 30px;
    }

    .footer-links a {
        color: #9ca3af;
        text-decoration: none;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .navbar {
            width: 95%;
            margin: 10px auto;
            padding: 0 15px;
        }

        .navbar.full-width {
            width: 100%;
            margin: 0;
            border-radius: 0;
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

        .hero {
            padding: 40px 20px 120px;
            min-height: auto;
            margin-top: -10px;
        }

        .hero-title {
            font-size: 28px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 24px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .hero-image {
            height: 80px !important;
            margin-bottom: 30px;
        }

        .hero .hero-image:nth-child(2) {
            height: 400px !important;
            margin-bottom: -300px;
        }

        .sekolah {
            padding: 60px 0;
            margin-top: 100px;
        }

        .carousel-container {
            margin-top: 40px;
        }

        .madrasah-item {
            margin-right: 20px;
            min-height: 120px;
        }

        .madrasah-item img {
            height: 60px;
            width: 120px;
        }

        .madrasah-item p {
            font-size: 11px;
            max-width: 120px;
        }

        .profile {
            padding: 60px 0;
            margin-top: -80px;
        }

        .profile-flex {
            flex-direction: column;
            gap: 40px;
        }

        .profile-content h2 {
            font-size: 28px;
        }

        .profile-content h1 {
            font-size: 48px;
        }

        .profile-content p {
            font-size: 16px;
        }

        .title-with-dot {
            font-size: 28px;
        }

        .features {
            padding: 80px 0;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .section-description {
            font-size: 16px;
            margin-bottom: 60px;
        }

        .grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            padding: 30px 20px;
        }

        .card h3 {
            font-size: 20px;
        }

        .card p {
            font-size: 15px;
        }

        .testimonials {
            padding: 60px 0;
        }

        .testimonial-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .testimonial {
            padding: 25px;
        }

        .about-flex,
        .contact-flex {
            flex-direction: column;
            gap: 40px;
        }

        .pricing-grid {
            grid-template-columns: 1fr;
        }

        .footer .container {
            flex-direction: column;
            gap: 20px;
        }

        .footer-links {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }

        .navbar {
            width: 98%;
            margin: 5px auto;
            height: 60px;
        }

        .nav-flex {
            height: 60px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 15px;
        }

        .hero-image {
            height: 60px !important;
        }

        .hero .hero-image:nth-child(2) {
            height: 300px !important;
            margin-bottom: -250px;
        }

        .profile-content h1 {
            font-size: 36px;
        }

        .title-with-dot {
            font-size: 24px;
        }

        .card {
            padding: 25px 15px;
        }
    }

/* ===== CLOUDLY STYLE OVERRIDE ===== */

/* Navbar lebih clean & modern */
.navbar {
    background: #ffffff;
    border-radius: 40px;
    /* box-shadow: 0 10px 30px rgba(0,0,0,0.08); */
    width: 1400px;
    position: sticky;
    margin: 0 auto;
}

.navbar.full-width {
    width: 100%;
    margin: 0;
    border-radius: 0 0 28px 28px;
}

/* Container navbar lebih lebar */
.navbar .container {
    max-width: 1400px;
    transition: max-width 0.3s ease;
}

.navbar.transparent .container {
    max-width: 1400px;
}

/* Menu spacing & typography */
.nav-menu a {
    font-weight: 500;
    color: #004b4c;
    padding: 10px 18px;
}

.nav-menu a:hover {
    color: #fefefe;
    background: linear-gradient(135deg, #004b4c, #006666);
    transform: translateY(-3px) scale(1.05);
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

    /* start lebih keluar */
    bottom: -60%;
    right: -60%;

    transition:
        width 0.55s ease-out,
        height 0.55s ease-out;

    z-index: -1;
}

.btn-primary:hover::before {
    width: 380%;
    height: 380%;
}

.btn-primary:hover {
    /* No position animation */
}

/* Hero container */
.hero {
    position: relative;
    margin-top: -20px;
    margin-bottom: 40px;
    padding: 60px 20px 160px;
    background: linear-gradient(135deg, #00393a, #005555, #00393a);
    border-radius: 48px;
    max-width: 1600px;
    margin-left: auto;
    margin-right: auto;
    min-height: 87vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

/* Judul hero */
.hero h1 {
    font-size: 56px;
    font-weight: 800;
    line-height: 1.15;
}

/* Deskripsi */
.hero p {
    font-size: 18px;
    max-width: 720px;
    margin: 0 auto 40px;
    opacity: 0.9;
}

/* Tombol hero */
.hero .btn-primary {
    background: #ffffff;
    color: #004b4c;
    padding: 14px 30px;
    border-radius: 999px;
    font-weight: 700;
    box-shadow: none;
}

/* Efek hover tombol hero */
.hero .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(255,255,255,0.35);
}

/* Background grid halus seperti Cloudly */
.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 60px 60px;
    border-radius: 48px;
    pointer-events: none;
}

/* Hero image hover effect */
.hero-image {
    transition: transform 0.3s ease, filter 0.3s ease;
    cursor: pointer;
}

.hero-image:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
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

/* SEKOLAH/MADRASAH CAROUSEL */
.sekolah {
    padding: 80px 0;
    background: #ffffff;
    margin-top: 150px;
}

.carousel-container {
    overflow: hidden;
    width: 100%;
    margin-top: 80px;
    display: flex;
    justify-content: center;
}

.carousel-track {
    display: flex;
    width: max-content;
    animation: infiniteScroll 100s linear infinite;
    justify-content: center;
}

.carousel-track img {
    height: 75px;
    width: 150px;
    object-fit: contain;
    flex-shrink: 0;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.carousel-track img:hover {
    transform: scale(1.05);
}

.madrasah-item p {
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #004b4c;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
    max-height: 2.4em;
}

/* INFINITE LOOP TANPA JEDA */
@keyframes infiniteScroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.madrasah-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-right: 40px;
    flex-shrink: 0;
    min-height: 140px;
    gap: 5px;
}

.madrasah-item p {
    font-size: 12px;
    margin: 0;
    text-align: center;
    line-height: 1.3;
    max-width: 150px;
    word-wrap: break-word;
    color: #333;
}

/* Optional: Pause saat hover */
.carousel-track:hover {
    animation-play-state: paused;
}

.madrasah-item:hover {
    animation-play-state: paused;
}

/* Optional: Versi Mobile Lebih Cepat */
@media (max-width: 768px) {
    .carousel-track {
        animation-duration: 25s;
    }

    .madrasah-item {
        margin-right: 20px;
    }

    .madrasah-item img {
        height: 60px;
        width: 120px;
    }

    .madrasah-item p {
        font-size: 12px;
        max-height: 2em;
    }
}

/* TITLE WITH DOT (ABOUT STYLE) */
.title-with-dot {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 36px;
    font-weight: 800;
    color: #004b4c; /* ungu seperti contoh */
    letter-spacing: 1px;
}

.title-with-dot .dot {
    width: 18px;
    height: 18px;
    background: linear-gradient(135deg, #004b4c, #006666);
    border-radius: 50%;
    flex-shrink: 0;
}
.hero-form {
    display: flex;
    gap: 40px;
    margin-top: 30px;
}

.form-column {
    flex: 1;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 18px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 14px;
}

.form-group input,
.form-group textarea {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
}



/* === BASE ANIMATION === */
.animate {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

.animate.show {
    opacity: 1;
    transform: translateY(0);
}

/* VARIANTS */
.fade-left {
    transform: translateX(-40px);
}

.fade-right {
    transform: translateX(40px);
}

.zoom-soft {
    transform: scale(0.95);
}

.show.fade-left,
.show.fade-right {
    transform: translateX(0);
}

.show.zoom-soft {
    transform: scale(1);
}

/* DELAY UTIL */
.delay-1 { transition-delay: 0.15s; }
.delay-2 { transition-delay: 0.3s; }
.delay-3 { transition-delay: 0.45s; }
.delay-4 { transition-delay: 0.6s; }

/* === BASE ANIMATION === */
.animate {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

.animate.show {
    opacity: 1;
    transform: translateY(0);
}

/* VARIANTS */
.fade-left {
    transform: translateX(-40px);
}

.fade-right {
    transform: translateX(40px);
}

.zoom-soft {
    transform: scale(0.95);
}

.show.fade-left,
.show.fade-right {
    transform: translateX(0);
}

.show.zoom-soft {
    transform: scale(1);
}

/* DELAY UTIL */
.delay-1 { transition-delay: 0.15s; }
.delay-2 { transition-delay: 0.3s; }
.delay-3 { transition-delay: 0.45s; }
.delay-4 { transition-delay: 0.6s; }

/* Responsive */
@media (max-width: 768px) {
    .hero-form {
        flex-direction: column;
        gap: 20px;
    }

    .fade-in-up, .fade-in-left, .fade-in-right {
        animation-duration: 0.6s;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function animateCounter(element, target, duration, suffix) {
        let start = 1;
        const increment = (target - 1) / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(start) + suffix;
        }, 16);
    }

