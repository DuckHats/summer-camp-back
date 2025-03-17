<?php

namespace Tests\Feature;

use App\Models\ContentLike;
use App\Models\Issue;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContentLikeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $post;

    protected $issue;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->post = Post::factory()->create();
        $this->issue = Issue::factory()->create();
    }

    /** @test */
    public function it_can_list_likes()
    {
        ContentLike::factory()->create(['likeable_type' => Post::class, 'likeable_id' => $this->post->id]);
        ContentLike::factory()->create(['likeable_type' => Issue::class, 'likeable_id' => $this->issue->id]);

        $response = $this->getJson(route('likes.list'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_like_for_a_post()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('likes.post.store', ['id' => $this->post->id]), ['type' => 'post']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('content_likes', [
            'likeable_type' => Post::class,
            'likeable_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_store_a_like_for_an_issue()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('likes.issue.store', ['id' => $this->issue->id]), ['type' => 'issue']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('content_likes', [
            'likeable_type' => Issue::class,
            'likeable_id' => $this->issue->id,
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_should_fail_if_invalid_likeable_type_is_given_on_store()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('likes.post.store', ['id' => $this->post->id]), ['type' => 'invalid_type']);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_delete_a_like()
    {
        $like = ContentLike::factory()->create([
            'likeable_type' => Post::class,
            'likeable_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('likes.destroy', ['id' => $like->id]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('content_likes', ['id' => $like->id]);
    }

    /** @test */
    public function it_should_fail_if_like_to_delete_does_not_exist()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('likes.destroy', ['id' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_user_tries_to_delete_other_users_like()
    {
        $anotherUser = User::factory()->create();
        $like = ContentLike::factory()->create([
            'likeable_type' => Post::class,
            'likeable_id' => $this->post->id,
            'user_id' => $anotherUser->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('likes.destroy', ['id' => $like->id]));

        $response->assertStatus(403);
    }
}
