<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CourseRegistrationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $course;

    protected $registration;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->course = Course::factory()->create();

        $this->registration = CourseRegistration::factory()->create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'progress' => 50,
        ]);
    }

    /** @test */
    public function it_can_list_course_registrations()
    {
        $response = $this->getJson(route('cregistration.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_course_registration()
    {
        $registrationData = [
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'registered_at' => '2025-03-06 18:01:59',
            'progress' => 10,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('cregistration.store'), $registrationData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('course_registrations', ['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_show_a_course_registration()
    {
        $response = $this->getJson(route('cregistration.show', $this->registration->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_course_registration()
    {
        $updatedData = [
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'registered_at' => '2025-03-06 18:01:59',
            'progress' => 75,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('cregistration.update', $this->registration->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('course_registrations', ['progress' => 75]);
    }

    /** @test */
    public function it_can_delete_a_course_registration()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('cregistration.destroy', $this->registration->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('course_registrations', ['id' => $this->registration->id]);
    }

    /** @test */
    public function it_should_fail_if_registration_not_found_on_show()
    {
        $response = $this->getJson(route('cregistration.show', 9999));
        $response->assertStatus(404);
    }
}
