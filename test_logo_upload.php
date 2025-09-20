<?php
// Test script untuk memastikan logo upload berfungsi
echo "=== TEST LOGO UPLOAD SYSTEM ===\n\n";

// Test 1: Cek folder storage
echo "1. Cek folder storage:\n";
$storagePath = public_path('storage/madrasah');
if (is_dir($storagePath)) {
    echo "✓ Folder madrasah ada di: $storagePath\n";
} else {
    echo "✗ Folder madrasah tidak ada\n";
}

// Test 2: Cek permissions
echo "\n2. Cek permissions:\n";
if (is_writable($storagePath)) {
    echo "✓ Folder madrasah dapat ditulis\n";
} else {
    echo "✗ Folder madrasah tidak dapat ditulis\n";
}

// Test 3: Cek file yang ada
echo "\n3. File yang ada di folder madrasah:\n";
$files = scandir($storagePath);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $filePath = $storagePath . '/' . $file;
        echo "  - $file (" . filesize($filePath) . " bytes)\n";
    }
}

echo "\n=== TEST SELESAI ===\n";
