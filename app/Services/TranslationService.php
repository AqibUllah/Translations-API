<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\Contracts\TranslationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TranslationService
{
    protected TranslationRepositoryInterface $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function listTranslations(array $filters, int $perPage = 50): LengthAwarePaginator
    {
        return $this->translationRepository->paginate($filters, $perPage);
    }

    public function createTranslation(array $data): Translation
    {
        return $this->translationRepository->create($data);
    }

    public function showTranslation(int $id): Translation
    {
        return $this->translationRepository->findOrFail($id);
    }

    public function updateTranslation(int $id, array $data): Translation
    {
        $translation = $this->translationRepository->findOrFail($id);
        return $this->translationRepository->update($translation, $data);
    }

    public function deleteTranslation(int $id): void
    {
        $translation = $this->translationRepository->findOrFail($id);
        $this->translationRepository->delete($translation);
    }

    public function exportTranslations(?string $locale = null): array
    {
        return $this->translationRepository->getExportData($locale);
    }
}
