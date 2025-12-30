<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NUIST - Sistem Informasi Tenaga Pendidik Madrasah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        /* Custom styles for landing page */
        body {
            font-family: 'Figtree', sans-serif;
        }

        .transition {
            transition: all 0.3s ease;
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    /* Import Google Fonts */ @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap'); /* Reset and base styles */ * { margin: 0; padding: 0; box-sizing: border-box; } body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; overflow-x: hidden; } /* Custom animations */ @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } } @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } } @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } } @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } } @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } } /* Navbar styles */ nav { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid rgba(0, 0, 0, 0.1); } /* Hero section styles */ .hero-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-size: 400% 400%; animation: gradientShift 8s ease infinite; } .hero-content { animation: fadeInUp 1s ease-out; } /* Button styles */ .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; padding: 16px 32px; font-size: 18px; font-weight: 600; color: white; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); } .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6); } .btn-secondary { background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 16px 32px; font-size: 18px; font-weight: 600; color: #374151; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); } .btn-secondary:hover { background: #f9fafb; border-color: #d1d5db; transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); } /* Feature cards */ .feature-card { background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2); position: relative; overflow: hidden; } .feature-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #667eea, #764ba2); transform: scaleX(0); transition: transform 0.3s ease; } .feature-card:hover::before { transform: scaleX(1); } .feature-card:hover { transform: translateY(-10px); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); } .feature-icon { width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 32px; } /* Footer styles */ footer { background: linear-gradient(135deg, #1f2937 0%, #374151 100%); position: relative; } footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent); } .footer-link { color: #9ca3af; text-decoration: none; transition: color 0.3s ease; } .footer-link:hover { color: #667eea; } .social-icon { width: 40px; height: 40px; border-radius: 50%; background: #374151; display: flex; align-items: center; justify-content: center; color: #9ca3af; transition: all 0.3s ease; text-decoration: none; } .social-icon:hover { background: #667eea; color: white; transform: translateY(-2px); } /* Responsive design */ @media (max-width: 768px) { .hero-content h1 { font-size: 2.5rem; } .feature-card { padding: 24px; } .btn-primary, .btn-secondary { width: 100%; text-align: center; margin-bottom: 16px; } } /* Custom scrollbar */ ::-webkit-scrollbar { width: 8px; } ::-webkit-scrollbar-track { background: #f1f1f5; } ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; } ::-webkit-scrollbar-thumb:hover { background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%); } /* Smooth scrolling */ html { scroll-behavior: smooth; } /* Loading animation */ .animate-fade-in { animation: fadeInUp 0.8s ease-out; } .animate-fade-in-left { animation: fadeInLeft 0.8s ease-out; } .animate-fade-in-right { animation: fadeInRight 0.8s ease-out; } .animate-pulse { animation: pulse 2s infinite; } /* Glass morphism effect */ .glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); } /* Gradient text */ .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; } /* Shadow effects */ .shadow-soft { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); } .shadow-medium { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); } .shadow-strong { box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15); } /* Typography */ h1, h2, h3, h4, h5, h6 { font-weight: 700; line-height: 1.2; } h1 { font-size: 3.5rem; margin-bottom: 1rem; } h2 { font-size: 2.5rem; margin-bottom: 1rem; } h3 { font-size: 2rem; margin-bottom: 0.75rem; } p { margin-bottom: 1rem; } /* Utility classes */ .text-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; } .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); } .bg-gradient-secondary { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); } .bg-gradient-accent { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); } </style>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <main class="min-h-screen flex flex-col">

        <!-- HERO -->
        <section class="flex-grow flex items-center">
            <div class="max-w-7xl mx-auto px-6 py-20 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    NUIST
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                    Sistem Informasi Tenaga Pendidik Madrasah
                </p>

                <p class="max-w-xl mx-auto text-gray-500 dark:text-gray-400 mb-8">
                    Platform terintegrasi untuk manajemen presensi, jadwal mengajar,
                    dan administrasi tenaga pendidik madrasah di lingkungan Nahdlatul Ulama.
                </p>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                        Masuk
                    </a>

                    <a href="#features"
                       class="px-6 py-3 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section id="features" class="bg-white dark:bg-gray-900 py-20">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Fitur Unggulan</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Solusi lengkap untuk mengelola operasional madrasah
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <div class="p-6 rounded-xl bg-gray-50 dark:bg-gray-800 shadow hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-900 dark:text-white">
                            Presensi Digital
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Sistem presensi berbasis lokasi dengan verifikasi wajah untuk memastikan kehadiran tenaga pendidik yang akurat.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl bg-gray-50 dark:bg-gray-800 shadow hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-900 dark:text-white">
                            Manajemen Jadwal
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Kelola jadwal mengajar, presensi mengajar, dan monitoring kegiatan pendidikan dengan mudah.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl bg-gray-50 dark:bg-gray-800 shadow hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-900 dark:text-white">
                            PPDB Online
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Sistem penerimaan peserta didik baru yang terintegrasi dengan verifikasi dan seleksi otomatis.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-gray-800 text-gray-300 py-10">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">

                <div>
                    <h4 class="font-semibold text-white mb-3">NUIST</h4>
                    <p class="text-sm">
                        Sistem Informasi Tenaga Pendidik Madrasah di lingkungan Nahdlatul Ulama.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-3">Fitur</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition">Presensi Digital</a></li>
                        <li><a href="#features" class="hover:text-white transition">Manajemen Jadwal</a></li>
                        <li><a href="#features" class="hover:text-white transition">PPDB Online</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-3">Link</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="#features" class="hover:text-white transition">Tentang</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-3">Kontak</h4>
                    <p class="text-sm">
                        info@nuist.id<br>
                        Jakarta, Indonesia
                    </p>
                </div>

            </div>

            <div class="text-center text-sm mt-8 text-gray-500 border-t border-gray-700 pt-6">
                © 2024 NUIST - Sistem Informasi Tenaga Pendidik Madrasah. All rights reserved.
            </div>
        </footer>

    </main>
</body>
</html>
