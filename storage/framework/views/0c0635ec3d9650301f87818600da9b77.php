<style>
/* FOOTER Styles */
.bg-dark {
    background-color: #1f2937;
}

.text-light {
    color: #f3f4f6;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.mt-5 {
    margin-top: 3rem;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.g-4 {
    gap: 1.5rem;
}

.footer-col {
    flex: 1;
    min-width: 200px;
    padding: 0 15px;
}

.fw-bold {
    font-weight: 700;
}

.text-primary {
    color: #004b4c;
}

.mb-3 {
    margin-bottom: 1rem;
}

.mb-0 {
    margin-bottom: 0;
}

.me-3 {
    margin-right: 1rem;
}

.list-unstyled {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-decoration-none {
    text-decoration: none;
}

.d-flex {
    display: flex;
}

.align-items-center {
    align-items: center;
}

.align-items-start {
    align-items: flex-start;
}

.mt-1 {
    margin-top: 0.25rem;
}

hr.my-4 {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
    border: 0;
    border-top: 1px solid #374151;
}

.text-md-end {
    text-align: right;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.footer-logo img {
    height: 50px;
}

.footer-logo span {
    font-size: 24px;
    font-weight: 700;
    color: #004b4c;
}

.social-links {
    display: flex;
    margin-top: 15px;
}

.social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: #004b4c;
    transform: translateY(-3px);
}

/* Back to Top Button */
#backToTop {
    position: fixed;
    bottom: 20px;
    right: 20px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    z-index: 1050;
    background: linear-gradient(135deg, #004b4c, #006666);
    color: white;
    border: none;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

#backToTop i {
    font-size: 24px;
    line-height: 1;
}

#backToTop:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 75, 76, 0.4);
}

/* Responsive Footer */
@media (max-width: 1200px) {
    .footer-col {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 768px) {
    .footer-col {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .row {
        flex-direction: column;
    }

    .text-md-end {
        text-align: left;
        margin-top: 1rem;
    }
}
</style>

<!-- FOOTER -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo dan Deskripsi -->
            <div class="footer-col">
                <div class="footer-logo mb-3">
                    <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="NUIST Logo">
                    <span>NUIST</span>
                </div>
                <p class="mb-3">Sistem Informasi Digital LPMNU PWNU DIY untuk pengelolaan data sekolah, tenaga pendidik, dan aktivitas madrasah secara terintegrasi.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Link Cepat -->
            <div class="footer-col">
                <h5 class="fw-bold mb-3">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing.sekolah')); ?>" class="text-light text-decoration-none">Sekolah</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>#about" class="text-light text-decoration-none">Tentang</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>#features" class="text-light text-decoration-none">Fitur</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="footer-col">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Panduan</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak Support</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="footer-col">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0"><?php echo e($yayasan->alamat ?? 'Jl. KH. Wahid Hasyim No. 123'); ?></p>
                        <p class="mb-0">Yogyakarta, 55281</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                    <p class="mb-0">0811 2505 5675</p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                    <p class="mb-0">nuistnu@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?php echo e(date('Y')); ?> NUIST. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Powered by <a href="#" class="text-primary text-decoration-none">Tim NUIST Developer</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px; z-index: 1050;">
    <i class='bx bxs-up-arrow'></i>
</button>

<script>
    // Back to Top functionality
    const backToTopBtn = document.getElementById('backToTop');

    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
</script>

<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/footer.blade.php ENDPATH**/ ?>