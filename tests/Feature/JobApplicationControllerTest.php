<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JobApplicationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $client;

    protected $company;

    protected $jobOffer;

    protected $jobApplication;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->client = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->client->createToken('auth_token')->plainTextToken;

        $this->company = Company::factory()->create([
            'user_creator_id' => $this->user->id,
        ]);

        $this->jobOffer = JobOffer::factory()->create([
            'company_id' => $this->company->id,
        ]);

        $this->jobApplication = JobApplication::factory()->create([
            'user_id' => $this->client->id,
            'job_offer_id' => $this->jobOffer->id,
        ]);
    }

    /** @test */
    public function it_can_list_job_applications()
    {
        $response = $this->getJson(route('jobapplication.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_job_application()
    {
        $jobData = [
            'job_offer_id' => $this->jobOffer->id,
            'user_id' => $this->client->id,
            'cover_letter' => 'This is a test cover letter.',
            'status' => 'pending',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('jobapplication.store'), $jobData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('job_applications', [
            'user_id' => $this->client->id,
            'job_offer_id' => $this->jobOffer->id,
            'cover_letter' => 'This is a test cover letter.',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_can_show_a_job_application()
    {
        $response = $this->getJson(route('jobapplication.show', $this->jobApplication->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_job_application()
    {
        $updatedData = [
            'user_id' => $this->client->id,
            'job_offer_id' => $this->jobOffer->id,
            'status' => 'accepted',
            'cover_letter' => 'Updated cover letter.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('jobapplication.update', $this->jobApplication->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_applications', [
            'id' => $this->jobApplication->id,
            'cover_letter' => 'Updated cover letter.',
        ]);
    }

    /** @test */
    public function it_can_partially_update_a_job_application()
    {
        $updatedData = [
            'user_id' => $this->client->id,
            'job_offer_id' => $this->jobOffer->id,
            'status' => 'rejected',
            'cover_letter' => 'Partially updated cover letter.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('jobapplication.patch', $this->jobApplication->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_applications', [
            'id' => $this->jobApplication->id,
            'cover_letter' => 'Partially updated cover letter.',
        ]);
    }

    /** @test */
    public function it_can_delete_a_job_application()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('jobapplication.destroy', $this->jobApplication->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('job_applications', ['id' => $this->jobApplication->id]);
    }

    /** @test */
    public function it_should_fail_if_job_application_not_found_on_show()
    {
        $response = $this->getJson(route('jobapplication.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_job_application_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('jobapplication.update', 9999), ['cover_letter' => 'Updated Letter']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_job_application_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('jobapplication.destroy', 9999));

        $response->assertStatus(404);
    }
}
