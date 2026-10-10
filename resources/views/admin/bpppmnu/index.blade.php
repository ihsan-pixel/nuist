@extends('admin.bpppmnu.layout')
@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('bpp-content')
<a class="btn btn-primary mb-3" href="{{ route('admin.agenda.create') }}">Buat Kegiatan</a>
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0"><i class="bx bx-calendar-event me-2"></i>Agenda Universal</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>Kegiatan</th><th>Waktu (WIB)</th><th>Status</th><th>Undangan</th><th>Hadir</th><th>Action</th></tr>
                </thead>
                <tbody>
@forelse($events as $index => $event)<tr><td>{{ $events->firstItem() + $index }}</td><td>{{ $event->name }}</td><td>{{ $event->start_at->format('d-m-Y H:i') }}</td><td>{{ $event->status }}</td><td>{{ $event->invitations_count + $event->guest_invitations_count }}</td><td>{{ $event->attendances_count + $event->guest_attendances_count }}</td><td><div class="d-flex gap-1 flex-wrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.agenda.show', $event) }}">Detail & Rekap</a>@if($event->status !== 'cancelled')<a class="btn btn-sm btn-warning" href="{{ route('admin.agenda.edit', $event) }}">Edit</a>@endif
<form method="post" action="{{ route('admin.agenda.destroy', $event) }}" onsubmit="return confirm('Hapus agenda {{ addslashes($event->name) }} secara permanen? Seluruh undangan, QR, dan data presensi agenda ini juga akan dihapus dan tidak dapat dikembalikan.')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button></form></div></td></tr>@empty<tr><td colspan="7">Belum ada kegiatan.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
        {{ $events->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script>
$(function () {
    $('#datatable-buttons').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        paging: false,
        searching: false,
        info: false,
        ordering: false
    });
});
</script>
@endsection
