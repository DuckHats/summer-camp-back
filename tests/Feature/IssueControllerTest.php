<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IssueControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $issue;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create an issue record for testing
        $this->issue = Issue::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_issues()
    {
        $response = $this->getJson(route('issues.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_issue()
    {
        $issueData = [
            'title' => 'Test Issue',
            'content' => 'Content of the test issue.',
            'status' => 'open',
            'user_id' => $this->user->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('issues.store'), $issueData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', ['title' => 'Test Issue']);
    }

    /** @test */
    public function it_can_show_an_issue()
    {
        $response = $this->getJson(route('issues.show', $this->issue->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_an_issue()
    {
        $updatedData = [
            'title' => 'Updated Issue Title',
            'status' => 'closed',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issues.update', $this->issue->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('issues', ['title' => 'Updated Issue Title']);
    }

    /** @test */
    public function it_can_partially_update_an_issue()
    {
        $updatedData = [
            'status' => 'in-progress',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issues.patch', $this->issue->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('issues', ['status' => 'in-progress']);
    }

    /** @test */
    public function it_can_delete_an_issue()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('issues.destroy', $this->issue->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('issues', ['id' => $this->issue->id]);
    }

    /** @test */
    public function it_should_fail_if_issue_not_found_on_show()
    {
        $response = $this->getJson(route('issues.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_issue_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issues.update', 9999), ['title' => 'Updated Issue Title']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_issue_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('issues.destroy', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_include_responses_in_the_issues()
    {
        $response = $this->getJson(route('issues.index', ['with_responses' => true]));

        $response->assertStatus(200);
    }
}
