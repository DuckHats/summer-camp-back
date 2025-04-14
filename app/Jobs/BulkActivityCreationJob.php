<?php

namespace App\Jobs;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkActivityCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $activities;

    /**
     * Create a new job instance.
     */
    public function __construct(array $activities)
    {
        $this->activities = $activities;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            foreach ($this->activities as $activityData) {
                Activity::create([
                    'name' => $activityData['name'],
                    'description' => $activityData['description'] ?? null,
                    'cover_image' => $activityData['cover_image'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Bulk activity creation completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkActivityCreationJob', [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
