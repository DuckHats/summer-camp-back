<?php

namespace Tests\Feature;

use App\Models\FriendshipStatus;
use App\Models\User;
use App\Models\UserFriendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFriendshipControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $friendshipStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->friendshipStatus = FriendshipStatus::factory()->create();
    }

    /** @test */
    public function it_can_list_user_friendships()
    {
        UserFriendship::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('user_friendships.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_user_friendship()
    {
        $data = [
            'user_id_1' => $this->user->id,
            'user_id_2' => User::factory()->create()->id,
            'status_id' => $this->friendshipStatus->id,
        ];

        $response = $this->actingAs($this->user)->postJson(route('user_friendships.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_friendships', $data);
    }

    /** @test */
    public function it_shows_a_user_friendship()
    {
        $friendship = UserFriendship::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('user_friendships.show', $friendship->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_not_found_when_user_friendship_does_not_exist()
    {
        $response = $this->actingAs($this->user)->getJson(route('user_friendships.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_user_friendship()
    {
        $friendship = UserFriendship::factory()->create([
            'user_id_1' => $this->user->id,
        ]);

        $updateData = [
            'status_id' => $this->friendshipStatus->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_friendships.patch', $friendship->id), $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_friendships', array_merge(['id' => $friendship->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_user_friendship()
    {
        $friendship = UserFriendship::factory()->create([
            'user_id_1' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('user_friendships.destroy', $friendship->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_friendships', ['id' => $friendship->id]);
    }

    /** @test */
    public function it_can_patch_a_user_friendship()
    {
        $friendship = UserFriendship::factory()->create([
            'user_id_1' => $this->user->id,
        ]);

        $patchData = [
            'status_id' => $this->friendshipStatus->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_friendships.patch', $friendship->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_friendships', array_merge(['id' => $friendship->id], $patchData));
    }
}
