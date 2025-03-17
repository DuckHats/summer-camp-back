<?php

namespace Tests\Feature;

use App\Models\ContentSave;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContentSaveControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $post;

    protected $contentSave;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->post = Post::factory()->create();

        $this->contentSave = ContentSave::factory()->create([
            'user_id' => $this->user->id,
            'saveable_id' => $this->post->id,
            'saveable_type' => Post::class,
        ]);
    }

    /** @test */
    public function it_can_list_saved_contents()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('saves.list'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_unsave_a_post()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('saves.post.store', $this->post->id), [
                'type' => 'post',
            ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('content_saves', [
            'user_id' => $this->user->id,
            'saveable_id' => $this->post->id,
            'saveable_type' => Post::class,
        ]);
    }

    /** @test */
    public function it_can_delete_a_saved_content()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('saves.destroy', $this->contentSave->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('content_saves', ['id' => $this->contentSave->id]);
    }

    /** @test */
    public function it_should_fail_if_content_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('saves.destroy', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_saving_invalid_type()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('saves.post.store', $this->post->id), [
                'type' => 'invalid_type',
            ]);
        $response->assertStatus(400);
    }
}
