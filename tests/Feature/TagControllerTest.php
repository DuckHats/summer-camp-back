<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $tag;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        UserRole::create([
            'user_id' => $this->user->id,
            'role_name' => 'admin',
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create a tag
        $this->tag = Tag::factory()->create();
    }

    /** @test */
    public function it_can_list_tags()
    {
        $response = $this->getJson(route('tags.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_tag()
    {
        $requestData = [
            'name' => 'New Tag',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('tags.store'), $requestData);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'New Tag']);
        $this->assertDatabaseHas('tags', ['name' => 'New Tag']);
    }

    /** @test */
    public function it_can_show_a_tag()
    {
        $response = $this->getJson(route('tags.show', $this->tag->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->tag->id]);
    }

    /** @test */
    public function it_can_update_a_tag()
    {
        $updatedData = [
            'name' => 'Updated Tag Name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('tags.update', $this->tag->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Tag Name']);
        $this->assertDatabaseHas('tags', ['name' => 'Updated Tag Name']);
    }

    /** @test */
    public function it_can_partially_update_a_tag()
    {
        $updatedData = [
            'name' => 'Partially Updated Tag Name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('tags.patch', $this->tag->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tags', ['name' => 'Partially Updated Tag Name']);
    }

    /** @test */
    public function it_can_delete_a_tag()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('tags.destroy', $this->tag->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tags', ['id' => $this->tag->id]);
    }

    /** @test */
    public function it_should_fail_if_tag_not_found_on_show()
    {
        $response = $this->getJson(route('tags.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_tag_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('tags.destroy', 9999));

        $response->assertStatus(404)
            ->assertJsonFragment(['code' => 'NOT_FOUND']);
    }
}
