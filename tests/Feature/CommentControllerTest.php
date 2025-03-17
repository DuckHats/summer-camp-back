<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_comments_for_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        Comment::factory(10)->create(['post_id' => $post->id]);

        $response = $this->getJson(route('comments.index', ['post' => $post->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $commentData = [
            'user_id' => $this->user->id,
            'content' => 'This is a comment.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('comments.store', ['post' => $post->id]), $commentData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'content' => 'This is a comment.',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('comments.store', ['post' => $post->id]), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('comments.show', ['post' => $post->id, 'id' => $comment->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_comment_not_found()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('comments.show', ['post' => $post->id, 'id' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $updatedData = ['content' => 'Updated comment content'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('comments.update', ['post' => $post->id, 'id' => $comment->id]), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment content',
        ]);
    }

    /** @test */
    public function it_can_delete_a_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('comments.destroy', ['post' => $post->id, 'id' => $comment->id]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
