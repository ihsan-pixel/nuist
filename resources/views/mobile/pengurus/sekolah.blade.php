@extends('layouts.mobile-pengurus')

@section('title', 'Data Sekolah')
@section('subtitle', 'Daftar Madrasah')

@section('content')
<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .stats-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .sekolah-card {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
            border-left: 4px solid #004b4c;
        }

        .sekolah-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .sekolah-logo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .search-box {
            background: #fff;
            border-radius: 10px;
            padding: 10px 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .search-box input {
            border: none;
            outline: none;
            font-size: 13px;
            width: 100%;
            padding: 4px 8px;
        }

        .search-box input::placeholder {
            color: #adb5bd;
        }

        .search-box button {
            background: none;
            border: none;
            color: #004b4c;
            cursor: pointer;
        }

        .badge-status {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-aktif {
            background: #d4edda;
            color: #155724;
        }

        .badge-nonaktif {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #dee2e6;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 16px;
            gap: 8px;
        }

        .pagination-wrapper .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #fff;
            color: #004b4c;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            border: none;
        }

        .pagination-wrapper .page-btn:hover {
            background: #004b4c;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
        }

        .pagination-wrapper .page-btn.disabled {
            opacity: 0.5;
            pointer-events: none;
            background: #f8f9fa;
            color: #adb5bd;
        }

        .pagination-wrapper .page-btn.active {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
        }

        .pagination-wrapper .page-info {
            font-size: 12px;
            color: #6c757d;
            margin: 0 8px;
        }
    </style>

    <!-- Page Title -->
    <div class="text-center mb-3">
        <h5 class="mb-0 fw-semibold text-dark" style="font-size: 16px;">
            <i class="bx bx-building me-2" style="color: #004b4c;"></i>
            Data Sekolah
        </h5>
        <small class="text-muted" style="font-size: 11px;">{{ $totalSekolah }} Madrasah Terdaftar</small>
    </div>

    <!-- Search Box -->
    <form action="{{ route('mobile.pengurus.sekolah') }}" method="GET" class="search-box d-flex align-items-center">
        <button type="submit">
            <i class="bx bx-search" style="font-size: 18px;"></i>
        </button>
        <input
            type="text"
            name="search"
            placeholder="Cari nama sekolah, kabupaten, atau alamat..."
            value="{{ $search ?? '' }}"
            autocomplete="off"
        >
        @if($search)
        <a href="{{ route('mobile.pengurus.sekolah') }}" class="text-muted ms-2">
            <i class="bx bx-x" style="font-size: 18px;"></i>
        </a>
        @endif
    </form>

    <!-- Schools List -->
    @if($madrasahs->count() > 0)
    <div class="sekolah-list">
        @foreach($madrasahs as $madrasah)
        <a href="{{ route('mobile.pengurus.sekolah.show', $madrasah->id) }}" class="text-decoration-none">
            <div class="sekolah-card d-flex align-items-start">
                <div class="me-3">
                    @if($madrasah->logo)
                    <img
                        src="{{ asset('storage/' . $madrasah->logo) }}"
                        alt="{{ $madrasah->name }}"
                        class="sekolah-logo"
                        onerror="this.src='{{ asset('build/images/logo-light.png') }}'"
                    >
                    @else
                    <img
                        src="{{ asset('build/images/logo-light.png') }}"
                        alt="{{ $madrasah->name }}"
                        class="sekolah-logo"
                    >
                    @endif
                </div>
                <div class="flex-grow-1 min-width-0">
                    <div class="d-flex align-items-center mb-1">
                        @if($madrasah->scod)
                        <span class="badge bg-primary me-2" style="font-size: 9px;">{{ $madrasah->scod }}</span>
                        @endif
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $madrasah->name }}
                        </h6>
                    </div>
                    <p class="mb-1 text-muted" style="font-size: 11px; line-height: 1.3;">
                        <i class="bx bx-map me-1" style="color: #0e8549;"></i>
                        {{ $madrasah->kabupaten ?: 'Kabupaten belum diisi' }}
                    </p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($madrasahs->hasPages())
    <div class="pagination-wrapper">
        {{-- Previous Button --}}
        @if($madrasahs->onFirstPage())
        <span class="page-btn disabled">
            <i class="bx bx-chevron-left"></i>
        </span>
        @else
        <a href="{{ $madrasahs->previousPageUrl() . ($search ? '&search=' . $search : '') }}" class="page-btn">
            <i class="bx bx-chevron-left"></i>
        </a>
        @endif

        {{-- Page Info --}}
        <span class="page-info">
            {{ $madrasahs->currentPage() }}/{{ $madrasahs->lastPage() }}
        </span>

        {{-- Next Button --}}
        @if($madrasahs->hasMorePages())
        <a href="{{ $madrasahs->nextPageUrl() . ($search ? '&search=' . $search : '') }}" class="page-btn">
            <i class="bx bx-chevron-right"></i>
        </a>
        @else
        <span class="page-btn disabled">
            <i class="bx bx-chevron-right"></i>
        </span>
        @endif
    </div>
    @endif
    @else
    <div class="empty-state">
        <i class="bx bx-building"></i>
        <h6 class="mb-2" style="font-size: 14px;">Tidak ada data sekolah</h6>
        <p class="mb-0" style="font-size: 12px;">
            @if($search)
            Tidak ditemukan sekolah dengan kata kunci "{{ $search }}"
            @else
            Belum ada data madrasah yang terdaftar
            @endif
        </p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification badge
    fetchUnreadNotifications();

    async function fetchUnreadNotifications() {
        try {
            const response = await fetch('{{ route("mobile.notifications.unread-count") }}');
            const data = await response.json();
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'block';
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
});
</script>
@endsection

