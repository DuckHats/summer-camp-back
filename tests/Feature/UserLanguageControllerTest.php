<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\SkillLevel;
use App\Models\User;
use App\Models\UserLanguage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $language;

    private $skillLevel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->language = Language::factory()->create();
        $this->skillLevel = SkillLevel::factory()->create();
    }

    /** @test */
    public function it_can_list_user_languages()
    {
        UserLanguage::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('user_languages.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_user_language()
    {
        $data = [
            'user_id' => $this->user->id,
            'language_id' => $this->language->id,
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->postJson(route('user_languages.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users_languages', $data);
    }

    /** @test */
    public function it_can_show_a_user_language()
    {
        $userLanguage = UserLanguage::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('user_languages.show', $userLanguage->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_not_found_when_user_language_does_not_exist()
    {
        $response = $this->actingAs($this->user)->getJson(route('user_languages.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_user_language()
    {
        $userLanguage = UserLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'language_id' => $this->language->id,
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_languages.patch', $userLanguage->id), $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users_languages', array_merge(['id' => $userLanguage->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_user_language()
    {
        $userLanguage = UserLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('user_languages.destroy', $userLanguage->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users_languages', ['id' => $userLanguage->id]);
    }

    /** @test */
    public function it_can_patch_a_user_language()
    {
        $userLanguage = UserLanguage::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $patchData = [
            'level_id' => $this->skillLevel->id,
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_languages.patch', $userLanguage->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users_languages', array_merge(['id' => $userLanguage->id], $patchData));
    }
}
