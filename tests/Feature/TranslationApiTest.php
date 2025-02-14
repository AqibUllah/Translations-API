<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_endpoint_returns_filtered_results()
    {
        Translation::factory()->create([
            'locale' => 'en',
            'group' => 'messages',
            'key' => 'hello_world',
            'tags' => ['desktop','mobile'],
        ]);

        Translation::factory()->create([
            'locale' => 'fr',
            'group' => 'messages',
            'key' => 'bonjour_monde',
            'tags' => ['desktop','mobile'],
        ]);

        $user = $this->fake_user();

        $response = $this->actingAs($user)->getJson('/api/translations?locale=en');

        $response->assertStatus(200)
            ->assertJsonFragment(['key' => 'hello_world'])
            ->assertJsonMissing(['key' => 'bonjour_monde']);
    }

    public function test_create_endpoint_requires_fields()
    {
        $user = $this->fake_user();

        // Missing required fields => 422
        $response = $this->actingAs($user)->postJson('/api/translations', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['locale', 'key', 'value']);
    }

    public function test_can_create_translation()
    {
        $data = [
            'group'     => 'messages',
            'locale'    => 'en',
            'key'       => 'welcome_msg',
            'value'     => 'Welcome to our site',
            'tags'      => ['mobile']
        ];

        $user = $this->fake_user();

        $response = $this->actingAs($user)->postJson('/api/translations', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['key' => 'welcome_msg']);

        $this->assertDatabaseHas('translations', ['key' => 'welcome_msg']);
    }

//    public function test_can_update_translation()
//    {
//        $translation = Translation::factory()->create(['key' => 'old_key']);
//
//        $user = $this->fake_user();
//
//        $response = $this->actingAs($user)->putJson("/api/translations/{$translation->id}", [
//            'key' => 'new_key'
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJsonFragment(['key' => 'new_key']);
//
//        $this->assertDatabaseHas('translations', ['id' => $translation->id, 'key' => 'new_key']);
//    }

    public function test_can_delete_translation()
    {
        $translation = Translation::factory()->create();

        $user = $this->fake_user();

        $response = $this->actingAs($user)->deleteJson("/api/translations/{$translation->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('translations', ['id' => $translation->id]);
    }

    public function test_export_endpoint_returns_grouped_locales()
    {

        Translation::factory()->create([
            'group' => 'messages',
            'locale' => 'en',
            'key' => 'greeting',
            'value' => 'Hello',
            'tags' => ['mobile'],

        ]);

        Translation::factory()->create([
            'group' => 'messages',
            'locale' => 'fr',
            'key' => 'greeting',
            'value' => 'Bonjour',
            'tags' => ['desktop']
        ]);

        $user = $this->fake_user();

        $response = $this->actingAs($user)->getJson('/api/translations/export');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'en' => ['greeting'],
                'fr' => ['greeting'],
            ]);
    }

    public function fake_user()
    {
        return User::create([
            'name' => fake()->name(),
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ]);
    }
}
