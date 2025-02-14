<?php

use App\Repositories\Contracts\TranslationRepositoryInterface;
use App\Services\TranslationService;

test('test_cannot_access_translations_without_auth',function () {
    // Attempt to get translations endpoint without logging in or sending a token
    $response = $this->getJson('/api/translations');

    // Expect a 401 since the route has 'auth:sanctum'
    $response->assertStatus(401);
});

// TranslationServiceTest.php
//test('test_search_translations', function () {
//    $mockRepo = Mockery::mock(TranslationRepositoryInterface::class);
//    $mockRepo->shouldReceive('paginate')->with(['key' => 'welcome'])->andReturn(collect([/* dummy data */]));
//
//    $service = new TranslationService($mockRepo);
//    $result = $service->listTranslations(['search' => 'welcome']);
//    $this->assertCount(1, $result);
//});

