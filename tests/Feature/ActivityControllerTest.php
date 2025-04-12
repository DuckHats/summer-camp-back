<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
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
    public function it_can_list_activities()
    {
        Activity::factory(10)->create();

        $response = $this->getJson(route('activities.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_activity()
    {
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('cover.jpg');

        $activityData = [
            'name' => 'Test Activity',
            'description' => 'This is a test activity.',
            'cover_image' => $fakeImage,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post(route('activities.store'), $activityData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('activities', ['name' => 'Test Activity']);
    }


    /** @test */
    public function it_validates_required_fields_when_creating_an_activity()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('activities.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_an_activity()
    {
        $activity = Activity::factory()->create();

        $response = $this->getJson(route('activities.show', $activity->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_activity_not_found()
    {
        $response = $this->getJson(route('activities.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_activity()
    {
        $activity = Activity::factory()->create();

        $updatedData = ['name' => 'Updated Activity', 'description' => 'Updated description'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('activities.update', $activity->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activities', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_an_activity()
    {
        $activity = Activity::factory()->create();

        $patchData = ['name' => 'Partially Updated Activity'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patchJson(route('activities.patch', $activity->id), $patchData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activities', $patchData);
    }

    /** @test */
    public function it_can_delete_an_activity()
    {
        $activity = Activity::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('activities.destroy', $activity->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }
}
