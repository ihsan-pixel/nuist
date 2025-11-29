<?php

namespace App\Jobs;

use App\Models\BroadcastNumber;
use App\Services\OpenWAService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBroadcastMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schoolId;
    protected $message;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($schoolId, $message, $userId)
    {
        $this->schoolId = $schoolId;
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Get broadcast numbers for the school
            $broadcastNumbers = BroadcastNumber::where('madrasah_id', $this->schoolId)
                ->pluck('whatsapp_number')
                ->toArray();

            if (empty($broadcastNumbers)) {
                Log::warning('No broadcast numbers found for school', [
                    'school_id' => $this->schoolId,
                    'user_id' => $this->userId
                ]);
                return;
            }

            // Send broadcast using OpenWA service
            $openWAService = new OpenWAService();
            $result = $openWAService->sendBroadcast($broadcastNumbers, $this->message);

            Log::info('Broadcast job completed', [
                'school_id' => $this->schoolId,
                'user_id' => $this->userId,
                'total_numbers' => $result['total'],
                'success_count' => $result['success'],
                'fail_count' => $result['failed']
            ]);

        } catch (\Exception $e) {
            Log::error('Broadcast job failed', [
                'school_id' => $this->schoolId,
                'user_id' => $this->userId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
