<?php
// Test script untuk memeriksa ppdb_status di database

require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test query
$madrasahs = \App\Models\Madrasah::select('id', 'name', 'ppdb_status')->limit(5)->get();

echo "=== TEST PPDB_STATUS ===\n";
echo "Total Madrasah: " . count($madrasahs) . "\n\n";

foreach ($madrasahs as $m) {
    echo "ID: {$m->id}\n";
    echo "Name: {$m->name}\n";
    echo "PPDB Status: {$m->ppdb_status}\n";
    echo "Status is 'buka': " . ($m->ppdb_status === 'buka' ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
?>
