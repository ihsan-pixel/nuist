@extends('layouts.mobile')

@section('title', 'Kelola Izin')
@section('subtitle', 'Pengelolaan Pengajuan Izin')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        .izin-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
        }

        .izin-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .izin-user {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .izin-date {
            font-size: 12px;
            color: #666;
        }

        .izin-type {
            font-size: 12px;
            color: #0e8549;
            font-weight: 500;
            background: #f0f8f0;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .izin-description {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .izin-actions {
            display: flex;
            gap: 8px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 16px;
        }

        .filter-tab {
            flex: 1;
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #666;
            text-decoration: none;
            transition: all 0.2s;
        }

        .filter-tab.active {
            background: white;
            color: #0e8549;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-btn {
            background: none;
            border: none;
            color: #004b4c;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #f0f8f0;
        }
    </style>

    <!-- Back Button -->
    <button onclick="history.back()" class="back-btn">
        <i class="bx bx-arrow-back"></i>
        <span>Kembali</span>
    </button>

    <h6 class="mb-3 fw-bold">Kelola Pengajuan Izin</h6>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('mobile.kelola-izin', ['status' => 'pending']) }}"
           class="filter-tab {{ request('status', 'pending') === 'pending' ? 'active' : '' }}">
            Pending
        </a>
        <a href="{{ route('mobile.kelola-izin', ['status' => 'all']) }}"
           class="filter-tab {{ request('status') === 'all' ? 'active' : '' }}">
            Semua
        </a>
    </div>

    @if($izinRequests->isEmpty())
        <div class="empty-state">
            <i class="bx bx-file-blank"></i>
            <p>Tidak ada pengajuan izin {{ request('status') === 'all' ? '' : 'pending' }}</p>
        </div>
    @else
        @foreach($izinRequests as $presensi)
        <div class="izin-card">
            <div class="izin-header">
                <div>
                    <div class="izin-user">{{ $presensi->user->name }}</div>
                    <div class="izin-date">{{ $presensi->tanggal->format('d M Y') }}</div>
                </div>
                <div>
                    <span class="status-badge status-{{ $presensi->status_izin }}">
                        {{ ucfirst($presensi->status_izin) }}
                    </span>
                </div>
            </div>

            <div class="izin-description">
                {{ $presensi->keterangan }}
            </div>

            @if($presensi->surat_izin_path)
            <div class="mb-3">
                <a href="{{ asset('storage/' . $presensi->surat_izin_path) }}"
                   target="_blank"
                   class="text-decoration-none"
                   style="color: #0e8549; font-size: 12px;">
                    <i class="bx bx-file"></i> Lihat Surat Izin
                </a>
            </div>
            @endif

            @if($presensi->status_izin === 'pending')
            <div class="izin-actions">
                <form action="{{ route('izin.approve', $presensi) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-approve">
                        <i class="bx bx-check"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('izin.reject', $presensi) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-reject">
                        <i class="bx bx-x"></i> Tolak
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach

        <!-- Pagination -->
        @if($izinRequests->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $izinRequests->appends(request()->query())->links() }}
        </div>
        @endif
    @endif
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve/reject actions with confirmation
    document.querySelectorAll('form[action*="approve"], form[action*="reject"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isApprove = this.action.includes('approve');
            const confirmMessage = isApprove ?
                'Apakah Anda yakin ingin menyetujui pengajuan izin ini?' :
                'Apakah Anda yakin ingin menolak pengajuan izin ini?';

            if (confirm(confirmMessage)) {
                // Show loading state
                const button = this.querySelector('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
                button.disabled = true;

                // Submit form
                this.submit();
            }
        });
    });
});
</script>
@endsection
