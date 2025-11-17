<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug/ppdb-status', function() {
    $madrasahs = \App\Models\Madrasah::select('id', 'name', 'ppdb_status')
        ->with(['ppdbSettings' => function($q) {
            $q->where('tahun', now()->year)->select('id', 'sekolah_id', 'slug', 'tahun', 'status');
        }])
        ->limit(10)
        ->get();

    return response()->json([
        'total' => count($madrasahs),
        'data' => $madrasahs->map(function($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'ppdb_status_db' => $m->ppdb_status,
                'ppdb_setting_exists' => $m->ppdbSettings->count() > 0,
                'ppdb_setting_slug' => $m->ppdbSettings->first()?->slug,
                'is_buka' => $m->ppdb_status === 'buka',
            ];
        })->toArray(),
    ], 200);
});
