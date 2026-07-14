<header id="home" class="landing-home-hero" aria-labelledby="landing-home-title">
    <div class="container landing-home-hero__container">
        <img
            src="{{ asset('images/image 1.png') }}"
            alt="Logo NUIST LP Ma'arif NU PWNU DIY"
            class="landing-home-hero__logo-image animate zoom-soft"
            width="420"
            height="100"
            loading="eager"
            fetchpriority="high"
            decoding="async">

        <h1 id="landing-home-title" class="landing-home-hero__title animate fade-up">
            Nuist - Sistem Informasi Digital
        </h1>

        <h2 class="landing-home-hero__subtitle animate fade-up delay-1">
            LP. Ma'arif NU PWNU DIY
        </h2>

        <p class="landing-home-hero__description animate fade-up delay-2">
            {{ $landing->content_hero ?? 'Kelola data kelembagaan, aktivitas, sistem informasi dan layanan dalam satu aplikasi yang modern, aman, dan mudah digunakan.' }}
        </p>

        <img
            src="{{ asset('images/image 2.png') }}"
            alt="Tampilan aplikasi NUIST untuk pengelolaan administrasi sekolah"
            class="landing-home-hero__showcase-image animate zoom-soft delay-3"
            width="920"
            height="500"
            loading="eager"
            fetchpriority="high"
            decoding="async">
    </div>
</header>
