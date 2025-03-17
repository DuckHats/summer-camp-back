<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\UserExperience;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserExperienceControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $experience;

    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->company = Company::factory()->create([
            'user_creator_id' => $this->user->id,
        ]);

        $this->experience = UserExperience::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);
    }

    /** @test */
    public function it_can_list_user_experiences()
    {
        $response = $this->getJson(route('user_experiences.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_user_experience()
    {
        $requestData = [
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'role' => 'Software Engineer',
            'start_date' => '2022-01-01',
            'end_date' => '2023-01-01',
            'is_current' => false,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('user_experiences.store'), $requestData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_experiences', ['role' => 'Software Engineer']);
    }

    /** @test */
    public function it_can_show_a_user_experience()
    {
        $response = $this->getJson(route('user_experiences.show', $this->experience->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->experience->id]);
    }

    /** @test */
    public function it_can_update_a_user_experience()
    {
        $updatedData = [
            'role' => 'Senior Software Engineer',
            'is_current' => true,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_experiences.update', $this->experience->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_experiences', ['role' => 'Senior Software Engineer']);
    }

    /** @test */
    public function it_can_partially_update_a_user_experience()
    {
        $partialData = [
            'role' => 'Lead Engineer',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_experiences.patch', $this->experience->id), $partialData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_experiences', ['role' => 'Lead Engineer']);
    }

    /** @test */
    public function it_can_delete_a_user_experience()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_experiences.destroy', $this->experience->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_experiences', ['id' => $this->experience->id]);
    }

    /** @test */
    public function it_should_fail_if_user_experience_not_found_on_show()
    {
        $response = $this->getJson(route('user_experiences.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_user_experience_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('user_experiences.update', 9999), ['role' => 'Nonexistent']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_user_experience_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_experiences.destroy', 9999));

        $response->assertStatus(404);
    }
}
