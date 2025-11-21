<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        img { width: 120px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>

<h3>Rekap Presensi Bulan: {{ $bulan }}</h3>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Guru</th>
            <th>Masuk</th>
            <th>Foto Masuk</th>
            <th>Keluar</th>
            <th>Foto Keluar</th>
            <th>Keterangan</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($presensis as $p)
        <tr>
            <td>{{ $p->tanggal }}</td>
            <td>{{ $p->user->name }}</td>

            <td>{{ $p->waktu_masuk }}</td>
            <td>
                @php
                    $fotoMasukPath = public_path('storage/' . $p->selfie_masuk_path);
                    $fotoMasuk = (file_exists($fotoMasukPath))
                        ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fotoMasukPath))
                        : null;
                @endphp

                @if($fotoMasuk)
                    <img src="{{ $fotoMasuk }}" width="120px">
                @endif
            </td>
            <td>{{ $p->waktu_keluar }}</td>
            <td>
                @if ($p->selfie_keluar_path)
                    <img src="file://{{ public_path('storage/' . $p->selfie_keluar_path) }}">
                @endif
            </td>

            <td>{{ $p->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
