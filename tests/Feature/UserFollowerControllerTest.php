<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserFollowerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $follower;

    protected $followed;

    protected $userFollower;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->follower = User::factory()->create();
        $this->followed = User::factory()->create();

        $this->userFollower = UserFollower::factory()->create([
            'follower_id' => $this->user->id,
            'followed_id' => $this->followed->id,
        ]);
    }

    /** @test */
    public function it_can_list_user_followers()
    {
        $response = $this->getJson(route('user_followers.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success']);
    }

    /** @test */
    public function it_can_create_a_user_follower()
    {
        $newFollowed = User::factory()->create();

        $requestData = [
            'follower_id' => $this->user->id,
            'followed_id' => $newFollowed->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('user_followers.store'), $requestData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_followers', [
            'follower_id' => $this->user->id,
            'followed_id' => $newFollowed->id,
        ]);
    }

    /** @test */
    public function it_can_show_a_user_follower()
    {
        $response = $this->getJson(route('user_followers.show', $this->userFollower->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->userFollower->id]);
    }

    /** @test */
    public function it_can_update_a_user_follower()
    {
        $updatedData = [
            'follower_id' => $this->user->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_followers.update', $this->userFollower->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_followers', ['follower_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_delete_a_user_follower()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_followers.destroy', $this->userFollower->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_followers', ['id' => $this->userFollower->id]);
    }

    /** @test */
    public function it_should_fail_if_user_follower_not_found_on_show()
    {
        $response = $this->getJson(route('user_followers.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_user_follower_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_followers.destroy', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_a_user_follower()
    {
        $partialUpdateData = [
            'followed_id' => $this->user->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_followers.patch', $this->userFollower->id), $partialUpdateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_followers', ['followed_id' => $this->user->id]);
    }
}
