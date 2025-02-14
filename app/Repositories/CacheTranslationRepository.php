<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\TranslationRepositoryInterface;

class CacheTranslationRepository implements TranslationRepositoryInterface
{
    protected TranslationRepositoryInterface $repo;
    protected int $ttl; // Time-to-live in seconds

    public function __construct(TranslationRepositoryInterface $repo, int $ttl = 300)
    {
        $this->repo = $repo;
        $this->ttl = $ttl;
    }

    public function paginate(array $filters, int $perPage = 50): LengthAwarePaginator
    {
        // For multiple filters, build a unique cache key.
        // Example: "translations.paginate.(md5 of filters + perPage)"
        $key = 'translations.paginate.' . md5(json_encode($filters) . $perPage);

        return Cache::remember($key, $this->ttl, function () use ($filters, $perPage) {
            return $this->repo->paginate($filters, $perPage);
        });
    }

    public function findOrFail(int $id): Translation
    {
        $key = "translations.find.{$id}";

        return Cache::remember($key, $this->ttl, function () use ($id) {
            return $this->repo->findOrFail($id);
        });
    }

    public function create(array $data): Translation
    {
        $translation = $this->repo->create($data);
        $this->flushRelevantCache($translation);

        return $translation;
    }

    public function update(Translation $translation, array $data): Translation
    {
        $updated = $this->repo->update($translation, $data);
        $this->flushRelevantCache($updated);

        return $updated;
    }

    public function delete(Translation $translation): void
    {
        $this->repo->delete($translation);
        $this->flushRelevantCache($translation);
    }

    public function getExportData(?string $locale = null): array
    {
        $key = "translations.export." . ($locale ?? 'all');

        return Cache::remember($key, $this->ttl, function () use ($locale) {
            return $this->repo->getExportData($locale);
        });
    }

    /**
     * Example approach to selectively clear cache for the updated item,
     * export endpoints, etc.
     */
    protected function flushRelevantCache(Translation $translation): void
    {
        // Clear single-translation cache
        $key = "translations.find.{$translation->id}";
        Cache::forget($key);

        // Clear export cache for this locale
        $exportKey = "translations.export.{$translation->locale}";
        Cache::forget($exportKey);

        // Clear 'all' export
        Cache::forget("translations.export.all");

    }
}
