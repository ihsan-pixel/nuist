<?php $__env->startSection('title', 'Dashboard Pemateri - Instrument Talenta'); ?>

<?php $__env->startSection('body'); ?>
<body class="bg-gray-50 min-h-screen">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Pemateri</h1>
                <p class="text-gray-600 mt-2">Kelola materi pembelajaran dan pantau perkembangan peserta</p>
            </div>
            <div class="flex space-x-4">
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Kursus Baru
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Upload Materi
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Kursus</p>
                    <p class="text-2xl font-bold text-gray-800">15</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-800">1,247</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Rating Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-800">4.8</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Tingkat Kelulusan</p>
                    <p class="text-2xl font-bold text-gray-800">89%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kursus Saya</h2>
            <button class="text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Semua
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Course Card 1 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <img src="https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Web+Dev" alt="Course" class="w-full h-32 object-cover rounded-lg mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Pemrograman Web Modern</h3>
                <p class="text-sm text-gray-600 mb-3">Belajar HTML, CSS, JavaScript, dan framework modern</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span><i class="fas fa-users mr-1"></i>245 peserta</span>
                    <span><i class="fas fa-clock mr-1"></i>40 jam</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Edit Kursus
                    </button>
                    <button class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>

            <!-- Course Card 2 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <img src="https://via.placeholder.com/300x200/059669/FFFFFF?text=Data+Science" alt="Course" class="w-full h-32 object-cover rounded-lg mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Data Science dengan Python</h3>
                <p class="text-sm text-gray-600 mb-3">Analisis data, machine learning, dan visualisasi</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span><i class="fas fa-users mr-1"></i>189 peserta</span>
                    <span><i class="fas fa-clock mr-1"></i>35 jam</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Edit Kursus
                    </button>
                    <button class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>

            <!-- Course Card 3 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <img src="https://via.placeholder.com/300x200/DC2626/FFFFFF?text=UI+UX" alt="Course" class="w-full h-32 object-cover rounded-lg mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">UI/UX Design Masterclass</h3>
                <p class="text-sm text-gray-600 mb-3">Prinsip desain antarmuka dan pengalaman pengguna</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span><i class="fas fa-users mr-1"></i>156 peserta</span>
                    <span><i class="fas fa-clock mr-1"></i>28 jam</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Edit Kursus
                    </button>
                    <button class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Student Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Ahmad mendaftar kursus "Web Development"</p>
                        <p class="text-xs text-gray-500">2 jam yang lalu</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Sari memberikan rating 5 bintang</p>
                        <p class="text-xs text-gray-500">4 jam yang lalu</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm">Budi menyelesaikan kursus "Data Science"</p>
                        <p class="text-xs text-gray-500">1 hari yang lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Progress Overview -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Progress Peserta</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://via.placeholder.com/40x40/4F46E5/FFFFFF?text=JD" alt="Student" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-800">John Doe</p>
                            <p class="text-sm text-gray-500">Web Development</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-green-600">85%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://via.placeholder.com/40x40/059669/FFFFFF?text=JS" alt="Student" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-800">Jane Smith</p>
                            <p class="text-sm text-gray-500">Data Science</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-blue-600">62%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 62%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://via.placeholder.com/40x40/DC2626/FFFFFF?text=MB" alt="Student" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-800">Mike Brown</p>
                            <p class="text-sm text-gray-500">UI/UX Design</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-purple-600">91%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 91%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/instumen-talenta.css')); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/pemateri.blade.php ENDPATH**/ ?>