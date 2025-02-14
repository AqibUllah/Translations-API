<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Repositories\Contracts\TranslationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTranslationRepository implements Contracts\TranslationRepositoryInterface
{

    public function paginate(array $filters, int $perPage = 50): LengthAwarePaginator
    {
        $query = Translation::query();

        // Filter by locale
        if (!empty($filters['locale'])) {
            $query->where('locale', $filters['locale']);
        }

        // Filter by translation_key
        if (!empty($filters['key'])) {
            $query->where('key', 'LIKE', '%' . $filters['key'] . '%');
        }

        // Filter by tag
        if (!empty($filters['tag'])) {
            $query->whereJsonContains('tags', $filters['tag']);
        }

        // Search in translation_value
        if (!empty($filters['search'])) {
            $query->where('value', 'LIKE', '%' . $filters['search'] . '%');
        }

        return $query->select([
            'id', 'locale', 'group', 'key', 'value', 'tags'
        ])->paginate($perPage);
    }

    public function findOrFail(int $id): Translation
    {
        return Translation::findOrFail($id);
    }

    public function create(array $data): Translation
    {
        return Translation::create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        return $translation;
    }

    public function delete(Translation $translation): void
    {
        $translation->delete();
    }

    public function getExportData(?string $locale = null): array
    {
        $query = Translation::query();

        if ($locale) {
            $query->where('locale', $locale);
        }

        return $query->get()
            ->groupBy('locale')
            ->map(fn($items) => $items->pluck('value', 'key'))
            ->toArray();
    }
}
