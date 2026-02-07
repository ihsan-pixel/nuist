@extends('layouts.master-without-nav')

@section('title', 'Dashboard Peserta - Instrument Talenta')

@section('body')
<body class="bg-gray-50 min-h-screen">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Peserta</h1>
                <p class="text-gray-600 mt-2">Selamat datang kembali! Lanjutkan perjalanan pembelajaran Anda</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Progress Keseluruhan</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-700 mt-1">75% Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book-open text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kursus Diikuti</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kursus Selesai</p>
                    <p class="text-2xl font-bold text-gray-800">8</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sedang Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-800">4</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-certificate text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sertifikat</p>
                    <p class="text-2xl font-bold text-gray-800">6</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Courses -->
    <div class="card mb-8">
        <div class="card-body">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Kursus Sedang Diikuti</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Course Card 1 -->
                <div class="course-card">
                    <div class="flex items-start space-x-4">
                        <img src="https://via.placeholder.com/80x80/4F46E5/FFFFFF?text=IT" alt="Course" class="course-image">
                        <div class="flex-1">
                            <h3 class="course-title">Pemrograman Web Modern</h3>
                            <p class="course-description">Pelajari HTML, CSS, JavaScript</p>
                            <div class="progress-bar mb-2">
                                <div class="progress-fill bg-blue-600" style="width: 60%"></div>
                            </div>
                            <p class="text-xs text-gray-500">60% selesai • 2 jam tersisa</p>
                        </div>
                    </div>
                    <button class="btn btn-primary w-full mt-4">
                        Lanjutkan Belajar
                    </button>
                </div>

                <!-- Course Card 2 -->
                <div class="course-card">
                    <div class="flex items-start space-x-4">
                        <img src="https://via.placeholder.com/80x80/059669/FFFFFF?text=DS" alt="Course" class="course-image">
                        <div class="flex-1">
                            <h3 class="course-title">Data Science Fundamentals</h3>
                            <p class="course-description">Analisis data dengan Python</p>
                            <div class="progress-bar mb-2">
                                <div class="progress-fill bg-green-600" style="width: 30%"></div>
                            </div>
                            <p class="text-xs text-gray-500">30% selesai • 8 jam tersisa</p>
                        </div>
                    </div>
                    <button class="btn btn-success w-full mt-4">
                        Lanjutkan Belajar
                    </button>
                </div>

                <!-- Course Card 3 -->
                <div class="course-card">
                    <div class="flex items-start space-x-4">
                        <img src="https://via.placeholder.com/80x80/DC2626/FFFFFF?text=UX" alt="Course" class="course-image">
                        <div class="flex-1">
                            <h3 class="course-title">UI/UX Design Principles</h3>
                            <p class="course-description">Desain antarmuka yang baik</p>
                            <div class="progress-bar mb-2">
                                <div class="progress-fill bg-red-600" style="width: 85%"></div>
                            </div>
                            <p class="text-xs text-gray-500">85% selesai • 1 jam tersisa</p>
                        </div>
                    </div>
                    <button class="btn btn-red w-full mt-4">
                        Lanjutkan Belajar
                    </button>
                </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-body">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Aktivitas Terbaru</h2>
            <div class="space-y-4">
                <div class="activity-item">
                    <div class="activity-icon blue">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Menyelesaikan modul "CSS Grid Layout"</p>
                        <p class="activity-meta">2 jam yang lalu</p>
                    </div>
                    <span class="text-green-600 font-semibold">+10 XP</span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Menyelesaikan kursus "JavaScript Basics"</p>
                        <p class="activity-meta">1 hari yang lalu</p>
                    </div>
                    <span class="text-green-600 font-semibold">+50 XP</span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon purple">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Mendapatkan sertifikat "Web Development"</p>
                        <p class="activity-meta">3 hari yang lalu</p>
                    </div>
                    <span class="text-purple-600 font-semibold">Sertifikat</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/instumen-talenta.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection
