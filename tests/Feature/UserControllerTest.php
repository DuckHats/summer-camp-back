<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $adminUser;

    protected $normalUser;

    protected $token;

    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        UserRole::factory()->create([
            'user_id' => $this->adminUser->id,
            'role_name' => 'admin',
        ]);

        $this->normalUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
        $this->adminToken = $this->adminUser->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_users()
    {
        User::factory(5)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'username' => 'john_doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.store'), $userData);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        $response = $this->getJson(route('users.show', $this->user->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_user_not_found()
    {
        $response = $this->getJson(route('users.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_patch_a_user()
    {
        $updatedData = ['first_name' => 'Updated Name'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('users.patch', $this->user->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['first_name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('users.destroy', $this->user->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

    /** @test */
    public function it_can_permaban_a_user()
    {
        Gate::shouldReceive('authorize')->once()->andReturn(true);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.permaban', $this->normalUser->id));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User permabaned.',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->normalUser->id,
            'status' => User::STATUS_PERMABAN,
        ]);
    }

    /** @test */
    public function it_returns_validation_error_if_request_is_invalid()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.permaban', $this->normalUser->id), [
                'invalid_param' => 'invalid_value',
            ]);

        $response->assertStatus(500)
            ->assertJsonFragment([
                'exception' => 'This action is unauthorized.',
            ]);
    }

    /** @test */
    public function it_can_unban_a_user()
    {
        $user = User::factory()->create([
            'status' => User::STATUS_PERMABAN,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson(route('users.unban', $user->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function it_can_modify_avatar()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson(route('users.avatar', $user->id), [
                'avatar' => 'avatar.jpg',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_picture_url' => 'avatar.jpg',
        ]);
    }

    /** @test */
    public function it_can_temporal_ban_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson(route('users.tempban', $user->id), [
                'days' => 5,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users_temporal_banned', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_disable_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson(route('users.disable', $user->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_INACTIVE,
        ]);
    }

    /** @test */
    public function it_should_fail_if_i_want_to_disable_other_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.disable', $this->normalUser->id));

        $response->assertStatus(500)
            ->assertJsonFragment([
                'exception' => 'This action is unauthorized.',
            ]);
    }
}
