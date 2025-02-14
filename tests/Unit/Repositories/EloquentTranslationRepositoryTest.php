<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\EloquentTranslationRepository;

class EloquentTranslationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EloquentTranslationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentTranslationRepository();
    }

    public function test_can_paginate_with_filters()
    {
        Translation::factory()->create([
            'locale' => 'en',
            'group' => 'messages',
            'key' => 'hello_world',
            'tags' => ['mobile','desktop'],
        ]);

        // Another record
        Translation::factory()->create([
            'locale' => 'fr',
            'group' => 'messages',
            'key' => 'bonjour_monde',
            'tags' => ['desktop'],
        ]);

        // Filter for 'en' locale
        $filters = ['locale' => 'en'];
        $results = $this->repository->paginate($filters, 10);

        $this->assertCount(1, $results->items());
        $this->assertEquals('en', $results->items()[0]->locale);
    }

    public function test_can_create_translation()
    {
        $data = [
            'locale' => 'en',
            'group' => 'auth',
            'key' => 'test_key',
            'value' => 'Test Value',
            'tags' => ['mobile'],
        ];

        $translation = $this->repository->create($data);

        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'locale' => 'en',
            'group' => 'auth',
            'key' => 'test_key',
            'value' => 'Test Value',
        ]);
    }

    public function test_can_find_and_update_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'group' => 'messages',
            'key' => 'old_key',
            'tags' => ['desktop'],
        ]);

        $this->repository->update($translation, [
            'key' => 'new_key'
        ]);

        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'key' => 'new_key'
        ]);
    }

    public function test_can_delete_translation()
    {
        $translation = Translation::factory()->create();

        $this->repository->delete($translation);

        $this->assertDatabaseMissing('translations', [
            'id' => $translation->id
        ]);
    }

    public function test_can_get_export_data()
    {
        Translation::factory()->create([
            'locale'    => 'en',
            'group'     => 'messages',
            'key'       => 'hello',
            'value'     => 'Hello!',
            'tags'      => ['mobile'],
        ]);

        Translation::factory()->create([
            'locale'    => 'fr',
            'group'     => 'messages',
            'key'       => 'hello',
            'value'     => 'Bonjour!',
            'tags'      => ['desktop'],
        ]);

        $data = $this->repository->getExportData();
        $this->assertArrayHasKey('en', $data);
        $this->assertArrayHasKey('fr', $data);
        $this->assertEquals('Hello!', $data['en']['hello']);
        $this->assertEquals('Bonjour!', $data['fr']['hello']);
    }
}
