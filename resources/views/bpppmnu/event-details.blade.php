<dl>
@foreach(['type'=>'Jenis kegiatan','organizer'=>'Penyelenggara','location_name'=>'Lokasi'] as $field => $label)
@if($event->$field)<dt>{{ $label }}</dt><dd class="bpp-detail">{{ $event->$field }}</dd>@endif
@endforeach
<dt>Waktu kegiatan (WIB)</dt><dd>{{ $event->start_at->format('d-m-Y H:i') }} – {{ $event->end_at->format('d-m-Y H:i') }}</dd>
<dt>Periode presensi (WIB)</dt><dd>{{ $event->attendance_open_at->format('d-m-Y H:i') }} – {{ $event->attendance_close_at->format('d-m-Y H:i') }}</dd>
@if($event->meeting_url)<dt>Link alamat/lokasi kegiatan</dt><dd><a href="{{ $event->meeting_url }}" target="_blank" rel="noopener noreferrer">Buka lokasi kegiatan</a></dd>@endif
</dl>
