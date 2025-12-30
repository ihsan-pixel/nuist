<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CMS Profesional - Kelola Konten dengan Mudah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform CMS modern untuk mengelola konten website dengan mudah dan profesional.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 80px;
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 50px;
    }

    .logo {
        font-size: 28px;
        font-weight: 700;
        color: #2563eb;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 50px;
    }

    .nav-menu a {
        text-decoration: none;
        color: #000000;
        font-weight: 350;
        transition: color 0.3s;
    }

    .nav-menu a:hover {
        color: #2563eb;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
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
        padding: 80px 0;
        background: white;
    }

    .section-title {
        text-align: center;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 60px;
        color: #1f2937;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .card {
        background: #f8fafc;
        padding: 40px 30px;
        text-align: center;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .card-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .card h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1f2937;
    }

    .card p {
        color: #6b7280;
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
        color: #2563eb;
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
        .nav-menu {
            display: none;
        }

        .hero h1 {
            font-size: 36px;
        }

        .hero p {
            font-size: 18px;
        }

        .about-flex,
        .contact-flex {
            flex-direction: column;
        }

        .grid,
        .testimonial-grid,
        .pricing-grid {
            grid-template-columns: 1fr;
        }

        .footer .container {
            flex-direction: column;
            gap: 20px;
        }
    }
</style>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-flex">
        <div class="nav-left">
            <img src="{{ asset('images/flayer1.png') }}" class="img-fluid rounded animate-bounce-in" alt="Flayer PPDB">
            <ul class="nav-menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#pricing">Harga</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </div>
        <a href="#" class="btn-primary">Mulai Gratis</a>
    </div>
</nav>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <h1>Kelola Konten Website dengan Mudah</h1>
        <p>Platform CMS profesional yang memungkinkan Anda membuat dan mengelola konten website tanpa coding. Cepat, aman, dan user-friendly.</p>
        <a href="#" class="btn-primary">Coba Sekarang</a>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="features">
    <div class="container">
        <h2 class="section-title">Fitur Unggulan</h2>
        <div class="grid">
            <div class="card">
                <div class="card-icon">⚡</div>
                <h3>Performa Tinggi</h3>
                <p>Website loading cepat dengan optimasi SEO otomatis untuk meningkatkan visibilitas.</p>
            </div>
            <div class="card">
                <div class="card-icon">📱</div>
                <h3>Responsif Penuh</h3>
                <p>Tampilan sempurna di semua perangkat, dari desktop hingga mobile.</p>
            </div>
            <div class="card">
                <div class="card-icon">🔒</div>
                <h3>Keamanan Terjamin</h3>
                <p>Proteksi data dengan enkripsi SSL dan backup otomatis.</p>
            </div>
            <div class="card">
                <div class="card-icon">🎨</div>
                <h3>Template Modern</h3>
                <p>Beragam template siap pakai yang dapat dikustomisasi sesuai kebutuhan.</p>
            </div>
            <div class="card">
                <div class="card-icon">📊</div>
                <h3>Analytics Terintegrasi</h3>
                <p>Pantau performa website dengan laporan analitik real-time.</p>
            </div>
            <div class="card">
                <div class="card-icon">🤝</div>
                <h3>Dukungan 24/7</h3>
                <p>Tim support siap membantu kapan saja Anda membutuhkan.</p>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="about">
    <div class="container about-flex">
        <img src="https://via.placeholder.com/500x300/2563eb/white?text=CMS+Dashboard" alt="CMS Dashboard">
        <div class="about-content">
            <h2>Tentang CMS Pro</h2>
            <p>CMS Pro adalah platform content management system yang dirancang untuk memudahkan bisnis dan individu dalam membangun website profesional. Dengan antarmuka yang intuitif, Anda dapat mengelola konten, gambar, dan data tanpa pengetahuan teknis yang mendalam.</p>
            <p>Kami berkomitmen untuk memberikan solusi terbaik dengan teknologi terkini, memastikan website Anda selalu up-to-date dan kompetitif di era digital.</p>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials">
    <div class="container">
        <h2 class="section-title">Apa Kata Pengguna Kami</h2>
        <div class="testimonial-grid">
            <div class="testimonial">
                <p>"CMS Pro sangat mudah digunakan. Dalam hitungan jam, website saya sudah online dan terlihat profesional."</p>
                <div class="testimonial-author">- Ahmad S., Pemilik Toko Online</div>
            </div>
            <div class="testimonial">
                <p>"Fitur analytics-nya sangat membantu. Saya bisa melihat performa website secara real-time."</p>
                <div class="testimonial-author">- Siti R., Konsultan Bisnis</div>
            </div>
            <div class="testimonial">
                <p>"Dukungan teknisnya luar biasa. Setiap pertanyaan saya dijawab dengan cepat dan jelas."</p>
                <div class="testimonial-author">- Budi K., Developer Freelance</div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section id="pricing" class="pricing">
    <div class="container">
        <h2 class="section-title">Pilih Paket yang Tepat</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Starter</h3>
                <div class="price">Rp 99K</div>
                <ul>
                    <li>1 Website</li>
                    <li>5GB Storage</li>
                    <li>Basic Templates</li>
                    <li>Email Support</li>
                </ul>
                <a href="#" class="btn-primary">Pilih Paket</a>
            </div>
            <div class="pricing-card">
                <h3>Professional</h3>
                <div class="price">Rp 299K</div>
                <ul>
                    <li>5 Website</li>
                    <li>50GB Storage</li>
                    <li>Premium Templates</li>
                    <li>Priority Support</li>
                    <li>Advanced Analytics</li>
                </ul>
                <a href="#" class="btn-primary">Pilih Paket</a>
            </div>
            <div class="pricing-card">
                <h3>Enterprise</h3>
                <div class="price">Rp 999K</div>
                <ul>
                    <li>Unlimited Website</li>
                    <li>Unlimited Storage</li>
                    <li>Custom Templates</li>
                    <li>24/7 Support</li>
                    <li>White-label Option</li>
                </ul>
                <a href="#" class="btn-primary">Pilih Paket</a>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact" class="contact">
    <div class="container contact-flex">
        <div class="contact-form">
            <h2>Hubungi Kami</h2>
            <form>
                <div class="form-group">
                    <input type="text" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <textarea rows="5" placeholder="Pesan Anda" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Kirim Pesan</button>
            </form>
        </div>
        <div class="contact-info">
            <h3>Informasi Kontak</h3>
            <p><strong>Alamat:</strong> Jl. Teknologi No. 123, Jakarta</p>
            <p><strong>Telepon:</strong> +62 21 1234 5678</p>
            <p><strong>Email:</strong> info@cmspro.com</p>
            <p><strong>Jam Kerja:</strong> Senin - Jumat, 09:00 - 17:00 WIB</p>
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

</body>
</html>
