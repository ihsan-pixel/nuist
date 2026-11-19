<!doctype html>
<html><body style="font-family:Arial,sans-serif;color:#24343b;line-height:1.6">
    <h2 style="color:#08745a">NUIST - Pengajuan Tenaga Pendidik</h2>
    <p>Yth. Admin Sekolah,</p>
    @php($payload = $submission->payload ?? [])
    @if($event === 'submitted')
        <p>Pengajuan tenaga pendidik <strong>{{ $payload['nama'] ?? '-' }}</strong> telah diterima dan menunggu review Super Admin.</p>
    @elseif($event === 'needs_revision')
        <p>Pengajuan tenaga pendidik <strong>{{ $payload['nama'] ?? '-' }}</strong> memerlukan perbaikan.</p>
        <p><strong>Catatan review:</strong><br>{{ $submission->review_note ?: '-' }}</p>
    @else
        <p>Pengajuan tenaga pendidik <strong>{{ $payload['nama'] ?? '-' }}</strong> telah disetujui. Akun tenaga pendidik sudah dibuat dan dapat digunakan untuk login.</p>
    @endif
    <p>Madrasah: <strong>{{ optional($submission->madrasah)->name ?: '-' }}</strong></p>
    <p>Silakan masuk ke aplikasi NUIST untuk melihat detail pengajuan.</p>
    <p>Salam,<br><strong>NUIST</strong></p>
</body></html>
