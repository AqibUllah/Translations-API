<?php

namespace Tests\Unit\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Models\Translation;
use App\Services\TranslationService;
use App\Repositories\Contracts\TranslationRepositoryInterface;

class TranslationServiceTest extends TestCase
{
    protected TranslationService $service;
    protected TranslationRepositoryInterface|MockInterface $mockRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepo = \Mockery::mock(TranslationRepositoryInterface::class);
        $this->service = new TranslationService($this->mockRepo);
    }

    public function test_list_translations()
    {
        $filters = ['locale' => 'en'];

        // Create a real (or semi-real) paginator
        $data = collect([]);
        $fakePaginator = new LengthAwarePaginator(
            $data,
            $data->count(),
            50
        );

        $this->mockRepo
            ->shouldReceive('paginate')
            ->with($filters, 50)
            ->andReturn($fakePaginator);

        $result = $this->service->listTranslations($filters, 50);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_create_translation()
    {
        $data = [
            'group' => 'auth',
            'locale' => 'en',
            'key' => 'my_key',
            'value' => 'My Value',
            'tags' => ['mobile'],
        ];

        $fake = new Translation($data);
        $fake->id = 123;

        $this->mockRepo
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($fake);

        $created = $this->service->createTranslation($data);

        $this->assertEquals(123, $created->id);
    }

    public function test_update_translation()
    {
        $data = ['key' => 'new_key'];
        $existing = new Translation(['id' => 1, 'locale' => 'en', 'key' => 'old_key']);

        $this->mockRepo->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($existing);

        $this->mockRepo->shouldReceive('update')
            ->once()
            ->with($existing, $data)
            ->andReturn(new Translation(['id' => 1, 'key' => 'new_key']));

        $updated = $this->service->updateTranslation(1, $data);

        $this->assertEquals('new_key', $updated->key);
    }
}
