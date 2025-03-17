<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JobOfferControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $jobOffer;

    protected $token;

    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create a company for testing
        $this->company = Company::factory()->create([
            'user_creator_id' => $this->user->id,
        ]);

        // Create a job offer for testing
        $this->jobOffer = JobOffer::factory()->create([
            'company_id' => $this->company->id,
        ]);
    }

    /** @test */
    public function it_can_list_job_offers()
    {
        $response = $this->getJson(route('joboffer.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_job_offer()
    {
        $jobData = [
            'company_id' => $this->company->id,
            'title' => 'Test Job',
            'description' => 'Description of the test job.',
            'location' => 'Test Location',
            'salary_average' => 1000,
            'work_schedule' => 'full-time',
            'work_hours' => 40,
            'contract_type' => 'permanent',
            'city' => 'Test City',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('joboffer.store'), $jobData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('job_offers', ['title' => 'Test Job']);
    }

    /** @test */
    public function it_can_show_a_job_offer()
    {
        $response = $this->getJson(route('joboffer.show', $this->jobOffer->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_job_offer()
    {
        $updatedData = [
            'company_id' => $this->company->id,
            'title' => 'Updated Test Job',
            'description' => 'Description of the test job.',
            'location' => 'Updated Test Location',
            'salary_average' => 1000,
            'work_schedule' => 'full-time',
            'work_hours' => 40,
            'contract_type' => 'permanent',
            'city' => 'Test City',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('joboffer.update', $this->jobOffer->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_offers', ['title' => 'Updated Test Job']);
    }

    /** @test */
    public function it_can_partially_update_a_job_offer()
    {
        $updatedData = [
            'location' => 'Updated Test Location 2',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('joboffer.patch', $this->jobOffer->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_offers', ['location' => 'Updated Test Location 2']);
    }

    /** @test */
    public function it_can_delete_a_job_offer()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('joboffer.destroy', $this->jobOffer->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('job_offers', ['id' => $this->jobOffer->id]);
    }

    /** @test */
    public function it_should_fail_if_job_offer_not_found_on_show()
    {
        $response = $this->getJson(route('joboffer.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_job_offer_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('joboffer.update', 9999), ['title' => 'Updated Job Title']);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_job_offer_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('joboffer.destroy', 9999));

        $response->assertStatus(404);
    }
}
