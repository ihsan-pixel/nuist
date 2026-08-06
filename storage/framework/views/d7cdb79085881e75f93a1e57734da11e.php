<?php $__env->startSection('title', 'Dashboard Fasilitator - Instrument Talenta'); ?>

<?php $__env->startSection('body'); ?>
<body class="bg-gray-50 min-h-screen">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Fasilitator</h1>
                <p class="text-gray-600 mt-2">Pantau dan fasilitasi proses pembelajaran peserta</p>
            </div>
            <div class="flex space-x-4">
                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-comments mr-2"></i>Forum Diskusi
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Laporan Progress
                </button>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Peserta Aktif</p>
                    <p class="text-2xl font-bold text-gray-800">892</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pertanyaan Baru</p>
                    <p class="text-2xl font-bold text-gray-800">23</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Tugas Dinilai</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Perlu Perhatian</p>
                    <p class="text-2xl font-bold text-gray-800">7</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Sessions & Discussions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Active Learning Sessions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Sesi Pembelajaran Aktif</h3>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">Web Development Bootcamp</h4>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Live</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Pemateri: Ahmad Rahman</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">45 peserta online</span>
                        <button class="text-blue-600 hover:text-blue-800">Bergabung</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">Data Science Workshop</h4>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Scheduled</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Pemateri: Siti Nurhaliza</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Mulai dalam 30 menit</span>
                        <button class="text-blue-600 hover:text-blue-800">Persiapkan</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">UI/UX Design Session</h4>
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Ended</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Pemateri: Budi Santoso</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Selesai 2 jam lalu</span>
                        <button class="text-blue-600 hover:text-blue-800">Lihat Rekaman</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Discussions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Diskusi Terbaru</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-semibold text-gray-800 text-sm">Kesulitan dengan CSS Grid</p>
                        <span class="text-xs text-gray-500">5 min ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Peserta: John Doe - "Saya bingung dengan positioning di CSS Grid"</p>
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Bantu Jawab</button>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-semibold text-gray-800 text-sm">Pertanyaan tentang Machine Learning</p>
                        <span class="text-xs text-gray-500">12 min ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Peserta: Jane Smith - "Apa perbedaan supervised dan unsupervised learning?"</p>
                    <button class="text-green-600 hover:text-green-800 text-sm">Sudah Dijawab</button>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-semibold text-gray-800 text-sm">Masalah dengan Python Environment</p>
                        <span class="text-xs text-gray-500">1 hour ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Peserta: Mike Johnson - "Package tidak bisa diinstall"</p>
                    <button class="text-yellow-600 hover:text-yellow-800 text-sm">Perlu Perhatian</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Progress Monitoring -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Monitoring Progress Peserta</h2>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option>Semua Kursus</option>
                    <option>Web Development</option>
                    <option>Data Science</option>
                    <option>UI/UX Design</option>
                </select>
                <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                    Export Data
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Peserta</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Kursus</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Progress</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Terakhir Aktif</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://via.placeholder.com/40x40/4F46E5/FFFFFF?text=JD" alt="Student" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-800">John Doe</p>
                                    <p class="text-sm text-gray-500">john@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">Web Development</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="text-sm text-gray-600">75%</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Aktif</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">2 jam lalu</td>
                        <td class="py-3 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Detail</button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://via.placeholder.com/40x40/059669/FFFFFF?text=JS" alt="Student" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-800">Jane Smith</p>
                                    <p class="text-sm text-gray-500">jane@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">Data Science</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                                <span class="text-sm text-gray-600">45%</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Perlu Bantuan</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">1 hari lalu</td>
                        <td class="py-3 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Bantu</button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://via.placeholder.com/40x40/DC2626/FFFFFF?text=MB" alt="Student" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-800">Mike Brown</p>
                                    <p class="text-sm text-gray-500">mike@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">UI/UX Design</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                                <span class="text-sm text-gray-600">90%</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Aktif</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">30 min lalu</td>
                        <td class="py-3 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="flex items-center justify-center space-x-3 bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-bullhorn"></i>
                <span>Kirim Pengumuman</span>
            </button>
            <button class="flex items-center justify-center space-x-3 bg-green-600 text-white py-4 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-users"></i>
                <span>Kelola Grup Belajar</span>
            </button>
            <button class="flex items-center justify-center space-x-3 bg-purple-600 text-white py-4 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-chart-bar"></i>
                <span>Generate Laporan</span>
            </button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/instumen-talenta.css')); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/fasilitator.blade.php ENDPATH**/ ?>