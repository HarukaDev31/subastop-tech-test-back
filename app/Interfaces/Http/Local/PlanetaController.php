<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Planeta\UseCases\CreatePlanetaUseCase;
use App\Application\Local\Planeta\UseCases\DeletePlanetaUseCase;
use App\Application\Local\Planeta\UseCases\GetPlanetaUseCase;
use App\Application\Local\Planeta\UseCases\ListPlanetasUseCase;
use App\Application\Local\Planeta\UseCases\UpdatePlanetaUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanetaRequest;
use App\Http\Requests\UpdatePlanetaRequest;
use App\Http\Resources\PlanetaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class PlanetaController extends Controller
{
    public function __construct(
        private readonly ListPlanetasUseCase $listPlanetas,
        private readonly GetPlanetaUseCase $getPlaneta,
        private readonly CreatePlanetaUseCase $createPlaneta,
        private readonly UpdatePlanetaUseCase $updatePlaneta,
        private readonly DeletePlanetaUseCase $deletePlaneta,
    ) {}

    /**
     * @OA\Get(
     *     path="/planetas",
     *     summary="Listar planetas locales con filtros",
     *     tags={"Planetas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="nombre", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="clima", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="terreno", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="recientes", in="query", required=false, @OA\Schema(type="integer", description="Últimos N días")),
     *     @OA\Response(response=200, description="Lista paginada de planetas con sus habitantes"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/planetas',
        summary: 'Listar planetas locales con filtros',
        tags: ['Planetas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de planetas'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['nombre', 'clima', 'terreno', 'recientes', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $planetas = $this->listPlanetas->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => PlanetaResource::collection($planetas),
            'meta'    => [
                'total'        => $planetas->total(),
                'per_page'     => $planetas->perPage(),
                'current_page' => $planetas->currentPage(),
                'last_page'    => $planetas->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/planetas/{id}",
     *     summary="Obtener un planeta local con sus habitantes",
     *     tags={"Planetas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Planeta con habitantes (eager loaded)"),
     *     @OA\Response(response=404, description="Planeta no encontrado")
     * )
     */
    #[OA\Get(
        path: '/planetas/{id}',
        summary: 'Obtener un planeta local con sus habitantes',
        tags: ['Planetas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Planeta encontrado'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $planeta): JsonResponse
    {
        $planeta = $this->getPlaneta->execute($planeta);

        return response()->json([
            'success' => true,
            'data'    => new PlanetaResource($planeta),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/planetas",
     *     summary="Crear un planeta con sus habitantes",
     *     tags={"Planetas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Tatooine"),
     *             @OA\Property(property="clima", type="string", example="árido"),
     *             @OA\Property(property="terreno", type="string", example="desierto"),
     *             @OA\Property(property="diametro", type="integer", example=10465),
     *             @OA\Property(property="poblacion", type="integer", example=200000),
     *             @OA\Property(property="habitantes", type="array", @OA\Items(
     *                 @OA\Property(property="nombre", type="string", example="Luke Skywalker"),
     *                 @OA\Property(property="genero", type="string", example="masculino"),
     *                 @OA\Property(property="altura_cm", type="integer", example=172)
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Planeta creado con sus habitantes"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Post(
        path: '/planetas',
        summary: 'Crear un planeta con sus habitantes',
        tags: ['Planetas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Planeta creado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StorePlanetaRequest $request): JsonResponse
    {
        $planeta = $this->createPlaneta->execute($request->validated());
        Cache::forget('select.planetas');

        return response()->json([
            'success' => true,
            'message' => 'Planeta creado exitosamente.',
            'data'    => new PlanetaResource($planeta),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/planetas/{id}",
     *     summary="Actualizar un planeta local",
     *     tags={"Planetas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Tatooine"),
     *             @OA\Property(property="clima", type="string", example="templado")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Planeta actualizado"),
     *     @OA\Response(response=404, description="Planeta no encontrado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/planetas/{id}',
        summary: 'Actualizar un planeta local',
        tags: ['Planetas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Planeta actualizado'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdatePlanetaRequest $request, int $planeta): JsonResponse
    {
        $planeta = $this->updatePlaneta->execute($planeta, $request->validated());
        Cache::forget('select.planetas');

        return response()->json([
            'success' => true,
            'message' => 'Planeta actualizado exitosamente.',
            'data'    => new PlanetaResource($planeta),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/planetas/{id}",
     *     summary="Eliminar un planeta local",
     *     tags={"Planetas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Planeta eliminado"),
     *     @OA\Response(response=404, description="Planeta no encontrado")
     * )
     */
    #[OA\Delete(
        path: '/planetas/{id}',
        summary: 'Eliminar un planeta local',
        tags: ['Planetas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Planeta eliminado'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $planeta): JsonResponse
    {
        $this->deletePlaneta->execute($planeta);
        Cache::forget('select.planetas');

        return response()->json([
            'success' => true,
            'message' => 'Planeta eliminado exitosamente.',
        ]);
    }
}

