<?php

namespace Tests\Feature;

use App\Models\Error;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ErrorControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $error;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create an error record for testing
        $this->error = Error::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_errors()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('errors.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_error()
    {
        $errorData = [
            'error_code' => '503',
            'error_message' => 'Internal Server Error',
            'stack_trace' => 'Trace details...',
            'user_id' => $this->user->id,
            'session_id' => 4,
            'occurred_at' => '2023-10-01T12',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('errors.store'), $errorData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('errors', ['error_code' => '503']);
    }

    /** @test */
    public function it_can_show_an_error()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('errors.show', $this->error->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_an_error()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('errors.destroy', $this->error->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('errors', ['id' => $this->error->id]);
    }

    /** @test */
    public function it_should_fail_if_error_not_found_on_show()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('errors.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_error_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('errors.update', 9999), ['error_message' => 'Updated Error Message']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_error_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('errors.destroy', 9999));

        $response->assertStatus(404);
    }
}
