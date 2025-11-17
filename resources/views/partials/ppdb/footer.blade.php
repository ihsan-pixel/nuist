<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo dan Deskripsi -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    {{-- <img src="{{ asset('images/logo1.png') }}" alt="Logo NUIST" class="me-2" style="height: 40px; width: auto;"> --}}
                    <span class="fw-bold text-primary">PPDB NUIST</span>
                </div>
                <p class="mb-3">Sistem Penerimaan Peserta Didik Baru Madrasah di bawah naungan LP. Ma'arif NU PWNU D.I. Yogyakarta untuk tahun pelajaran 2026/2027.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Link Cepat -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('ppdb.index') }}" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="#sekolah" class="text-light text-decoration-none">Sekolah</a></li>
                    <li class="mb-2"><a href="#about" class="text-light text-decoration-none">Tentang</a></li>
                    <li class="mb-2"><a href="#kontak" class="text-light text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Panduan Pendaftaran</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak Support</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status Pendaftaran</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 col-md-4">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0">Jl. KH. Wahid Hasyim No. 123</p>
                        <p class="mb-0">Jakarta Pusat, 10250</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                    <p class="mb-0">(021) 1234-5678</p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                    <p class="mb-0">ppdb@nuist.id</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2025 PPDB NUIST. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Powered by <a href="#" class="text-primary text-decoration-none">Tim NUIST Developer</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px; z-index: 1050;">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    // Back to Top functionality
    const backToTopBtn = document.getElementById('backToTop');

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
</script>
