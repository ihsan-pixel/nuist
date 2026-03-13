<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaKehadiranPeserta extends Model
{
    use HasFactory;

    protected $table = 'talenta_kehadiran_peserta';

    protected $fillable = [
        'tanggal',
        'talenta_peserta_id',
        'materi_id',
        'status_kehadiran',
        'sesi',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sesi' => 'array',
    ];

    public function peserta()
    {
        return $this->belongsTo(TalentaPeserta::class, 'talenta_peserta_id');
    }

    public function materi()
    {
        return $this->belongsTo(TalentaMateri::class, 'materi_id');
    }

    public function getNamaHariAttribute()
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][$this->tanggal?->format('l') ?? ''] ?? '-';
    }

    public function getKeteranganLabelAttribute()
    {
        $statusMap = [
            'hadir' => 'Hadir',
            'telat' => 'Telat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'tidak_hadir' => 'Tidak Hadir',
            'lainnya' => 'Keterangan',
        ];

        $sesi = collect($this->sesi ?? [])
            ->map(fn ($item) => 'Sesi ' . $item)
            ->implode(', ');

        $label = $statusMap[$this->status_kehadiran] ?? 'Keterangan';

        if ($this->status_kehadiran === 'lainnya') {
            return $this->catatan ?: $label;
        }

        $parts = array_filter([
            $label,
            $sesi,
            $this->catatan,
        ]);

        return implode(' - ', $parts);
    }
}
