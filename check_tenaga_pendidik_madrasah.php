<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Madrasah;

// Get all tenaga pendidik
$tenagaPendidik = User::where('role', 'tenaga_pendidik')->with('madrasah')->get();

$issues = [];

foreach ($tenagaPendidik as $tp) {
    if (!$tp->madrasah_id) {
        $issues[] = "User {$tp->name} (ID: {$tp->id}) has no madrasah_id";
        continue;
    }

    $madrasah = Madrasah::find($tp->madrasah_id);
    if (!$madrasah) {
        $issues[] = "User {$tp->name} (ID: {$tp->id}) has invalid madrasah_id: {$tp->madrasah_id}";
        continue;
    }

    // Check if the madrasah name matches what we expect
    // Since the view uses madrasah_id to filter, the name should match
    // But let's verify the relationship is consistent
    if ($tp->madrasah->name !== $madrasah->name) {
        $issues[] = "User {$tp->name} (ID: {$tp->id}) madrasah relationship mismatch: relationship shows '{$tp->madrasah->name}', direct query shows '{$madrasah->name}'";
    }
}

if (empty($issues)) {
    echo "All tenaga pendidik have correct madrasah assignments.\n";
} else {
    echo "Found issues:\n";
    foreach ($issues as $issue) {
        echo "- $issue\n";
    }
}

// Also check if there are madrasah with no tenaga pendidik
$madrasahWithNoTenaga = Madrasah::whereDoesntHave('users', function($q) {
    $q->where('role', 'tenaga_pendidik');
})->get();

if ($madrasahWithNoTenaga->count() > 0) {
    echo "\nMadrasah with no tenaga pendidik:\n";
    foreach ($madrasahWithNoTenaga as $madrasah) {
        echo "- {$madrasah->name} (ID: {$madrasah->id})\n";
    }
} else {
    echo "\nAll madrasah have at least one tenaga pendidik.\n";
}
