<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Madrasah;

echo "Total madrasah: " . Madrasah::count() . PHP_EOL;
echo "Madrasah dengan logo: " . Madrasah::whereNotNull('logo')->count() . PHP_EOL;
echo PHP_EOL;

$madrasahsWithLogo = Madrasah::whereNotNull('logo')->get(['id', 'name', 'logo']);
foreach ($madrasahsWithLogo as $madrasah) {
    echo $madrasah->id . ': ' . $madrasah->name . ' -> ' . $madrasah->logo . PHP_EOL;
}
