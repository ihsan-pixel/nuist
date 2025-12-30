<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Landing Page Modern</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #f9fafb;
        color: #333;
    }

    /* CONTAINER */
    .container {
        max-width: 1100px;
        margin: auto;
        padding: 0 20px;
    }

    /* NAVBAR */
    .navbar {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        position: sticky;
        top: 0;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    .nav-menu a {
        text-decoration: none;
        color: #555;
        font-weight: 500;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
    }

    /* HERO */
    .hero {
        padding: 80px 0;
    }

    .hero-flex {
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .hero h2 {
        font-size: 40px;
        margin-bottom: 15px;
    }

    /* FEATURES */
    .features {
        padding: 60px 0;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background: white;
        padding: 30px;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,.05);
    }

    /* FOOTER */
    .footer {
        background: #111827;
        color: #9ca3af;
        text-align: center;
        padding: 20px;
    }

    /* RESPONSIVE */
    @media(max-width:768px) {
        .hero-flex {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-flex">
        <h1 class="logo">MyBrand</h1>
        <ul class="nav-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Fitur</a></li>
            <li><a href="#">Harga</a></li>
            <li><a href="#">Kontak</a></li>
        </ul>
        <a href="#" class="btn-primary">Mulai</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container hero-flex">
        <div>
            <h2>Bangun Website Modern & Profesional</h2>
            <p>Landing page cepat, responsif, dan siap meningkatkan konversi.</p>
            <a href="#" class="btn-primary">Coba Sekarang</a>
        </div>
        <img src="https://via.placeholder.com/400" alt="Hero Image">
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="container grid">
        <div class="card">⚡ Cepat</div>
        <div class="card">📱 Responsif</div>
        <div class="card">🎨 Modern</div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2025 MyBrand. All rights reserved.</p>
</footer>

</body>
</html>
