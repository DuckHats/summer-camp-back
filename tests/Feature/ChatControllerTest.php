<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $chat;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->chat = Chat::factory()->create();

        ChatMember::create([
            'chat_id' => $this->chat->id,
            'user_id' => $this->user->id,
            'joined_at' => now(),
        ]);
    }

    /** @test */
    public function it_can_list_chats()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('chats.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_chat()
    {
        $chatData = [
            'chat_type' => 'group',
            'members' => [$this->user->id],
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->postJson(route('chats.store'), $chatData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('chats', ['chat_type' => 'group']);
    }

    /** @test */
    public function it_can_show_a_chat()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('chats.show', $this->chat->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_chat()
    {
        $updatedData = [
            'chat_type' => 'private',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('chats.patch', $this->chat->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('chats', ['chat_type' => 'private']);
    }

    /** @test */
    public function it_can_delete_a_chat()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->deleteJson(route('chats.destroy', $this->chat->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('chats', ['id' => $this->chat->id]);
    }

    /** @test */
    public function it_can_patch_a_chat()
    {
        $patchData = [
            'chat_type' => 'group',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->patchJson(route('chats.patch', $this->chat->id), $patchData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('chats', ['chat_type' => 'group']);
    }

    /** @test */
    public function it_should_fail_if_chat_not_found_on_show()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)->getJson(route('chats.show', 9999));

        $response->assertStatus(404);
    }
}
