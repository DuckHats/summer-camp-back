<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CourseModuleControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $course;

    protected $courseModule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->course = Course::factory()->create();

        $this->courseModule = CourseModule::factory()->create(['course_id' => $this->course->id]);
    }

    /** @test */
    public function it_can_list_course_modules()
    {
        $response = $this->getJson(route('coursesmodules.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_course_module()
    {
        $courseModuleData = [
            'course_id' => $this->course->id,
            'title' => 'New Module',
            'description' => 'Module description',
            'status' => 1,
            'content' => 'Module content',
            'order_number' => 1,
            'header_image' => 'image.png',
            'duration' => 60,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('coursesmodules.store'), $courseModuleData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('course_modules', ['title' => 'New Module']);
    }

    /** @test */
    public function it_can_show_a_course_module()
    {
        $response = $this->getJson(route('coursesmodules.show', $this->courseModule->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_course_module()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('coursesmodules.delete', $this->courseModule->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('course_modules', ['id' => $this->courseModule->id]);
    }

    /** @test */
    public function it_can_patch_a_course_module()
    {
        $patchData = ['title' => 'Patched Module Name'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('coursesmodules.patch', $this->courseModule->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('course_modules', ['title' => 'Patched Module Name']);
    }

    /** @test */
    public function it_should_fail_if_course_module_not_found_on_show()
    {
        $response = $this->getJson(route('coursesmodules.show', 9999));

        $response->assertStatus(404);
    }
}
