<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Son;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SonControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $group;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->group = Group::factory()->create();

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_sons()
    {
        Son::factory(10)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('sons.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_son()
    {
        $sonData = [
            'dni' => '12345678A',
            'first_name' => 'Test',
            'last_name' => 'Son',
            'birth_date' => '2021-01-01',
            'group_id' => $this->group->id,
            'profile_picture_url' => 'https://example.com/image.jpg',
            'profile_extra_info' => 'Extra info',
            'gender' => 'male',
            'user_id' => $this->user->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('sons.store'), $sonData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('sons', $sonData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_son()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('sons.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_son()
    {
        $son = Son::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('sons.show', $son->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_son_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('sons.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_son()
    {
        $son = Son::factory()->create(['user_id' => $this->user->id]);

        $updatedData = ['dni' => '12345678K', 'first_name' => 'Johan'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('sons.update', $son->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('sons', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_a_son()
    {
        $son = Son::factory()->create(['user_id' => $this->user->id]);

        $patchData = ['dni' => '12345678K'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('sons.patch', $son->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('sons', $patchData);
    }

    /** @test */
    public function it_can_delete_a_son()
    {
        $son = Son::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('sons.destroy', $son->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('sons', ['id' => $son->id]);
    }
}
