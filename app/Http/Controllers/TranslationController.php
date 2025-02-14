<?php

namespace App\Http\Controllers;

use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['locale', 'key', 'tag', 'search']);
        $translations = $this->translationService->listTranslations($filters,$request->perPage ?? 50);

        return response()->json(TranslationResource::collection($translations));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale'    => 'required|string|max:5',
            'group'     => 'required|string',
            'key'       => 'required|string',
            'value'     => 'required|string',
            'tags'      => 'array',
        ]);

        $translation = $this->translationService->createTranslation($data);

        return response()->json($translation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($translation_id): JsonResponse
    {
        $translation = $this->translationService->showTranslation($translation_id);
        return response()->json($translation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $translation_id): JsonResponse
    {
        $data = $request->validate([
            'locale'            => 'sometimes|required|string|max:5',
            'translation_key'   => 'sometimes|required|string',
            'translation_value' => 'sometimes|required|string',
            'tags'              => 'array',
        ]);

        $translation = $this->translationService->updateTranslation($translation_id, $data);

        return response()->json($translation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($translation_id): JsonResponse
    {
        $this->translationService->deleteTranslation($translation_id);
        return response()->json(null, 204);
    }

    public function export(Request $request): JsonResponse
    {
        $locale = $request->input('locale');
        $grouped = $this->translationService->exportTranslations($locale);

        // If a single locale is requested, flatten
        if ($locale && isset($grouped[$locale])) {
            return response()->json($grouped[$locale]);
        }

        return response()->json($grouped);
    }
}
