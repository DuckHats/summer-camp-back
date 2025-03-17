<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $course;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->course = Course::factory()->create([
            'title' => 'Curso de Laravel',
            'description' => 'Aprende Laravel desde cero',
            'short_description' => 'Laravel básico',
            'instructor_id' => $this->user->id,
            'status' => 1,
            'duration' => 5,
            'header_image' => 'https://example.com/image.jpg',
        ]);
    }

    /** @test */
    public function it_can_list_courses()
    {
        $response = $this->getJson(route('courses.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_course()
    {
        $courseData = [
            'title' => 'Nuevo Curso',
            'description' => 'Descripción del curso',
            'short_description' => 'Curso breve',
            'instructor_id' => $this->user->id,
            'status' => 1,
            'duration' => 5,
            'header_image' => 'https://example.com/image2.jpg',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('courses.store'), $courseData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('courses', ['title' => 'Nuevo Curso']);
    }

    /** @test */
    public function it_can_show_a_course()
    {
        $response = $this->getJson(route('courses.show', $this->course->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $updatedData = [
            'title' => 'Curso de Laravel Avanzado',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('courses.update', $this->course->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('courses', ['title' => 'Curso de Laravel Avanzado']);
    }

    /** @test */
    public function it_can_patch_a_course()
    {
        $patchData = [
            'title' => 'Curso de Laravel Modificado',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('courses.patch', $this->course->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('courses', ['title' => 'Curso de Laravel Modificado']);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('courses.destroy', $this->course->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('courses', ['id' => $this->course->id]);
    }

    /** @test */
    public function it_should_fail_if_course_not_found_on_show()
    {
        $response = $this->getJson(route('courses.show', 9999));

        $response->assertStatus(404);
    }
}
