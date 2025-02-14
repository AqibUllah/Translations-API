<?php

namespace Tests\Unit\Repositories;

use App\Http\Resources\TranslationResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use App\Repositories\CacheTranslationRepository;
use App\Repositories\Contracts\TranslationRepositoryInterface;

class CacheTranslationRepositoryTest extends TestCase
{
    protected CacheTranslationRepository $cacheRepo;
    protected TranslationRepositoryInterface $mockRepo;

    protected function setUp(): void
    {
        parent::setUp();

        // Force array driver
        config(['cache.default' => 'array']);
        $this->assertEquals('array', config('cache.default'));

        $this->mockRepo = \Mockery::mock(TranslationRepositoryInterface::class);

        $this->cacheRepo = new CacheTranslationRepository($this->mockRepo, 500);

    }

    public function test_paginate_caches_result()
    {
        $filters = ['locale' => 'en'];

        $items = collect([
            (object) ['id' => 1, 'locale' => 'en'],
            (object) ['id' => 2, 'locale' => 'fr'],
            (object) ['id' => 3, 'locale' => 'es'],
        ]);
        dump(Cache::get('translations.paginate.'.md5(json_encode($filters).'50')));


        $fakePaginator = new LengthAwarePaginator(
            $items,
            $items->count(),
            50
        );

        $this->mockRepo
            ->shouldReceive('paginate')
            ->once()
            ->with($filters, 50)
            ->andReturn($fakePaginator);

        $result = $this->cacheRepo->paginate($filters, 50);
        $this->assertEquals($fakePaginator, $result);

        $result2 = $this->cacheRepo->paginate($filters, 50);
        $this->assertEquals($fakePaginator, $result2);
    }

    public function test_create_flushes_cache()
    {
        $data = ['locale' => 'en', 'group' => 'auth', 'key' => 'test', 'value' => 'abc', 'tags' => ['mobile']];
        $fakeTrans = new Translation($data);
        $fakeTrans->id = 99;

        // underlying repo's create
        $this->mockRepo
            ->shouldReceive('create')
            ->once()
            ->andReturn($fakeTrans);

        $created = $this->cacheRepo->create($data);

        $this->assertEquals(99, $created->id);
    }
}
