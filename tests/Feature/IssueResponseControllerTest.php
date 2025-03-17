<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\IssueResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IssueResponseControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $issue;

    protected $issueResponse;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->issue = Issue::factory()->create(['user_id' => $this->user->id]);
        $this->issueResponse = IssueResponse::factory()->create([
            'user_id' => $this->user->id,
            'issue_id' => $this->issue->id,
        ]);
    }

    /** @test */
    public function it_can_list_issue_responses()
    {
        $response = $this->getJson(route('issue_responses.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_issue_response()
    {
        $responseData = [
            'user_id' => $this->user->id,
            'content' => 'This is a test response.',
            'is_solution' => true,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('issue_responses.store', ['id' => $this->issue->id]), $responseData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issue_responses', ['content' => 'This is a test response.']);
    }

    /** @test */
    public function it_can_show_an_issue_response()
    {
        $response = $this->getJson(route('issue_responses.show', ['id' => $this->issueResponse->id]));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_an_issue_response()
    {
        $updatedData = ['content' => 'Updated response content.'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issue_responses.patch', ['issueid' => $this->issue->id, 'id' => $this->issueResponse->id]), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('issue_responses', ['content' => 'Updated response content.']);
    }

    /** @test */
    public function it_can_partially_update_an_issue_response()
    {
        $updatedData = ['content' => 'Partially updated response.'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issue_responses.patch', ['issueid' => $this->issue->id, 'id' => $this->issueResponse->id]), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('issue_responses', ['content' => 'Partially updated response.']);
    }

    /** @test */
    public function it_can_delete_an_issue_response()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('issue_responses.destroy', ['issueid' => $this->issue->id, 'id' => $this->issueResponse->id]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('issue_responses', ['id' => $this->issueResponse->id]);
    }

    /** @test */
    public function it_should_fail_if_issue_response_not_found_on_show()
    {
        $response = $this->getJson(route('issue_responses.show', ['id' => 9999]));
        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_issue_response_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('issue_responses.patch', ['issueid' => $this->issue->id, 'id' => 9999]), ['content' => 'Updated response content.']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_issue_response_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('issue_responses.destroy', ['issueid' => $this->issue->id, 'id' => 9999]));

        $response->assertStatus(404);
    }
}
