<?php

namespace Tests\Feature;

use App\Models\EducationalInstitution;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EducationalInstitutionControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $institution;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->institution = EducationalInstitution::factory()->create([
            'user_creator_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_educational_institutions()
    {
        $response = $this->getJson(route('educational_institutions.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_educational_institution()
    {
        $institutionData = [
            'name' => 'New Educational Institution',
            'nif' => '987654321',
            'email' => 'institution@example.com',
            'phone' => '9876543210',
            'user_creator_id' => $this->user->id,
            'address' => '456 Street',
            'zip_code' => '54321',
            'city' => 'City Name',
            'country' => 'Country Name',
            'employees_count' => 50,
            'description' => 'A new educational institution',
            'logo' => 'logo.png',
            'location' => 'New Location',
            'type' => 'University',
            'web_url' => 'http://institution.com',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->postJson(route('educational_institutions.store'), $institutionData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('educational_institutions', ['name' => 'New Educational Institution']);
    }

    /** @test */
    public function it_can_show_an_educational_institution()
    {
        $response = $this->getJson(route('educational_institutions.show', $this->institution->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_an_educational_institution()
    {
        $updatedData = [
            'name' => 'Updated Educational Institution Name',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('educational_institutions.update', $this->institution->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('educational_institutions', ['name' => 'Updated Educational Institution Name']);
    }

    /** @test */
    public function it_can_delete_an_educational_institution()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->deleteJson(route('educational_institutions.destroy', $this->institution->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('educational_institutions', ['id' => $this->institution->id]);
    }

    /** @test */
    public function it_can_patch_an_educational_institution()
    {
        $patchData = [
            'description' => 'Updated description for the institution',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('educational_institutions.patch', $this->institution->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('educational_institutions', ['description' => 'Updated description for the institution']);
    }

    /** @test */
    public function it_should_fail_if_educational_institution_not_found_on_show()
    {
        $response = $this->getJson(route('educational_institutions.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_educational_institution_not_found_on_update()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('educational_institutions.update', 9999), [
            'name' => 'Non-Existent Institution',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_educational_institution_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->deleteJson(route('educational_institutions.destroy', 9999));

        $response->assertStatus(404);
    }
}
