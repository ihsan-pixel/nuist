<footer class="landing-footer bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="footer-col">
                <div class="footer-logo mb-3">
                    <div class="footer-logo-button">
                        <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="NUIST Logo" loading="lazy" decoding="async">
                    </div>
                </div>
                <p class="mb-3">Sistem Informasi Digital LPMNU PWNU DIY untuk pengelolaan data sekolah, tenaga pendidik, dan aktivitas madrasah secara terintegrasi.</p>
                <div class="social-links">
                    <a href="https://lpmnudiy.id/" class="text-light me-2" aria-label="Website LPMNU"><i class="bi bi-globe"></i></a>
                    <a href="https://web.facebook.com/@maarifnudiy/?_rdc=1&_rdr#" class="text-light me-2" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://wa.me/6281125055675?text=Halo%20Admin%20Nuist%20LPMNU%20PWNU%20DIY" class="text-light me-2" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.instagram.com/maarifnudiy/profilecard/?igsh=MTZzOXVzOHYyNHlibQ%3D%3D" class="text-light me-2" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.youtube.com/@lpmaarifnupwnudiyogyakarta9092" class="text-light" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h5 class="fw-bold mb-3">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>" class="text-light text-decoration-none" data-nav-ajax="true">Beranda</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing.produk')); ?>" class="text-light text-decoration-none" data-nav-ajax="true">Produk</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing.sekolah')); ?>" class="text-light text-decoration-none" data-nav-ajax="true">Sekolah</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing.tentang')); ?>" class="text-light text-decoration-none" data-nav-ajax="true">Tentang</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing.kontak')); ?>" class="text-light text-decoration-none" data-nav-ajax="true">Kontak</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('mobile.login')); ?>" class="text-light text-decoration-none">Masuk Aplikasi Nuist</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Panduan</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak Support</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill me-3 mt-1" style="color: #eda711"></i>
                    <div>
                        <p class="mb-0"><?php echo e($yayasan->alamat ?? 'Jl. KH. Wahid Hasyim No. 123'); ?></p>
                        <p class="mb-0">Yogyakarta, 55281</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill me-3" style="color: #eda711"></i>
                    <p class="mb-0">0811 2505 5675</p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill me-3" style="color: #eda711"></i>
                    <p class="mb-0">nuistnu@gmail.com</p>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0" style="color: #eda711">&copy; <?php echo e(date('Y')); ?> NUIST. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Powered by <a href="#" class="text-decoration-none" style="color: #eda711">Tim NUIST Developer</a></p>
            </div>
        </div>
    </div>
</footer>

<button id="backToTop" class="landing-back-to-top btn btn-primary position-fixed" type="button" aria-label="Kembali ke atas">
    <i class='bx bxs-up-arrow'></i>
</button>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/footer.blade.php ENDPATH**/ ?>