<?php

namespace App\Jobs;

use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkMonitorCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $monitors;

    /**
     * Create a new job instance.
     */
    public function __construct(array $monitors)
    {
        $this->monitors = $monitors;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            foreach ($this->monitors as $monitorData) {
                Monitor::create([
                    'first_name' => $monitorData['first_name'],
                    'last_name' => $monitorData['last_name'],
                    'email' => $monitorData['email'],
                    'phone' => $monitorData['phone'] ?? null,
                    'extra_info' => $monitorData['extra_info'] ?? null,
                    'profile_picture' => $monitorData['profile_picture'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Bulk monitor creation completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkMonitorCreationJob', [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
