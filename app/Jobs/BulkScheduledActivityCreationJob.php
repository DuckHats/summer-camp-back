<?php

namespace App\Jobs;

use App\Models\ScheduledActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkScheduledActivityCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $scheduledActivities;

    /**
     * Create a new job instance.
     */
    public function __construct(array $scheduledActivities)
    {
        $this->scheduledActivities = $scheduledActivities;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            foreach ($this->scheduledActivities as $activityData) {
                ScheduledActivity::create([
                    'activity_id' => $activityData['activity_id'],
                    'group_id' => $activityData['group_id'],
                    'initial_date' => $activityData['initial_date'],
                    'final_date' => $activityData['final_date'],
                    'initial_hour' => $activityData['initial_hour'],
                    'final_hour' => $activityData['final_hour'],
                    'location' => $activityData['location'],
                ]);
            }

            DB::commit();
            Log::info('Bulk scheduled activity creation completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkScheduledActivityCreationJob', [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
