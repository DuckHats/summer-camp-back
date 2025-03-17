<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Tag;
use App\Models\TagAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TagAssignmentControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $tagAssignment;

    protected $token;

    protected $tag1;

    protected $tag2;

    protected $issue;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create a TagAssignment
        $this->tagAssignment = TagAssignment::factory()->create();

        $this->tag1 = Tag::factory()->create();
        $this->tag2 = Tag::factory()->create();
        $this->issue = Issue::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_tag_assignments()
    {
        $response = $this->getJson(route('tag_assignments.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success']);
    }

    /** @test */
    public function it_can_create_a_tag_assignment()
    {
        $requestData = [
            'tag_id' => $this->tag1->id,
            'entity_type' => 'App\\Models\\Issue',
            'entity_id' => $this->issue->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('tag_assignments.store'), $requestData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('tag_assignments', ['tag_id' => $this->tag1->id]);
    }

    /** @test */
    public function it_can_show_a_tag_assignment()
    {
        $response = $this->getJson(route('tag_assignments.show', $this->tagAssignment->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->tagAssignment->id]);
    }

    /** @test */
    public function it_can_delete_a_tag_assignment()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('tag_assignments.destroy', $this->tagAssignment->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tag_assignments', ['id' => $this->tagAssignment->id]);
    }

    /** @test */
    public function it_should_fail_if_tag_assignment_not_found_on_show()
    {
        $response = $this->getJson(route('tag_assignments.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_tag_assignment_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('tag_assignments.destroy', 9999));

        $response->assertStatus(404);
    }
}
