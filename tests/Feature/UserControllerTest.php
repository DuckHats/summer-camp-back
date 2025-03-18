<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $normalUser;

    protected $token;

    protected $adminUser;

    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->normalUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        UserSetting::factory()->create([
            'user_id' => $this->adminUser->id,
            'key' => 'role',
            'value' => 'admin',
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
            'dni' => '1234567890',
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
        $updatedData = ['username' => 'Updated Name'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('users.patch', $this->user->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['username' => 'Updated Name']);
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
     public function it_can_enable_user()
     {
         $user = User::factory()->create();
 
         $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
             ->postJson(route('users.enable', $user->id));
 
         $response->assertStatus(200);
 
         $this->assertDatabaseHas('users', [
             'id' => $user->id,
             'status' => User::STATUS_ACTIVE,
         ]);
     }

    /** @test */
    public function it_should_fail_if_i_want_to_disable_other_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('users.disable', $this->normalUser->id));

        $response->assertStatus(500);
    }
}
