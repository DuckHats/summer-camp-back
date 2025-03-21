<?php

namespace App\Jobs;

use App\Models\Son;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BulkUserCreationJob implements ShouldQueue
{
    // Per executar el worker: php artisan queue:work --queue=bulk-processing,user-creation
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $users;

    /**
     * Create a new job instance.
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            foreach ($this->users as $userData) {
                $user = User::create([
                    'username' => $userData['username'],
                    'phone' => $userData['phone'] ?? null,
                    'email' => $userData['email'],
                    'status' => $userData['status'] ?? User::STATUS_ACTIVE,
                    'password' => Hash::make($userData['password']),
                ]);

                if (! empty($userData['sons'])) {
                    $sons = array_map(fn ($son) => array_merge($son, ['user_id' => $user->id]), $userData['sons']);
                    Son::insert($sons);
                }
            }

            DB::commit();
            Log::info('Bulk user creation completed successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkUserCreationJob', ['exception' => $e->getMessage()]);
        }
    }
}
