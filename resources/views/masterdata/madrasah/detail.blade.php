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

                        <p class="text-muted"><i class="bx bx-envelope me-1"></i><span id="modal-email"></span></p>
                        <span class="badge bg-success" id="modal-status"></span>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-phone text-primary me-1"></i>No HP:</strong><br>
                                <span id="modal-no_hp">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NIP:</strong><br>
                                <span id="modal-nip">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NUPTK:</strong><br>
                                <span id="modal-nuptk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NPK:</strong><br>
                                <span id="modal-npk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-map-pin text-primary me-1"></i>Tempat Lahir:</strong><br>
                                <span id="modal-tempat_lahir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar text-primary me-1"></i>Tanggal Lahir:</strong><br>
                                <span id="modal-tanggal_lahir">-</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong><i class="bx bx-home text-primary me-1"></i>Alamat:</strong><br>
                                <span id="modal-alamat">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-card text-primary me-1"></i>Kartanu:</strong><br>
                                <span id="modal-kartanu">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-graduation text-primary me-1"></i>Pendidikan:</strong><br>
                                <span id="modal-pendidikan_terakhir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-check text-primary me-1"></i>Tahun Lulus:</strong><br>
                                <span id="modal-tahun_lulus">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-book text-primary me-1"></i>Program Studi:</strong><br>
                                <span id="modal-program_studi">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-event text-primary me-1"></i>TMT:</strong><br>
                                <span id="modal-tmt">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-briefcase text-primary me-1"></i>Ketugasan:</strong><br>
                                <span id="modal-ketugasan">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-chalkboard text-primary me-1"></i>Mengajar:</strong><br>
                                <span id="modal-mengajar">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {
    let table = $("#tenaga-pendidik-table").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#tenaga-pendidik-table_wrapper .col-md-6:eq(0)');

    // Handle View Button Click
    $('#tenaga-pendidik-table').on('click', '.view-btn', function() {
        const data = $(this).data();
        $('#modal-name').text(data.name);
        $('#modal-email').text(data.email);
        $('#modal-status').text(data.status);
        $('#modal-no_hp').text(data.no_hp);
        $('#modal-nip').text(data.nip);
        $('#modal-nuptk').text(data.nuptk);
        $('#modal-npk').text(data.npk);
        $('#modal-tempat_lahir').text(data.tempat_lahir);
        $('#modal-tanggal_lahir').text(data.tanggal_lahir);
        $('#modal-alamat').text(data.alamat);
        $('#modal-kartanu').text(data.kartanu);
        $('#modal-pendidikan_terakhir').text(data.pendidikan_terakhir);
        $('#modal-tahun_lulus').text(data.tahun_lulus);
        $('#modal-program_studi').text(data.program_studi);
        $('#modal-tmt').text(data.tmt);
        $('#modal-ketugasan').text(data.ketugasan);
        $('#modal-mengajar').text(data.mengajar);
        $('#modal-avatar').attr('src', data.avatar);
        $('#viewModal').modal('show');
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
