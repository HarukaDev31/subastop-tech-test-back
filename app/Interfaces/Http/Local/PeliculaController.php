<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Local;

use App\Application\Local\Pelicula\UseCases\CreatePeliculaUseCase;
use App\Application\Local\Pelicula\UseCases\DeletePeliculaUseCase;
use App\Application\Local\Pelicula\UseCases\GetPeliculaUseCase;
use App\Application\Local\Pelicula\UseCases\ListPeliculasUseCase;
use App\Application\Local\Pelicula\UseCases\UpdatePeliculaUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeliculaRequest;
use App\Http\Requests\UpdatePeliculaRequest;
use App\Http\Resources\PeliculaLocalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class PeliculaController extends Controller
{
    public function __construct(
        private readonly ListPeliculasUseCase $listPeliculas,
        private readonly GetPeliculaUseCase $getPelicula,
        private readonly CreatePeliculaUseCase $createPelicula,
        private readonly UpdatePeliculaUseCase $updatePelicula,
        private readonly DeletePeliculaUseCase $deletePelicula,
    ) {}

    /**
     * @OA\Get(
     *     path="/peliculas",
     *     summary="Listar películas locales",
     *     tags={"Películas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="titulo", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="director", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista paginada de películas con relaciones"),
     *     @OA\Response(response=401, description="API Key inválida")
     * )
     */
    #[OA\Get(
        path: '/peliculas',
        summary: 'Listar películas locales',
        tags: ['Películas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de películas'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['titulo', 'director', 'recientes', 'per_page']);
        $filters['page'] = max(1, (int) $request->query('page', $request->query('pagina', 1)));

        $peliculas = $this->listPeliculas->execute($filters);

        return response()->json([
            'success' => true,
            'data'    => PeliculaLocalResource::collection($peliculas),
            'meta'    => [
                'total'        => $peliculas->total(),
                'per_page'     => $peliculas->perPage(),
                'current_page' => $peliculas->currentPage(),
                'last_page'    => $peliculas->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/peliculas/{id}",
     *     summary="Obtener una película local con todas sus relaciones",
     *     tags={"Películas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Película con planetas, naves, vehículos y especies"),
     *     @OA\Response(response=404, description="Película no encontrada")
     * )
     */
    #[OA\Get(
        path: '/peliculas/{id}',
        summary: 'Obtener una película local con todas sus relaciones',
        tags: ['Películas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Película encontrada'),
            new OA\Response(response: 404, description: 'Película no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function show(int $pelicula): JsonResponse
    {
        $pelicula = $this->getPelicula->execute($pelicula);

        return response()->json([
            'success' => true,
            'data'    => new PeliculaLocalResource($pelicula),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/peliculas",
     *     summary="Crear una película local con sus relaciones",
     *     tags={"Películas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo","episodio_id","director"},
     *             @OA\Property(property="titulo", type="string", example="Una nueva esperanza"),
     *             @OA\Property(property="episodio_id", type="integer", example=4),
     *             @OA\Property(property="director", type="string", example="George Lucas"),
     *             @OA\Property(property="fecha_estreno", type="string", format="date", example="1977-05-25"),
     *             @OA\Property(property="planeta_ids", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="nave_ids", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="vehiculo_ids", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="especie_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Película creada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Post(
        path: '/peliculas',
        summary: 'Crear una película local con sus relaciones',
        tags: ['Películas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Película creada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function store(StorePeliculaRequest $request): JsonResponse
    {
        Cache::forget('select.peliculas');
        $pelicula = $this->createPelicula->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Película creada exitosamente.',
            'data'    => new PeliculaLocalResource($pelicula),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/peliculas/{id}",
     *     summary="Actualizar una película local",
     *     tags={"Películas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Película actualizada"),
     *     @OA\Response(response=404, description="Película no encontrada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    #[OA\Put(
        path: '/peliculas/{id}',
        summary: 'Actualizar una película local',
        tags: ['Películas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Película actualizada'),
            new OA\Response(response: 404, description: 'Película no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function update(UpdatePeliculaRequest $request, int $pelicula): JsonResponse
    {
        Cache::forget('select.peliculas');
        $pelicula = $this->updatePelicula->execute($pelicula, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Película actualizada exitosamente.',
            'data'    => new PeliculaLocalResource($pelicula),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/peliculas/{id}",
     *     summary="Eliminar una película local",
     *     tags={"Películas Locales"},
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Película eliminada"),
     *     @OA\Response(response=404, description="Película no encontrada")
     * )
     */
    #[OA\Delete(
        path: '/peliculas/{id}',
        summary: 'Eliminar una película local',
        tags: ['Películas Locales'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Película eliminada'),
            new OA\Response(response: 404, description: 'Película no encontrada'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ],
    )]
    public function destroy(int $pelicula): JsonResponse
    {
        Cache::forget('select.peliculas');
        $this->deletePelicula->execute($pelicula);

        return response()->json([
            'success' => true,
            'message' => 'Película eliminada exitosamente.',
        ]);
    }
}

