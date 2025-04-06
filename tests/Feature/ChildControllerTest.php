<?php

namespace Tests\Feature;

use App\Models\Child;
use App\Models\Group;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChildControllerTest extends TestCase
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

        UserSetting::factory()->create([
            'user_id' => $this->user->id,
            'key' => 'role',
            'value' => 'admin',
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_childs()
    {
        Child::factory(10)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('childs.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_child()
    {
        $childData = [
            'dni' => '12345678A',
            'first_name' => 'Test',
            'last_name' => 'Child',
            'birth_date' => '2021-01-01',
            'group_id' => $this->group->id,
            'profile_picture_url' => 'https://example.com/image.jpg',
            'profile_extra_info' => 'Extra info',
            'gender' => 'male',
            'user_id' => $this->user->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('childs.store'), $childData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('childs', $childData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_child()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('childs.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_child()
    {
        $child = Child::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('childs.show', $child->id));

        $response->assertStatus(200);
    }

    public function it_can_inspect_a_child()
    {
        $child = Child::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('childs.inspect', $child->id));

        $response->assertStatus(200);
    }

    public function it_can_inspect_multiple_a_child()
    {
        $child1 = Child::factory()->create(['user_id' => $this->user->id]);
        $child2 = Child::factory()->create(['user_id' => $this->user->id]);
        $child3 = Child::factory()->create(['user_id' => $this->user->id]);

        $childIds = [$child1->id, $child2->id, $child3->id];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('childs.multipleInspect', $childIds));

        $response->assertStatus(200);
    }

    public function it_should_fail_if_child_not_exists_on_inspect()
    {

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('childs.inspect', 999));

        $response->assertStatus(404);
    }

    public function it_should_fail_if_child_not_exists_on_multiple_inspect()
    {
        $childIds = [9999, 9998];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('childs.multipleInspect', $childIds));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_if_child_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('childs.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_child()
    {
        $child = Child::factory()->create(['user_id' => $this->user->id]);

        $updatedData = ['dni' => '12345678K', 'first_name' => 'Johan'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('childs.update', $child->id), $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('childs', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_a_child()
    {
        $child = Child::factory()->create(['user_id' => $this->user->id]);

        $patchData = ['dni' => '12345678K'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('childs.patch', $child->id), $patchData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('childs', $patchData);
    }

    /** @test */
    public function it_can_delete_a_child()
    {
        $child = Child::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('childs.destroy', $child->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('childs', ['id' => $child->id]);
    }
}
