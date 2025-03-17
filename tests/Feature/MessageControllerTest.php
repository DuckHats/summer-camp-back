<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MessageControllerTest extends TestCase
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

        $this->chat = Chat::factory()->create();

        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_send_message_to_chat()
    {
        $messageData = [
            'sender_id' => $this->user->id,
            'message' => 'This is a test message',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('chats.messages.store', $this->chat->id), $messageData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('messages', ['message' => 'This is a test message']);
    }

    /** @test */
    public function it_can_get_messages_from_chat()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('chats.messages.index', $this->chat->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_message()
    {
        $message = Message::factory()->create(['chat_id' => $this->chat->id, 'sender_id' => $this->user->id]);

        $updatedData = [
            'sender_id' => $this->user->id,
            'message' => 'This is a test message',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('messages.update', $message->id), $updatedData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_message()
    {
        $message = Message::factory()->create(['chat_id' => $this->chat->id, 'sender_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('messages.destroy', $message->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    /** @test */
    public function it_should_fail_if_message_not_found_on_update()
    {
        $updatedData = [
            'sender_id' => $this->user->id,
            'message' => 'caracola',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('messages.update', 9999), $updatedData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_message_not_found_on_delete()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('messages.destroy', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_fail_if_validation_fails_on_send_message()
    {
        $invalidMessageData = [
            'sender_id' => 99999,
            'message' => 'caracola',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('chats.messages.store', $this->chat->id), $invalidMessageData);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_should_fail_if_chat_not_found_on_get_messages()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('chats.messages.index', 9999));

        $response->assertStatus(404);
    }
}
