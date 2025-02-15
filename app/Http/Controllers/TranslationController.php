<?php

namespace App\Http\Controllers;

use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

//#[OA\Info(title: "Translations API", version: "1.0")]

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Translations API",
 *      description="API Documentation for Translations",
 *      @OA\Contact(
 *          email="aqibullah3312@gmail.com"
 *      ),
 * )
 */

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "sanctum"
)]
class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    #[OA\Get(
        path: "/api/translations",
        summary: "Get Translations",
        description: "Get translations",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        parameters:[
            new OA\Parameter(
                name: "search",
                in: "query",
                required: false,
                description: "Filter translations by search",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "key",
                in: "query",
                required: false,
                description: "Filter translations with key",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "locale",
                in: "query",
                required: false,
                description: "Filter translations by locale",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "tag",
                in: "query",
                required: false,
                description: "Filter translations by tag",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Translations",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "locale",type:"string",default: "en"),
                        new OA\Property(property: "group",type:"string",example:"messages"),
                        new OA\Property(property: "key",type:"string",example:"hello_world"),
                        new OA\Property(property: "value",type:"string",example:"Hello World"),
                        new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Translations not found"
            ),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['locale', 'key', 'tag', 'search']);
        $translations = $this->translationService->listTranslations($filters,$request->perPage ?? 50);

        return response()->json(TranslationResource::collection($translations),201);
    }

    #[OA\Post(
        path: "/api/translations",
        summary: "Add Translation",
        description: "Add a new translation",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["locale","group","key","value","tags"],
                properties: [
                    new OA\Property(property: "locale",type:"string",default: "en"),
                    new OA\Property(property: "group",type:"string",example:"messages"),
                    new OA\Property(property: "key",type:"string",example:"hello_world"),
                    new OA\Property(property: "value",type:"string",example:"Hello World"),
                    new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful login",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "locale",type:"string",default: "en"),
                        new OA\Property(property: "group",type:"string",example:"messages"),
                        new OA\Property(property: "key",type:"string",example:"hello_world"),
                        new OA\Property(property: "value",type:"string",example:"Hello World"),
                        new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request"
            ),
        ],
    )]
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

    #[OA\Get(
        path: "/api/translations/{translation_id}",
        summary: "Add Translation",
        description: "Add a new translation",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        parameters: [
            new OA\Parameter(
                name: "translation_id",
                in: "path",
                required: true,
                description: "Translation ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Translation Details",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "locale",type:"string",default: "en"),
                        new OA\Property(property: "group",type:"string",example:"messages"),
                        new OA\Property(property: "key",type:"string",example:"hello_world"),
                        new OA\Property(property: "value",type:"string",example:"Hello World"),
                        new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request"
            ),
            new OA\Response(
                response: 404,
                description: "Translation not found"
            ),
        ],
    )]
    public function show($translation_id): JsonResponse
    {
        $translation = $this->translationService->showTranslation($translation_id);
        return response()->json($translation);
    }

    #[OA\Put(
        path: "/api/translations/{translation_id}",
        summary: "Add Translation",
        description: "Update translation",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        parameters: [
            new OA\Parameter(
                name: "translation_id",
                in: "path",
                required: true,
                description: "Translation ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["locale","group","key","value","tags"],
                properties: [
                    new OA\Property(property: "locale",type:"string",default: "en"),
                    new OA\Property(property: "group",type:"string",example:"messages"),
                    new OA\Property(property: "key",type:"string",example:"hello_world"),
                    new OA\Property(property: "value",type:"string",example:"Hello World"),
                    new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Translation Details",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "locale",type:"string",default: "en"),
                        new OA\Property(property: "group",type:"string",example:"messages"),
                        new OA\Property(property: "key",type:"string",example:"hello_world"),
                        new OA\Property(property: "value",type:"string",example:"Hello World"),
                        new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request"
            ),
            new OA\Response(
                response: 404,
                description: "Translation not found"
            ),
        ],
    )]
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


    #[OA\Delete(
        path: "/api/translations/{translation_id}",
        summary: "Delete Translation",
        description: "Delete a translation",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        parameters: [
            new OA\Parameter(
                name: "translation_id",
                in: "path",
                required: true,
                description: "Translation ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Translation Deleted",
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 400,
                description: "Bad request"
            ),
            new OA\Response(
                response: 404,
                description: "Translation not found"
            ),
        ],
    )]
    public function destroy($translation_id): JsonResponse
    {
        try {
            $this->translationService->deleteTranslation($translation_id);
            return response()->json(null, 204);
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    #[OA\Get(
        path: "/api/translations/export",
        summary: "Export Translations",
        description: "Add a new translation",
        security: [["bearerAuth" => []]],
        tags: ["Translation"],
        parameters: [
            new OA\Parameter(
                name: "locale",
                in: "query",
                required: false,
                description: "Locale",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Translations",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "locale",type:"string",default: "en"),
                        new OA\Property(property: "group",type:"string",example:"messages"),
                        new OA\Property(property: "key",type:"string",example:"hello_world"),
                        new OA\Property(property: "value",type:"string",example:"Hello World"),
                        new OA\Property(property: "tags",type:"array",example:["mobile","desktop"],items: new OA\Items(type: "string")),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request"
            ),
            new OA\Response(
                response: 404,
                description: "No translations found"
            ),
        ],
    )]
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
