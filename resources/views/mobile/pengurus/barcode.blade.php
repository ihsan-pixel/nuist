@extends('layouts.mobile-pengurus')

@section('title', 'Barcode Users')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        background-color: #f5f6fa;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 16px;
    }

    .user-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 2px;
    }

    .user-role {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .barcode-container {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 12px;
    }

    .barcode-svg {
        max-width: 100%;
        height: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px;
        background: white;
    }

    .search-container {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .stats-card {
        background: linear-gradient(135deg, #004b4c, #0e8549);
        color: white;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        margin-bottom: 16px;
    }

    .stats-value {
        font-size: 24px;
        font-weight: 700;
        display: block;
        margin-bottom: 4px;
    }

    .stats-label {
        font-size: 11px;
        opacity: 0.9;
    }

    .print-btn {
        position: fixed;
        bottom: 80px;
        right: 16px;
        background: linear-gradient(135deg, #004b4c, #0e8549);
        color: white;
        border: none;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .print-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    }

    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @media print {
        .mobile-nav,
        .search-container,
        .print-btn {
            display: none !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }

        .barcode-container {
            background: white !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>

<div class="container py-3" style="max-width: 540px; margin: auto;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h5 class="mb-0" style="font-size: 16px; font-weight: 600; color: #004b4c;">
                <i class="bx bx-barcode me-2"></i>Barcode Users
            </h5>
        </div>
        <span class="badge bg-secondary" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;">
            {{ $totalUsers }} Users
        </span>
    </div>

    <!-- Search -->
    <div class="search-container">
        <input type="text" id="searchInput" class="form-control"
               placeholder="Cari nama atau NUIST ID..." style="border-radius: 8px; border: 1px solid #e9ecef;">
    </div>

    <!-- Users List -->
    <div id="usersContainer">
        @forelse($users as $user)
            <div class="card user-card" data-name="{{ strtolower($user->name) }}" data-nuist="{{ $user->nuist_id }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $user->avatar ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                             alt="Avatar" class="user-avatar me-3">
                        <div class="grow">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-role">{{ ucfirst($user->role) }}</div>
                            <small class="text-muted">NUIST ID: {{ $user->nuist_id }}</small>
                        </div>
                    </div>

                    <div class="barcode-container">
                        <svg id="barcode-{{ $user->id }}" class="barcode-svg"></svg>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bx bx-user-x mb-2" style="font-size: 48px; color: #dee2e6;"></i>
                    <p class="text-muted mb-0">Tidak ada data user</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Print Button -->
<button class="print-btn" onclick="window.print()" title="Print Barcodes">
    <i class="bx bx-printer" style="font-size: 20px;"></i>
</button>

<div style="height: 100px;"></div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate barcodes for all users
    @foreach($users as $user)
        JsBarcode("#barcode-{{ $user->id }}", "{{ $user->nuist_id }}", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
            margin: 10
        });
    @endforeach

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const userCards = document.querySelectorAll('.user-card');
    const usersContainer = document.getElementById('usersContainer');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        userCards.forEach(card => {
            const name = card.dataset.name;
            const nuist = card.dataset.nuist;

            if (name.includes(searchTerm) || nuist.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Add loading state for print
    const printBtn = document.querySelector('.print-btn');
    const originalPrintHtml = printBtn.innerHTML;

    printBtn.addEventListener('click', function() {
        this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
        this.disabled = true;

        setTimeout(() => {
            this.innerHTML = originalPrintHtml;
            this.disabled = false;
        }, 2000);
    });
});
</script>
@endsection
