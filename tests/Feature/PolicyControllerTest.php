<?php

namespace Tests\Feature;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PolicyControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $policy;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->policy = Policy::factory(
            ['user_id' => $this->user->id]
        )->create();
    }

    /** @test */
    public function it_can_list_policies()
    {
        $response = $this->getJson(route('policy.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_policy()
    {
        $policyData = ['user_id' => $this->user->id,
            'accept_newsletter' => 1,
            'accept_privacy_policy' => 1,
            'accept_terms_of_use' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('policy.store'), $policyData);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_show_a_policy()
    {
        $response = $this->getJson(route('policy.show', $this->policy->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_policy()
    {
        $updatedData = ['user_id' => $this->user->id,
            'accept_newsletter' => 1,
            'accept_privacy_policy' => 1,
            'accept_terms_of_use' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('policy.update', $this->policy->id), $updatedData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_patch_a_policy()
    {
        $patchData = ['user_id' => $this->user->id,
            'accept_newsletter' => 1];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('policy.patch', $this->policy->id), $patchData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_policy()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('policy.destroy', $this->policy->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('policies', ['id' => $this->policy->id]);
    }

    /** @test */
    public function it_should_fail_if_policy_not_found_on_show()
    {
        $response = $this->getJson(route('policy.show', 1999));

        $response->assertStatus(404);
    }
}
