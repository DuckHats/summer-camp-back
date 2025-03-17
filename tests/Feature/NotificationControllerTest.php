<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $notification;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->notification = Notification::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_notifications()
    {
        $response = $this->getJson(route('notification.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_notification()
    {
        $notificationData = [
            'user_id' => $this->user->id,
            'type' => 'info',
            'data' => 'Update password for user',
            'read_at' => null,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('notification.store'), $notificationData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('notifications', ['type' => 'info']);
    }

    /** @test */
    public function it_can_show_a_notification()
    {
        $response = $this->getJson(route('notification.show', $this->notification->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_notification()
    {
        $updatedData = [
            'user_id' => $this->user->id,
            'type' => 'warning',
            'data' => 'Update password for user',
            'read_at' => null,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('notification.update', $this->notification->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', ['type' => 'warning']);
    }

    /** @test */
    public function it_can_delete_a_notification()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('notification.destroy', $this->notification->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('notifications', ['id' => $this->notification->id]);
    }

    /** @test */
    public function it_can_patch_a_notification()
    {
        $patchData = [
            'user_id' => $this->user->id,
            'type' => 'success',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('notification.patch', $this->notification->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', ['type' => 'success']);
    }

    /** @test */
    public function it_should_fail_if_notification_not_found_on_show()
    {
        $response = $this->getJson(route('notification.show', 1999));

        $response->assertStatus(404);
    }
}
