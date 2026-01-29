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
    max-width: 1200px;
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

.col-lg-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding: 0 15px;
}

.col-lg-2 {
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
    padding: 0 15px;
}

.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
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

.align-items-center {
    align-items: center;
}

.text-md-end {
    text-align: right;
}

/* Back to Top Button */
#backToTop {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    z-index: 1050;
    background: linear-gradient(135deg, #004b4c, #006666);
    color: white;
    border: none;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#backToTop:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 75, 76, 0.4);
}

/* Responsive Footer */
@media (max-width: 992px) {
    .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .col-lg-2 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 768px) {
    .col-lg-2,
    .col-md-4 {
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
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="fw-bold text-primary">NUIST</span>
                </div>
                <p class="mb-3">Sistem Informasi Digital LPMNU PWNU DIY untuk pengelolaan data sekolah, tenaga pendidik, dan aktivitas madrasah secara terintegrasi.</p>
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
                    <li class="mb-2"><a href="{{ route('landing') }}" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('landing.sekolah') }}" class="text-light text-decoration-none">Sekolah</a></li>
                    <li class="mb-2"><a href="{{ route('landing') }}#about" class="text-light text-decoration-none">Tentang</a></li>
                    <li class="mb-2"><a href="{{ route('landing') }}#features" class="text-light text-decoration-none">Fitur</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Panduan</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak Support</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 col-md-4">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0">{{ $yayasan->alamat ?? 'Jl. KH. Wahid Hasyim No. 123' }}</p>
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
                <p class="mb-0">&copy; {{ date('Y') }} NUIST. All rights reserved.</p>
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

