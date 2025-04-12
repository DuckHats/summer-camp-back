<?php

namespace Tests\Feature;

use App\Models\Monitor;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MonitorControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        UserSetting::factory()->create([
            'user_id' => $this->user->id,
            'key' => 'role',
            'value' => 'admin',
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_monitors()
    {
        Monitor::factory(10)->create();

        $response = $this->getJson(route('monitors.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_monitor()
    {
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg');
        $monitorData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'profile_picture' => $fakeImage,
            'extra_info' => 'Some extra information about the monitor.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('monitors.store'), $monitorData);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_monitor()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('monitors.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_monitor()
    {
        $monitor = Monitor::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('monitors.show', $monitor->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_monitor_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('monitors.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_monitor()
    {
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('updated_profile.jpg');
        $monitor = Monitor::factory()->create();

        $updatedData = [
            'first_name' => 'Updated First',
            'last_name' => 'Updated Last',
            'email' => 'updated.email@example.com',
            'phone' => '0987654321',
            'profile_picture' => $fakeImage,
            'extra_info' => 'Updated extra information about the monitor.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('monitors.update', $monitor->id), $updatedData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_partially_update_a_monitor()
    {
        $monitor = Monitor::factory()->create();

        $patchData = ['first_name' => 'Partially Updated'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('monitors.patch', $monitor->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('monitors', $patchData);
    }

    /** @test */
    public function it_can_delete_a_monitor()
    {
        $monitor = Monitor::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('monitors.destroy', $monitor->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('monitors', ['id' => $monitor->id]);
    }
}
