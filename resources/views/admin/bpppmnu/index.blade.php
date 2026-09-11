@extends('admin.bpppmnu.layout')
@section('bpp-content')
<a class="btn btn-primary mb-3" href="{{ route('admin.bpppmnu.events.create') }}">Buat Kegiatan</a>
<div class="card"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>Kegiatan</th><th>Waktu (WIB)</th><th>Status</th><th>Undangan</th><th>Hadir</th><th>Aksi</th></tr></thead><tbody>
@forelse($events as $event)<tr><td>{{ $event->name }}</td><td>{{ $event->start_at->format('d-m-Y H:i') }}</td><td>{{ $event->status }}</td><td>{{ $event->invitations_count }}</td><td>{{ $event->attendances_count }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.bpppmnu.events.show', $event) }}">Detail & Rekap</a></td></tr>@empty<tr><td colspan="6">Belum ada kegiatan.</td></tr>@endforelse
</tbody></table>{{ $events->links() }}</div></div>
@endsection
