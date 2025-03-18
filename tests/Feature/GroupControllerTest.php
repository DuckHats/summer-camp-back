<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GroupControllerTest extends TestCase
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

        UserSetting::factory()->create(
            [
                'user_id' => $this->user->id,
                'key' => 'role',
                'value' => 'admin',
            ]
        );

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_groups()
    {
        Group::factory(10)->create();

        $response = $this->getJson(route('groups.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_group()
    {
        $groupData = [
            'name' => 'Test Group',
            'profile_picture' => 'image.png',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('groups.store'), $groupData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('groups', $groupData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_group()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('groups.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_group()
    {
        $group = Group::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('groups.show', $group->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_group_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('groups.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_group()
    {
        $group = Group::factory()->create();

        $updatedData = ['name' => 'Updated Title', 'profile_picture' => 'Updated content'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('groups.update', $group->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('groups', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_a_group()
    {
        $group = Group::factory()->create();

        $patchData = ['name' => 'Partially Updated name'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('groups.patch', $group->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('groups', $patchData);
    }

    /** @test */
    public function it_can_delete_a_group()
    {
        $group = Group::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('groups.destroy', $group->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
