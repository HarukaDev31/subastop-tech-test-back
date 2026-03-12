<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Vehiculo\UseCases\CreateVehiculoUseCase;
use App\Application\Local\Vehiculo\UseCases\DeleteVehiculoUseCase;
use App\Application\Local\Vehiculo\UseCases\GetVehiculoUseCase;
use App\Application\Local\Vehiculo\UseCases\ListVehiculosUseCase;
use App\Application\Local\Vehiculo\UseCases\UpdateVehiculoUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Http\Resources\VehiculoLocalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class VehiculoController extends Controller
{
    public function __construct(
        private readonly ListVehiculosUseCase $listVehiculos,
        private readonly GetVehiculoUseCase $getVehiculo,
        private readonly CreateVehiculoUseCase $createVehiculo,
        private readonly UpdateVehiculoUseCase $updateVehiculo,
        private readonly DeleteVehiculoUseCase $deleteVehiculo,
    ) {}

    /**
     * @OA\Get(
     *     path="/vehiculos",
     *     summary="Listar vehículos locales",
     *     tags={"Vehículos Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="nombre", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="clase", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista paginada de vehículos con relaciones"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/vehiculos',
        summary: 'Listar vehículos locales',
        tags: ['Vehículos Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de vehículos'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters   = $request->only(['nombre', 'clase', 'recientes', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $vehiculos = $this->listVehiculos->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => VehiculoLocalResource::collection($vehiculos),
            'meta'    => [
                'total'        => $vehiculos->total(),
                'per_page'     => $vehiculos->perPage(),
                'current_page' => $vehiculos->currentPage(),
                'last_page'    => $vehiculos->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/vehiculos/{id}",
     *     summary="Obtener un vehículo local con sus películas",
     *     tags={"Vehículos Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Vehículo con películas asociadas"),
     *     @OA\Response(response=404, description="Vehículo no encontrado")
     * )
     */
    #[OA\Get(
        path: '/vehiculos/{id}',
        summary: 'Obtener un vehículo local con sus películas',
        tags: ['Vehículos Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Vehículo encontrado'),
            new OA\Response(response: 404, description: 'Vehículo no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $vehiculo): JsonResponse
    {
        $vehiculo = $this->getVehiculo->execute($vehiculo);

        return response()->json([
            'success' => true,
            'data'    => new VehiculoLocalResource($vehiculo),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/vehiculos",
     *     summary="Crear un vehículo local",
     *     tags={"Vehículos Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="AT-AT"),
     *             @OA\Property(property="modelo", type="string", example="All Terrain Armored Transport"),
     *             @OA\Property(property="clase_vehiculo", type="string", example="Transporte de asalto"),
     *             @OA\Property(property="longitud", type="number", example=20.6),
     *             @OA\Property(property="pelicula_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Vehículo creado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Post(
        path: '/vehiculos',
        summary: 'Crear un vehículo local',
        tags: ['Vehículos Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Vehículo creado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StoreVehiculoRequest $request): JsonResponse
    {
        Cache::forget('select.vehiculos');
        $vehiculo = $this->createVehiculo->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Vehículo creado exitosamente.',
            'data'    => new VehiculoLocalResource($vehiculo),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/vehiculos/{id}",
     *     summary="Actualizar un vehículo local",
     *     tags={"Vehículos Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Vehículo actualizado"),
     *     @OA\Response(response=404, description="Vehículo no encontrado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/vehiculos/{id}',
        summary: 'Actualizar un vehículo local',
        tags: ['Vehículos Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Vehículo actualizado'),
            new OA\Response(response: 404, description: 'Vehículo no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdateVehiculoRequest $request, int $vehiculo): JsonResponse
    {
        Cache::forget('select.vehiculos');
        $vehiculo = $this->updateVehiculo->execute($vehiculo, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Vehículo actualizado exitosamente.',
            'data'    => new VehiculoLocalResource($vehiculo),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/vehiculos/{id}",
     *     summary="Eliminar un vehículo local",
     *     tags={"Vehículos Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Vehículo eliminado"),
     *     @OA\Response(response=404, description="Vehículo no encontrado")
     * )
     */
    #[OA\Delete(
        path: '/vehiculos/{id}',
        summary: 'Eliminar un vehículo local',
        tags: ['Vehículos Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Vehículo eliminado'),
            new OA\Response(response: 404, description: 'Vehículo no encontrado'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $vehiculo): JsonResponse
    {
        Cache::forget('select.vehiculos');
        $this->deleteVehiculo->execute($vehiculo);

        return response()->json([
            'success' => true,
            'message' => 'Vehículo eliminado exitosamente.',
        ]);
    }
}

