<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengurus BPPPMNU</title>
    <style>
        @page { margin: 32px 36px 42px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { margin: 0 0 5px; color: #183153; font-size: 18px; text-align: center; }
        .meta { margin-bottom: 18px; color: #6b7280; font-size: 9px; text-align: center; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #cbd5e1; padding: 7px 8px; vertical-align: top; word-wrap: break-word; }
        th { background: #183153; color: #fff; font-size: 9px; text-transform: uppercase; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .number { width: 7%; text-align: center; }
        .name { width: 32%; }
        .email { width: 35%; }
        .position { width: 26%; }
        .empty { padding: 20px; color: #6b7280; text-align: center; }
        footer { position: fixed; right: 0; bottom: -27px; left: 0; color: #94a3b8; font-size: 8px; text-align: center; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <footer>Data Pengurus BPPPMNU - Halaman <span class="page-number"></span></footer>
    <h1>DATA PENGURUS BPPPMNU</h1>
    <div class="meta">Dicetak {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB &bull; Total {{ $members->count() }} pengurus aktif</div>

    <table>
        <thead>
            <tr>
                <th class="number">No.</th>
                <th class="name">Nama</th>
                <th class="email">Email</th>
                <th class="position">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $index => $member)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->bpppmnuMember?->jabatan ?: $member->bpppmnu_jabatan ?: $member->jabatan ?: $member->ketugasan ?: '-' }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="4">Belum ada data pengurus aktif.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
