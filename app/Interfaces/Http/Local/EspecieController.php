<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Especie\UseCases\CreateEspecieUseCase;
use App\Application\Local\Especie\UseCases\DeleteEspecieUseCase;
use App\Application\Local\Especie\UseCases\GetEspecieUseCase;
use App\Application\Local\Especie\UseCases\ListEspeciesUseCase;
use App\Application\Local\Especie\UseCases\UpdateEspecieUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEspecieRequest;
use App\Http\Requests\UpdateEspecieRequest;
use App\Http\Resources\EspecieLocalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class EspecieController extends Controller
{
    public function __construct(
        private readonly ListEspeciesUseCase $listEspecies,
        private readonly GetEspecieUseCase $getEspecie,
        private readonly CreateEspecieUseCase $createEspecie,
        private readonly UpdateEspecieUseCase $updateEspecie,
        private readonly DeleteEspecieUseCase $deleteEspecie,
    ) {}

    /**
     * @OA\Get(
     *     path="/especies",
     *     summary="Listar especies locales",
     *     tags={"Especies Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="nombre", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="clasificacion", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista paginada de especies con relaciones"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/especies',
        summary: 'Listar especies locales',
        tags: ['Especies Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de especies'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters  = $request->only(['nombre', 'clasificacion', 'recientes', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $especies = $this->listEspecies->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => EspecieLocalResource::collection($especies),
            'meta'    => [
                'total'        => $especies->total(),
                'per_page'     => $especies->perPage(),
                'current_page' => $especies->currentPage(),
                'last_page'    => $especies->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/especies/{id}",
     *     summary="Obtener una especie local con sus habitantes y películas",
     *     tags={"Especies Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Especie con planeta natal, habitantes y películas"),
     *     @OA\Response(response=404, description="Especie no encontrada")
     * )
     */
    #[OA\Get(
        path: '/especies/{id}',
        summary: 'Obtener una especie local con sus habitantes y películas',
        tags: ['Especies Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Especie encontrada'),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $especie): JsonResponse
    {
        $especie = $this->getEspecie->execute($especie);

        return response()->json([
            'success' => true,
            'data'    => new EspecieLocalResource($especie),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/especies",
     *     summary="Crear una especie local",
     *     tags={"Especies Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Wookiee"),
     *             @OA\Property(property="clasificacion", type="string", example="Mamífero"),
     *             @OA\Property(property="idioma", type="string", example="Shyriiwook"),
     *             @OA\Property(property="planeta_natal_id", type="integer", example=1),
     *             @OA\Property(property="pelicula_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Especie creada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Post(
        path: '/especies',
        summary: 'Crear una especie local',
        tags: ['Especies Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Especie creada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StoreEspecieRequest $request): JsonResponse
    {
        Cache::forget('select.especies');
        $especie = $this->createEspecie->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Especie creada exitosamente.',
            'data'    => new EspecieLocalResource($especie),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/especies/{id}",
     *     summary="Actualizar una especie local",
     *     tags={"Especies Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Especie actualizada"),
     *     @OA\Response(response=404, description="Especie no encontrada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/especies/{id}',
        summary: 'Actualizar una especie local',
        tags: ['Especies Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Especie actualizada'),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdateEspecieRequest $request, int $especie): JsonResponse
    {
        Cache::forget('select.especies');
        $especie = $this->updateEspecie->execute($especie, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Especie actualizada exitosamente.',
            'data'    => new EspecieLocalResource($especie),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/especies/{id}",
     *     summary="Eliminar una especie local",
     *     tags={"Especies Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Especie eliminada"),
     *     @OA\Response(response=404, description="Especie no encontrada")
     * )
     */
    #[OA\Delete(
        path: '/especies/{id}',
        summary: 'Eliminar una especie local',
        tags: ['Especies Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Especie eliminada'),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $especie): JsonResponse
    {
        Cache::forget('select.especies');
        $this->deleteEspecie->execute($especie);

        return response()->json([
            'success' => true,
            'message' => 'Especie eliminada exitosamente.',
        ]);
    }
}

