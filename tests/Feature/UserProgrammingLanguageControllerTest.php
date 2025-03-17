<?php

namespace Tests\Feature;

use App\Models\ProgrammingLanguage;
use App\Models\SkillLevel;
use App\Models\User;
use App\Models\UserProgrammingLanguage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProgrammingLanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $programmingLanguage;

    private $skillLevel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->programmingLanguage = ProgrammingLanguage::factory()->create();
        $this->skillLevel = SkillLevel::factory()->create();
    }

    /** @test */
    public function it_can_list_user_programming_languages()
    {
        UserProgrammingLanguage::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('user_programming_languages.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_user_programming_language()
    {
        $data = [
            'user_id' => $this->user->id,
            'language_id' => $this->programmingLanguage->id,
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->postJson(route('user_programming_languages.store'), $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.language_id', $this->programmingLanguage->id);

        $this->assertDatabaseHas('users_programming_languages', $data);
    }

    /** @test */
    public function it_shows_a_user_programming_language()
    {
        $userProgrammingLanguage = UserProgrammingLanguage::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('user_programming_languages.show', $userProgrammingLanguage->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_not_found_when_user_programming_language_does_not_exist()
    {
        $response = $this->actingAs($this->user)->getJson(route('user_programming_languages.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_user_programming_language()
    {
        $userProgrammingLanguage = UserProgrammingLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_programming_languages.patch', $userProgrammingLanguage->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.level_id', $this->skillLevel->id);

        $this->assertDatabaseHas('users_programming_languages', array_merge(['id' => $userProgrammingLanguage->id], $updateData));
    }

    /** @test */
    public function it_returns_not_found_when_updating_non_existent_user_programming_language()
    {
        $updateData = [
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_programming_languages.update', 999), $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_user_programming_language()
    {
        $userProgrammingLanguage = UserProgrammingLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('user_programming_languages.destroy', $userProgrammingLanguage->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users_programming_languages', ['id' => $userProgrammingLanguage->id]);
    }

    /** @test */
    public function it_returns_not_found_when_deleting_non_existent_user_programming_language()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('user_programming_languages.destroy', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_a_user_programming_language()
    {
        $userProgrammingLanguage = UserProgrammingLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $patchData = [
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_programming_languages.patch', $userProgrammingLanguage->id), $patchData);

        $response->assertStatus(200)
            ->assertJsonPath('data.level_id', $this->skillLevel->id);

        $this->assertDatabaseHas('users_programming_languages', array_merge(['id' => $userProgrammingLanguage->id], $patchData));
    }
}
