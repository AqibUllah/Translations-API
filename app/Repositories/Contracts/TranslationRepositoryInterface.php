<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Translation;

interface TranslationRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 50): LengthAwarePaginator;

    public function findOrFail(int $id): Translation;

    public function create(array $data): Translation;

    public function update(Translation $translation, array $data): Translation;

    public function delete(Translation $translation): void;

    public function getExportData(?string $locale = null): array;
}
