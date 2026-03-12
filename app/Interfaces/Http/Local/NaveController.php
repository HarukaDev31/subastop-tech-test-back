<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Nave\UseCases\CreateNaveUseCase;
use App\Application\Local\Nave\UseCases\DeleteNaveUseCase;
use App\Application\Local\Nave\UseCases\GetNaveUseCase;
use App\Application\Local\Nave\UseCases\ListNavesUseCase;
use App\Application\Local\Nave\UseCases\UpdateNaveUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNaveRequest;
use App\Http\Requests\UpdateNaveRequest;
use App\Http\Resources\NaveLocalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class NaveController extends Controller
{
    public function __construct(
        private readonly ListNavesUseCase $listNaves,
        private readonly GetNaveUseCase $getNave,
        private readonly CreateNaveUseCase $createNave,
        private readonly UpdateNaveUseCase $updateNave,
        private readonly DeleteNaveUseCase $deleteNave,
    ) {}

    /**
     * @OA\Get(
     *     path="/naves",
     *     summary="Listar naves locales",
     *     tags={"Naves Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="nombre", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="clase", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista paginada de naves con relaciones"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/naves',
        summary: 'Listar naves locales',
        tags: ['Naves Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de naves'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['nombre', 'clase', 'recientes', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $naves = $this->listNaves->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => NaveLocalResource::collection($naves),
            'meta'    => [
                'total'        => $naves->total(),
                'per_page'     => $naves->perPage(),
                'current_page' => $naves->currentPage(),
                'last_page'    => $naves->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/naves/{id}",
     *     summary="Obtener una nave local con sus pilotos y películas",
     *     tags={"Naves Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Nave con pilotos y películas"),
     *     @OA\Response(response=404, description="Nave no encontrada")
     * )
     */
    #[OA\Get(
        path: '/naves/{id}',
        summary: 'Obtener una nave local con sus pilotos y películas',
        tags: ['Naves Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Nave encontrada'),
            new OA\Response(response: 404, description: 'Nave no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $nave): JsonResponse
    {
        $nave = $this->getNave->execute($nave);

        return response()->json([
            'success' => true,
            'data'    => new NaveLocalResource($nave),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/naves",
     *     summary="Crear una nave local",
     *     tags={"Naves Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Millennium Falcon"),
     *             @OA\Property(property="modelo", type="string", example="YT-1300f"),
     *             @OA\Property(property="clase_nave", type="string", example="Carguero ligero"),
     *             @OA\Property(property="longitud", type="number", example=34.37),
     *             @OA\Property(property="piloto_ids", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="pelicula_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Nave creada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Post(
        path: '/naves',
        summary: 'Crear una nave local',
        tags: ['Naves Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Nave creada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StoreNaveRequest $request): JsonResponse
    {
        Cache::forget('select.naves');
        $nave = $this->createNave->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Nave creada exitosamente.',
            'data'    => new NaveLocalResource($nave),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/naves/{id}",
     *     summary="Actualizar una nave local",
     *     tags={"Naves Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Nave actualizada"),
     *     @OA\Response(response=404, description="Nave no encontrada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/naves/{id}',
        summary: 'Actualizar una nave local',
        tags: ['Naves Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Nave actualizada'),
            new OA\Response(response: 404, description: 'Nave no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdateNaveRequest $request, int $nave): JsonResponse
    {
        Cache::forget('select.naves');
        $nave = $this->updateNave->execute($nave, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Nave actualizada exitosamente.',
            'data'    => new NaveLocalResource($nave),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/naves/{id}",
     *     summary="Eliminar una nave local",
     *     tags={"Naves Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Nave eliminada"),
     *     @OA\Response(response=404, description="Nave no encontrada")
     * )
     */
    #[OA\Delete(
        path: '/naves/{id}',
        summary: 'Eliminar una nave local',
        tags: ['Naves Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Nave eliminada'),
            new OA\Response(response: 404, description: 'Nave no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $nave): JsonResponse
    {
        Cache::forget('select.naves');
        $this->deleteNave->execute($nave);

        return response()->json([
            'success' => true,
            'message' => 'Nave eliminada exitosamente.',
        ]);
    }
}

