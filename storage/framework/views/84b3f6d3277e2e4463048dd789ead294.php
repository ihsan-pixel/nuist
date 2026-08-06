<section id="about" class="landing-home-profile" aria-labelledby="landing-home-profile-title">
    <h2 id="landing-home-profile-title" class="section-title animate fade-up">Profile Nuist</h2>

    <div class="container landing-home-profile__intro animate fade-up delay-1">
        <div class="landing-home-profile__intro-copy">
            <p>
                Nuist menghadirkan ekosistem aplikasi terintegrasi yang dirancang untuk mendukung pengelolaan administrasi sekolah secara menyeluruh. Melalui Nuist Desktop dan Nuist Mobile, sekolah dapat mengelola data, aktivitas, dan kehadiran secara terpusat, akurat, serta mudah diakses oleh administrator, tenaga pendidik, dan kepala sekolah dalam satu sistem yang saling terhubung.
            </p>
        </div>
    </div>

    <div class="container landing-home-profile__gallery animate fade-up delay-2">
        <img
            src="<?php echo e(asset('images/image 3.png')); ?>"
            alt="Tampilan dashboard Nuist Desktop"
            class="landing-home-profile__gallery-image animate fade-left delay-1"
            width="520"
            height="230"
            loading="lazy"
            decoding="async">
        <img
            src="<?php echo e(asset('images/image 4.png')); ?>"
            alt="Tampilan aplikasi Nuist Mobile"
            class="landing-home-profile__gallery-image animate fade-right delay-2"
            width="520"
            height="230"
            loading="lazy"
            decoding="async">
    </div>

    <div class="container landing-home-profile__products animate fade-up delay-3">
        <article class="landing-home-profile__product animate fade-left">
            <h3 class="landing-home-profile__product-title">
                <span class="landing-home-profile__product-dot" aria-hidden="true"></span>
                Nuist Desktop
            </h3>
            <p>
                <?php echo e($landing->content_2_profile ?? 'Aplikasi khusus untuk administrator sekolah dalam mengelola data sekolah dan data tenaga pendidik secara terpusat, aman, dan efisien. Dirancang untuk mendukung kebutuhan administrasi modern, Nuist Desktop membantu menyederhanakan pengelolaan data, meningkatkan akurasi informasi, serta mendukung pengambilan keputusan berbasis data.'); ?>

            </p>
        </article>

        <article class="landing-home-profile__product animate fade-right delay-1">
            <h3 class="landing-home-profile__product-title">
                <span class="landing-home-profile__product-dot" aria-hidden="true"></span>
                Nuist Mobile
            </h3>
            <p>
                <?php echo e($landing->content_3_profile ?? 'Aplikasi berbasis mobile yang dirancang khusus untuk tenaga pendidik dan kepala sekolah dalam melakukan presensi, presensi mengajar, pengajuan izin, serta penyesuaian data pribadi secara praktis dan real-time. Aplikasi ini mendukung kemudahan akses, akurasi data, dan efisiensi administrasi dalam satu platform terpadu.'); ?>

            </p>
        </article>
    </div>
</section>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/partials/home/profile.blade.php ENDPATH**/ ?>