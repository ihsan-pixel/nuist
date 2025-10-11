<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tenaga Pendidik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .modal-enter { animation: fadeIn 0.3s ease-out, slideUp 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); } to { transform: translateY(0); } }
        .photo-hover:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(22, 160, 133, 0.5); }
    </style>
</head>
<body class="bg-gray-100">
    @if(isset($tenagaPendidik))
    <!-- Modal Overlay -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm modal-enter">
        <!-- Modal Content -->
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="bg-[#16A085] text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Detail Tenaga Pendidik</h2>
                <button onclick="closeModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-all duration-200">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Left Column -->
                    <div class="lg:w-1/3 flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            @if($tenagaPendidik->avatar)
                                <img src="{{ asset('storage/app/public/' . $tenagaPendidik->avatar) }}"
                                     alt="Foto {{ $tenagaPendidik->name }}"
                                     class="w-32 h-32 rounded-full border-4 border-gradient-to-r from-[#16A085] to-[#117A65] shadow-lg photo-hover transition-all duration-300">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center shadow-lg">
                                    <i data-lucide="user" class="w-16 h-16 text-gray-600"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-semibold text-[#117A65] mb-2">{{ $tenagaPendidik->name }}</h3>
                        <div class="flex items-center mb-3 text-gray-600">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                            {{ $tenagaPendidik->email }}
                        </div>
                        @if($tenagaPendidik->statusKepegawaian)
                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium" title="Status Kepegawaian">
                            {{ $tenagaPendidik->statusKepegawaian->name }}
                        </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="lg:w-2/3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="phone" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">No HP</p>
                                    <p class="font-medium">{{ $tenagaPendidik->no_hp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="file-text" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">NIP</p>
                                    <p class="font-medium cursor-pointer hover:text-[#16A085]" onclick="copyToClipboard('{{ $tenagaPendidik->nip ?? '-' }}')">{{ $tenagaPendidik->nip ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="hash" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">NUPTK</p>
                                    <p class="font-medium cursor-pointer hover:text-[#16A085]" onclick="copyToClipboard('{{ $tenagaPendidik->nuptk ?? '-' }}')">{{ $tenagaPendidik->nuptk ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="file-text" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">NPK</p>
                                    <p class="font-medium">{{ $tenagaPendidik->npk ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="map-pin" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Tempat Lahir</p>
                                    <p class="font-medium">{{ $tenagaPendidik->tempat_lahir ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="calendar" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Lahir</p>
                                    <p class="font-medium">{{ $tenagaPendidik->tanggal_lahir ? $tenagaPendidik->tanggal_lahir->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl md:col-span-2">
                                <i data-lucide="home" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Alamat</p>
                                    <p class="font-medium">{{ $tenagaPendidik->alamat ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="graduation-cap" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Pendidikan</p>
                                    <p class="font-medium">{{ $tenagaPendidik->pendidikan_terakhir ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="book-open" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Program Studi</p>
                                    <p class="font-medium">{{ $tenagaPendidik->program_studi ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="credit-card" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Kartanu</p>
                                    <p class="font-medium">{{ $tenagaPendidik->kartanu ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="calendar-check" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Tahun Lulus</p>
                                    <p class="font-medium">{{ $tenagaPendidik->tahun_lulus ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="clock" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">TMT</p>
                                    <p class="font-medium">{{ $tenagaPendidik->tmt ? $tenagaPendidik->tmt->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="user-check" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Mengajar</p>
                                    <p class="font-medium">{{ $tenagaPendidik->mengajar ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <i data-lucide="briefcase" class="w-5 h-5 text-[#16A085] mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Ketugasan</p>
                                    <p class="font-medium">{{ $tenagaPendidik->ketugasan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl font-medium transition-all duration-200">
                    Tutup
                </button>
                <button class="bg-[#16A085] hover:bg-gradient-to-r hover:from-[#16A085] hover:to-[#117A65] text-white px-6 py-2 rounded-xl font-medium transition-all duration-200">
                    Edit Data
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Close modal function
        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.style.animation = 'fadeOut 0.3s ease-out, slideDown 0.3s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
                // You might want to redirect or hide the modal in your app
            }, 300);
        }

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Simple feedback, you can enhance this
                alert('Teks berhasil disalin: ' + text);
            });
        }

        // Add fadeOut and slideDown animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
            @keyframes slideDown { from { transform: translateY(0); } to { transform: translateY(20px); } }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
