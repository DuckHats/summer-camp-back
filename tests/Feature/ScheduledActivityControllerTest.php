<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Group;
use App\Models\ScheduledActivity;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ScheduledActivityControllerTest extends TestCase
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
    public function it_can_list_scheduled_activity()
    {
        ScheduledActivity::factory(10)->create();

        $response = $this->getJson(route('scheduled_activities.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_scheduled_activity()
    {

        $scheduled_Activity_data = [
            'activity_id' => Activity::factory()->create()->id,
            'group_id' => Group::factory()->create()->id,
            'initial_date' => now()->format('Y-m-d'),
            'final_date' => now()->addDays(5)->format('Y-m-d'),
            'initial_hour' => now()->format('H:i:s'),
            'final_hour' => now()->addHours(5)->format('H:i:s'),
            'location' => 'Test Location',

        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('scheduled_activities.store'), $scheduled_Activity_data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('scheduled_activities', ['location' => 'Test Location']);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_an_scheduled_activities()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('scheduled_activities.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_an_scheduled_activities()
    {
        $scheduled_activity = ScheduledActivity::factory()->create();

        $response = $this->getJson(route('scheduled_activities.show', $scheduled_activity->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_scheduled_activities_not_found()
    {
        $response = $this->getJson(route('scheduled_activities.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_an_scheduled_activity()
    {
        $scheduled_activity = ScheduledActivity::factory()->create();

        $patchData = ['location' => 'Partially Updated Location'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('scheduled_activities.patch', $scheduled_activity->id), $patchData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('scheduled_activities', $patchData);
    }

    /** @test */
    public function it_can_delete_an_scheduled_activity()
    {
        $scheduled_activity = ScheduledActivity::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('scheduled_activities.destroy', $scheduled_activity->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('scheduled_activities', ['id' => $scheduled_activity->id]);
    }
}
