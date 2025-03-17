<?php

namespace Tests\Feature;

use App\Models\EmailVerification;
use App\Models\PasswordReset;
use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $userData = [
            'dni' => '1234567890',
            'username' => 'TestUser',
            'first_name' => 'TestUser',
            'last_name' => 'test_user',
            'email' => 'test_user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_login_a_user()
    {
        $loginData = [
            'email' => $this->user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_fail_if_login_with_invalid_credentials()
    {
        $loginData = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->postJson(route('auth.logout'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_send_reset_password_code()
    {
        $response = $this->postJson(route('auth.sendResetCode'), ['email' => $this->user->email]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_reset_password()
    {
        $this->postJson(route('auth.sendResetCode'), ['email' => $this->user->email]);

        $resetCode = PasswordReset::where('email', $this->user->email)->first()->token;

        $response = $this->postJson(route('auth.resetPassword'), [
            'email' => $this->user->email,
            'code' => $resetCode,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_create_user_basic_settings()
    {
        $userData = [
            'dni' => '1234567890',
            'username' => 'TestUser2',
            'first_name' => 'TestUser2',
            'last_name' => 'test_user2',
            'email' => 'test_user2@example.com',
            'password' => 'password1232',
            'password_confirmation' => 'password1232',
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201);

        $user = User::where('email', 'test_user2@example.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('user_settings', ['user_id' => $user->id, 'key' => 'web.basic_setting', 'value' => 'true']);
    }
}
