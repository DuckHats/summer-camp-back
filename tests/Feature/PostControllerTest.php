<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostControllerTest extends TestCase
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
    public function it_can_list_posts()
    {
        Post::factory(10)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('posts.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_post()
    {
        $postData = [
            'user_id' => $this->user->id,
            'title' => 'Test Post',
            'content' => 'This is a test content.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('posts.store'), $postData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', $postData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_post()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('posts.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('posts.show', $post->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_post_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('posts.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $updatedData = ['title' => 'Updated Title', 'content' => 'Updated content'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('posts.update', $post->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $patchData = ['title' => 'Partially Updated Title'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('posts.patch', $post->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', $patchData);
    }

    /** @test */
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('posts.destroy', $post->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
