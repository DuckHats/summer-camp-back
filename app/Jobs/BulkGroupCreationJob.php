<?php

namespace App\Jobs;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkGroupCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $groups;

    /**
     * Create a new job instance.
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            foreach ($this->groups as $groupData) {
                Group::create([
                    'name' => $groupData['name'],
                    'monitor_id' => $groupData['monitor_id'] ?? null,
                    'profile_picture' => $groupData['profile_picture'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Bulk group creation completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkGroupCreationJob', [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
