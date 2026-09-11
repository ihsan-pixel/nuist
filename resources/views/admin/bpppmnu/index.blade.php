@extends('admin.bpppmnu.layout')
@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('bpp-content')
<a class="btn btn-primary mb-3" href="{{ route('admin.bpppmnu.events.create') }}">Buat Kegiatan</a>
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0"><i class="bx bx-calendar-event me-2"></i>Agenda BPPPMNU</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>Kegiatan</th><th>Waktu (WIB)</th><th>Status</th><th>Undangan</th><th>Hadir</th><th>Action</th></tr>
                </thead>
                <tbody>
@forelse($events as $index => $event)<tr><td>{{ $events->firstItem() + $index }}</td><td>{{ $event->name }}</td><td>{{ $event->start_at->format('d-m-Y H:i') }}</td><td>{{ $event->status }}</td><td>{{ $event->invitations_count }}</td><td>{{ $event->attendances_count }}</td><td><div class="d-flex gap-1 flex-wrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.bpppmnu.events.show', $event) }}">Detail & Rekap</a>@if(!$event->isLocked() && $event->status !== 'cancelled')<a class="btn btn-sm btn-warning" href="{{ route('admin.bpppmnu.events.edit', $event) }}">Edit</a>@endif
@if(!$event->attendances_count)<form method="post" action="{{ route('admin.bpppmnu.events.destroy', $event) }}" onsubmit="return confirm('Hapus agenda ini beserta undangannya?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>@endif</div></td></tr>@empty<tr><td colspan="6">Belum ada kegiatan.</td></tr>@endforelse
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
