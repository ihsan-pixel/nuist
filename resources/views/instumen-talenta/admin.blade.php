@extends('layouts.master-without-nav')

@section('title', 'Dashboard Admin - Instrument Talenta')

@section('body')
<body class="bg-gray-50 min-h-screen">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
                <p class="text-gray-600 mt-2">Kelola sistem dan pengaturan platform Instrument Talenta</p>
            </div>
            <div class="flex space-x-4">
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-cog mr-2"></i>Pengaturan Sistem
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Laporan Global
                </button>
            </div>
        </div>
    </div>

    <!-- System Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Pengguna</p>
                    <p class="text-2xl font-bold text-gray-800">2,847</p>
                    <p class="text-xs text-green-600 mt-1">+12% dari bulan lalu</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Kursus</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                    <p class="text-xs text-green-600 mt-1">+8 kursus baru</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sertifikat Dikeluarkan</p>
                    <p class="text-2xl font-bold text-gray-800">1,203</p>
                    <p class="text-xs text-green-600 mt-1">+15% dari bulan lalu</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Server Uptime</p>
                    <p class="text-2xl font-bold text-gray-800">99.9%</p>
                    <p class="text-xs text-green-600 mt-1">Status: Optimal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- User Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Manajemen Pengguna</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Peserta Terdaftar</p>
                            <p class="text-sm text-gray-500">2,156 pengguna aktif</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Pemateri Aktif</p>
                            <p class="text-sm text-gray-500">89 instruktur</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-cog text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Fasilitator</p>
                            <p class="text-sm text-gray-500">23 fasilitator aktif</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
            </div>
        </div>

        <!-- Content Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Manajemen Konten</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Kursus & Materi</p>
                            <p class="text-sm text-gray-500">156 kursus, 2,340 modul</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Sertifikat & Assessment</p>
                            <p class="text-sm text-gray-500">45 template sertifikat</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-tags text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Kategori & Tag</p>
                            <p class="text-sm text-gray-500">28 kategori, 156 tag</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-semibold">Kelola</button>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- System Health -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Kesehatan Sistem</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Database</span>
                    <span class="text-green-600 font-semibold">Healthy</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 98%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Storage</span>
                    <span class="text-green-600 font-semibold">85% Used</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">CDN</span>
                    <span class="text-green-600 font-semibold">Optimal</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 95%"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mt-1">
                        <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Pengguna baru terdaftar</p>
                        <p class="text-xs text-gray-500">Ahmad Rahman - 5 min ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mt-1">
                        <i class="fas fa-book text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Kursus baru dipublikasikan</p>
                        <p class="text-xs text-gray-500">"Machine Learning Basics" - 1 hour ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mt-1">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Laporan bug diterima</p>
                        <p class="text-xs text-gray-500">Issue #1234 - 2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <button class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-plus-circle text-blue-600"></i>
                        <span class="text-sm font-semibold">Tambah Admin Baru</span>
                    </div>
                </button>
                <button class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file-export text-green-600"></i>
                        <span class="text-sm font-semibold">Export Data</span>
                    </div>
                </button>
                <button class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-shield-alt text-red-600"></i>
                        <span class="text-sm font-semibold">Security Audit</span>
                    </div>
                </button>
                <button class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-database text-purple-600"></i>
                        <span class="text-sm font-semibold">Backup Database</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Placeholder -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Analytics & Reports</h2>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option>7 hari terakhir</option>
                    <option>30 hari terakhir</option>
                    <option>3 bulan terakhir</option>
                    <option>1 tahun terakhir</option>
                </select>
                <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i>Download Report
                </button>
            </div>
        </div>

        <!-- Chart Placeholders -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">User Growth Chart</h3>
                <p class="text-gray-500">Grafik pertumbuhan pengguna akan ditampilkan di sini</p>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                <i class="fas fa-chart-bar text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Course Completion Rate</h3>
                <p class="text-gray-500">Statistik penyelesaian kursus akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/instumen-talenta.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection
