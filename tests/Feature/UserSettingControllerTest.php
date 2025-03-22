<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSettingControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        UserSetting::factory()->create([
            'user_id' => $this->user->id,
            'key' => 'role',
            'value' => 'admin',
        ]);
    }

    /** @test */
    public function it_can_list_user_settings()
    {
        UserSetting::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('user_settings.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_user_setting()
    {
        $data = [
            'user_id' => $this->user->id,
            'key' => 'theme',
            'value' => 'dark',
        ];

        $response = $this->actingAs($this->user)->postJson(route('user_settings.store'), $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.key', $data['key'])
            ->assertJsonPath('data.value', $data['value']);

        $this->assertDatabaseHas('user_settings', $data);
    }

    /** @test */
    public function it_shows_a_user_setting()
    {
        $userSetting = UserSetting::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson(route('user_settings.show', $userSetting->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_not_found_when_user_setting_does_not_exist()
    {
        $response = $this->actingAs($this->user)->getJson(route('user_settings.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_user_setting()
    {
        $userSetting = UserSetting::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'value' => 'light',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_settings.patch', $userSetting->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.value', $updateData['value']);

        $this->assertDatabaseHas('user_settings', array_merge(['id' => $userSetting->id], $updateData));
    }

    /** @test */
    public function it_returns_not_found_when_updating_non_existent_user_setting()
    {
        $updateData = [
            'value' => 'light',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_settings.update', 999), $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_user_setting()
    {
        $userSetting = UserSetting::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson(route('user_settings.destroy', $userSetting->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_settings', ['id' => $userSetting->id]);
    }

    /** @test */
    public function it_returns_not_found_when_deleting_non_existent_user_setting()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('user_settings.destroy', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_partially_update_a_user_setting()
    {
        $userSetting = UserSetting::factory()->create(['user_id' => $this->user->id]);

        $patchData = [
            'value' => 'light',
        ];

        $response = $this->actingAs($this->user)->patchJson(route('user_settings.patch', $userSetting->id), $patchData);

        $response->assertStatus(200)
            ->assertJsonPath('data.value', $patchData['value']);

        $this->assertDatabaseHas('user_settings', array_merge(['id' => $userSetting->id], $patchData));
    }
}
