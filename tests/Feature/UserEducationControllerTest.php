<?php

namespace Tests\Feature;

use App\Models\EducationalInstitution;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserEducation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserEducationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $education;

    protected $institution;

    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create an educational institution
        $this->institution = EducationalInstitution::factory()->create();

        // Create a user education
        $this->education = UserEducation::factory()->create([
            'user_id' => $this->user->id,
            'institution_id' => $this->institution->id,
        ]);

        // Create a tag
        $this->tag = Tag::factory()->create();
        $this->education->tags()->attach($this->tag->id, ['entity_type' => 'App\Models\UserEducation']);
    }

    /** @test */
    public function it_can_list_user_educations()
    {
        $response = $this->getJson(route('user_educations.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success']);
    }

    /** @test */
    public function it_can_create_a_user_education()
    {
        $requestData = [
            'user_id' => $this->user->id,
            'institution_id' => $this->institution->id,
            'description' => 'Bachelor of Science in Computer Science',
            'start_date' => '2020-01-01',
            'end_date' => '2024-01-01',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('user_educations.store'), $requestData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_educations', ['description' => 'Bachelor of Science in Computer Science']);
    }

    /** @test */
    public function it_can_show_a_user_education()
    {
        $response = $this->getJson(route('user_educations.show', $this->education->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->education->id]);
    }

    /** @test */
    public function it_can_update_a_user_education()
    {
        $updatedData = [
            'description' => 'Master of Science in Software Engineering',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_educations.update', $this->education->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_educations', ['description' => 'Master of Science in Software Engineering']);
    }

    /** @test */
    public function it_can_delete_a_user_education()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_educations.destroy', $this->education->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_educations', ['id' => $this->education->id]);
    }

    /** @test */
    public function it_should_fail_if_user_education_not_found_on_show()
    {
        $response = $this->getJson(route('user_educations.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_user_education_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('user_educations.destroy', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_a_user_education()
    {
        $partialUpdateData = [
            'end_date' => '2025-01-01',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('user_educations.patch', $this->education->id), $partialUpdateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_educations', ['end_date' => '2025-01-01']);
    }
}
