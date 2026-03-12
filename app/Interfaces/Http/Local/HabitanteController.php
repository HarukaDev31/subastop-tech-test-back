<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Habitante\UseCases\CreateHabitanteUseCase;
use App\Application\Local\Habitante\UseCases\DeleteHabitanteUseCase;
use App\Application\Local\Habitante\UseCases\GetHabitanteUseCase;
use App\Application\Local\Habitante\UseCases\ListHabitantesUseCase;
use App\Application\Local\Habitante\UseCases\UpdateHabitanteUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHabitanteRequest;
use App\Http\Requests\UpdateHabitanteRequest;
use App\Http\Resources\HabitanteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class HabitanteController extends Controller
{
    public function __construct(
        private readonly ListHabitantesUseCase $listHabitantes,
        private readonly GetHabitanteUseCase $getHabitante,
        private readonly CreateHabitanteUseCase $createHabitante,
        private readonly UpdateHabitanteUseCase $updateHabitante,
        private readonly DeleteHabitanteUseCase $deleteHabitante,
    ) {}

    /**
     * @OA\Get(
     *     path="/habitantes",
     *     summary="Listar habitantes locales",
     *     tags={"Habitantes"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="nombre", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="genero", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista paginada de habitantes con planeta, especie y naves"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/habitantes',
        summary: 'Listar habitantes locales',
        tags: ['Habitantes'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de habitantes'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['nombre', 'genero', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $habitantes = $this->listHabitantes->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => HabitanteResource::collection($habitantes),
            'meta'    => [
                'total'        => $habitantes->total(),
                'per_page'     => $habitantes->perPage(),
                'current_page' => $habitantes->currentPage(),
                'last_page'    => $habitantes->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/habitantes",
     *     summary="Crear un habitante local",
     *     tags={"Habitantes"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","planeta_id"},
     *             @OA\Property(property="nombre", type="string", example="Luke Skywalker"),
     *             @OA\Property(property="planeta_id", type="integer", example=1),
     *             @OA\Property(property="especie_id", type="integer", example=1),
     *             @OA\Property(property="genero", type="string", example="masculino")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Habitante creado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Post(
        path: '/habitantes',
        summary: 'Crear un habitante local',
        tags: ['Habitantes'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Habitante creado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StoreHabitanteRequest $request): JsonResponse
    {
        Cache::forget('select.habitantes');
        $habitante = $this->createHabitante->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Habitante creado exitosamente.',
            'data'    => new HabitanteResource($habitante),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/habitantes/{id}",
     *     summary="Obtener un habitante local",
     *     tags={"Habitantes"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Habitante con planeta, especie y naves"),
     *     @OA\Response(response=404, description="Habitante no encontrado")
     * )
     */
    #[OA\Get(
        path: '/habitantes/{id}',
        summary: 'Obtener un habitante local',
        tags: ['Habitantes'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Habitante encontrado'),
            new OA\Response(response: 404, description: 'Habitante no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $habitante): JsonResponse
    {
        $habitante = $this->getHabitante->execute($habitante);

        return response()->json([
            'success' => true,
            'data'    => new HabitanteResource($habitante),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/habitantes/{id}",
     *     summary="Actualizar un habitante local",
     *     tags={"Habitantes"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Habitante actualizado"),
     *     @OA\Response(response=404, description="Habitante no encontrado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/habitantes/{id}',
        summary: 'Actualizar un habitante local',
        tags: ['Habitantes'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Habitante actualizado'),
            new OA\Response(response: 404, description: 'Habitante no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdateHabitanteRequest $request, int $habitante): JsonResponse
    {
        Cache::forget('select.habitantes');
        $habitante = $this->updateHabitante->execute($habitante, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Habitante actualizado exitosamente.',
            'data'    => new HabitanteResource($habitante),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/habitantes/{id}",
     *     summary="Eliminar un habitante local",
     *     tags={"Habitantes"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Habitante eliminado"),
     *     @OA\Response(response=404, description="Habitante no encontrado")
     * )
     */
    #[OA\Delete(
        path: '/habitantes/{id}',
        summary: 'Eliminar un habitante local',
        tags: ['Habitantes'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Habitante eliminado'),
            new OA\Response(response: 404, description: 'Habitante no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $habitante): JsonResponse
    {
        Cache::forget('select.habitantes');
        $this->deleteHabitante->execute($habitante);

        return response()->json([
            'success' => true,
            'message' => 'Habitante eliminado exitosamente.',
        ]);
    }
}

