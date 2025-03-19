<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Photo;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PhotoControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        UserSetting::factory()->create(
            [
                'user_id' => $this->user->id,
                'key' => 'role',
                'value' => 'admin',
            ]
        );
        
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_can_list_photos()
    {
        Photo::factory(10)->create();

        $response = $this->getJson(route('photos.index', ['per_page' => 5]));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_photo()
    {
        $photoData = [
            'title' => 'Sample Photo',
            'description' => 'A test photo',
            'group_id' => Group::factory()->create()->id,
            'image_url' => 'https://example.com/photo.jpg',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('photos.store'), $photoData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('photos', $photoData);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_photo()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson(route('photos.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_photo()
    {
        $photo = Photo::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('photos.show', $photo->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_photo_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('photos.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_photo()
    {
        $photo = Photo::factory()->create();

        $updatedData = ['title' => 'Updated Photo', 'description' => 'Updated description', 'group_id' => Group::factory()->create()->id, 'image_url' => 'https://example.com/updated.jpg'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->putJson(route('photos.update', $photo->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('photos', $updatedData);
    }

    /** @test */
    public function it_can_partially_update_a_photo()
    {
        $photo = Photo::factory()->create();

        $patchData = ['title' => 'Partially Updated Title'];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->patchJson(route('photos.patch', $photo->id), $patchData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('photos', $patchData);
    }

    /** @test */
    public function it_can_delete_a_photo()
    {
        $photo = Photo::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->deleteJson(route('photos.destroy', $photo->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
    }
}
