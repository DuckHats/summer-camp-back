<?php

namespace Tests\Feature;

use App\Models\CourseModule;
use App\Models\ModuleCompletion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModuleCompletionControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $completion;

    protected $token;

    protected $module;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->module = CourseModule::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->completion = ModuleCompletion::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_module_completions()
    {
        $response = $this->getJson(route('mcompletion.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_module_completion()
    {
        $data = [
            'user_id' => $this->user->id,
            'module_id' => $this->module->id,
            'completed_at' => '2025-02-24 02:29:41',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('mcompletion.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('module_completions', ['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_show_a_module_completion()
    {
        $response = $this->getJson(route('mcompletion.show', $this->completion->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_module_completion()
    {
        $data = [
            'user_id' => $this->user->id,
            'module_id' => $this->module->id,
            'completed_at' => '2023-02-14 02:29:41',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('mcompletion.update', $this->completion->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('module_completions', ['id' => $this->completion->id]);
    }

    /** @test */
    public function it_can_delete_a_module_completion()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('mcompletion.destroy', $this->completion->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('module_completions', ['id' => $this->completion->id]);
    }

    /** @test */
    public function it_should_fail_if_module_completion_not_found()
    {
        $response = $this->getJson(route('mcompletion.show', 9999));
        $response->assertStatus(404);
    }
}
