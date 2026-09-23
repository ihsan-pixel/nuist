<?php

namespace Tests\Unit;

use App\Models\SppSiswaVirtualAccount;
use App\Services\BniVaCsvService;
use Carbon\Carbon;
use Tests\TestCase;

class BniVaCsvServiceTest extends TestCase
{
    public function test_it_builds_the_exact_bni_csv_columns(): void
    {
        $account = new SppSiswaVirtualAccount([
            'trx_id' => 'INV-LPMNUDIY-104-TA2627-01837',
            'virtual_account' => '9887910500001837',
            'customer_name' => 'SISWA CONTOH',
            'customer_email' => 'siswa@example.com',
            'customer_phone' => '081234567890',
            'trx_amount' => 0,
            'description' => 'Juli 2026 - Juni 2027',
        ]);
        $account->expired_at = Carbon::parse('2027-06-30 20:00:00');

        $this->assertSame([
            'trx_id', 'virtual_account', 'customer_name', 'customer_email',
            'customer_phone', 'trx_amount', 'expired_date', 'expired_time', 'description',
        ], BniVaCsvService::HEADERS);

        $this->assertSame([
            'INV-LPMNUDIY-104-TA2627-01837', '9887910500001837', 'SISWA CONTOH',
            'siswa@example.com', '081234567890', '0', '2027-06-30', '20:00:00',
            'Juli 2026 - Juni 2027',
        ], (new BniVaCsvService())->csvRow($account));
    }
}
