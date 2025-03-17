<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRolesControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_user_roles()
    {
        UserRole::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('user_roles.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_user_role()
    {
        $data = [
            'user_id' => $this->user->id,
            'role_name' => 'Admin',
        ];

        $response = $this->actingAs($this->user)->postJson(route('user_roles.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_roles', $data);
    }

    /** @test */
    public function it_shows_a_user_role()
    {
        $userRole = UserRole::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('user_roles.show', $userRole->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['role_name' => $userRole->role_name]);
    }

    /** @test */
    public function it_returns_not_found_when_user_role_does_not_exist()
    {
        $response = $this->actingAs($this->user)->getJson(route('user_roles.show', 999));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'User role not found.']);
    }

    /** @test */
    public function it_can_update_a_user_role()
    {
        $userRole = UserRole::factory()->create([
            'user_id' => $this->user->id,
            'role_name' => 'Editor',
        ]);

        $updateData = [
            'role_name' => 'Admin',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_roles.patch', $userRole->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.role_name', 'Admin');

        $this->assertDatabaseHas('user_roles', array_merge(['id' => $userRole->id], $updateData));
    }

    /** @test */
    public function it_returns_not_found_when_updating_non_existent_user_role()
    {
        $updateData = [
            'role_name' => 'SuperAdmin',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_roles.update', 999), $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_user_role()
    {
        $userRole = UserRole::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('user_roles.destroy', $userRole->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_roles', ['id' => $userRole->id]);
    }

    /** @test */
    public function it_returns_not_found_when_deleting_non_existent_user_role()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('user_roles.destroy', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_a_user_role()
    {
        $userRole = UserRole::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $patchData = [
            'role_name' => 'Viewer',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_roles.patch', $userRole->id), $patchData);

        $response->assertStatus(200)
            ->assertJsonPath('data.role_name', 'Viewer');

        $this->assertDatabaseHas('user_roles', array_merge(['id' => $userRole->id], $patchData));
    }
}
