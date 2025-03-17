<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $company;

    protected $token;

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
    }

    /** @test */
    public function it_can_list_companies()
    {
        $response = $this->getJson(route('companies.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_company()
    {
        $companyData = [
            'name' => 'New Company',
            'nif' => '123456789',
            'email' => 'company@example.com',
            'phone' => '1234567890',
            'user_creator_id' => $this->user->id,
            'address' => '123 Street',
            'zip_code' => '12345',
            'city' => 'City',
            'country' => 'Country',
            'employees_count' => 10,
            'description' => 'A new company',
            'logo' => 'logo.png',
            'location' => 'Location',
            'sector' => 'Tech',
            'web_url' => 'http://example.com',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->postJson(route('companies.store'), $companyData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('companies', ['name' => 'New Company']);
    }

    /** @test */
    public function it_can_show_a_company()
    {
        $response = $this->getJson(route('companies.show', $this->company->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_company()
    {
        $updatedData = [
            'name' => 'Updated Company Name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('companies.patch', $this->company->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('companies', ['name' => 'Updated Company Name']);
    }

    /** @test */
    public function it_can_delete_a_company()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->deleteJson(route('companies.destroy', $this->company->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('companies', ['id' => $this->company->id]);
    }

    /** @test */
    public function it_can_patch_a_company()
    {
        $patchData = [
            'name' => 'Patched Company Name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('companies.patch', $this->company->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('companies', ['name' => 'Patched Company Name']);
    }

    /** @test */
    public function it_should_fail_if_company_not_found_on_show()
    {
        $response = $this->getJson(route('companies.show', 1999));

        $response->assertStatus(404);
    }
}
