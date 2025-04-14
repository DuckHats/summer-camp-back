<?php

namespace App\Jobs;

use App\Models\Child;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelper;
use App\Mail\PasswordChangedMail;
use App\Mail\WelcomeMail;
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

                if (! empty($userData['childs'])) {
                    $childs = array_map(fn ($child) => array_merge($child, ['user_id' => $user->id]), $userData['childs']);
                    Child::insert($childs);
                }
            }

            DB::commit();
            Log::info('Bulk user creation completed successfully.');
            Log::info('Let send the email to the user');
            foreach ($this->users as $userData) {
                Log::info('Send email to user: ' . $userData['email']);
                EmailHelper::sendEmail($user->email, WelcomeMail::class, [$user]);
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing BulkUserCreationJob', ['exception' => $e->getMessage()]);
        }
    }
}
