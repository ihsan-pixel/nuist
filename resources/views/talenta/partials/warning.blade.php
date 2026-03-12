<div class="card" style="border:2px solid #dc3545">
    @if($submissionClosed ?? false)
        <h3 style="color:#dc3545">
            Batas waktu pengiriman tugas telah berakhir
        </h3>

        <p>
            Pengiriman file untuk semua materi sudah ditutup dan tidak dapat disimpan lagi ke database.
        </p>
    @else
        <h3 style="color:#dc3545">
            Materi {{ $nama }} belum terlaksana
        </h3>

        <p>
            Akan dilaksanakan:
            <strong>{{ $tanggal->format('d F Y') }}</strong>
        </p>
    @endif

</div>
