@extends('layouts.vertical')

@section('title', 'Face Enrollment Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pendaftaran Wajah - Daftar Guru</h4>
                    <p class="card-title-desc">Kelola pendaftaran wajah untuk guru. Klik "Daftar / Re-enroll" untuk membuka halaman pendaftaran wajah.</p>

                    <!-- Filter by Madrasah -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="madrasah-filter" class="form-select">
                                <option value="">Semua Madrasah</option>
                                @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ request('madrasah_id') == $madrasah->id ? 'selected' : '' }}>
                                    {{ $madrasah->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="face-status-filter" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="registered" {{ request('face_status') == 'registered' ? 'selected' : '' }}>Sudah Terdaftar</option>
                                <option value="not_registered" {{ request('face_status') == 'not_registered' ? 'selected' : '' }}>Belum Terdaftar</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button id="apply-filters" class="btn btn-primary">Terapkan Filter</button>
                        </div>
                    </div>

                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIP / NUPTK</th>
                                <th>Madrasah</th>
                                <th>Status Wajah</th>
                                <th>Tanggal Daftar</th>
                                <th>Verifikasi Diperlukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $u->name }}</strong>
                                    @if($u->face_verification_required)
                                    <br><small class="text-muted">Verifikasi wajah aktif</small>
                                    @endif
                                </td>
                                <td>{{ $u->nip ?? $u->nuptk ?? '-' }}</td>
                                <td>{{ $u->madrasah->nama ?? '-' }}</td>
                                <td>
                                    @if($u->face_registered_at)
                                        <span class="badge bg-success">Terdaftar</span>
                                    @else
                                        <span class="badge bg-warning">Belum Terdaftar</span>
                                    @endif
                                </td>
                                <td>{{ $u->face_registered_at ? $u->face_registered_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($u->face_verification_required)
                                        <span class="badge bg-info">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('face.enrollment', ['user_id' => $u->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-camera me-1"></i>Daftar / Re-enroll
                                        </a>
                                        @if($u->face_registered_at)
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteFace({{ $u->id }}, '{{ $u->name }}')">
                                            <i class="bx bx-trash me-1"></i>Hapus
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFaceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Data Wajah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data wajah untuk guru: <strong id="delete-user-name"></strong>?</p>
                <p class="text-danger">Data wajah yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function confirmDeleteFace(userId, userName) {
    document.getElementById('delete-user-name').textContent = userName;
    document.getElementById('confirm-delete-btn').onclick = function() {
        deleteFaceData(userId);
    };
    new bootstrap.Modal(document.getElementById('deleteFaceModal')).show();
}

function deleteFaceData(userId) {
    fetch(`/admin/face-enrollment/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menghapus data wajah: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data wajah');
    });
}

// Filter functionality
document.getElementById('apply-filters').addEventListener('click', function() {
    const madrasahId = document.getElementById('madrasah-filter').value;
    const faceStatus = document.getElementById('face-status-filter').value;

    let url = '{{ route("face.enrollment.list") }}';
    const params = new URLSearchParams();

    if (madrasahId) params.append('madrasah_id', madrasahId);
    if (faceStatus) params.append('face_status', faceStatus);

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
});
</script>
@endsection
