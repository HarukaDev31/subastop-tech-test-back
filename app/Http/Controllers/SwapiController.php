<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\EspecieResource;
use App\Http\Resources\NaveResource;
use App\Http\Resources\PeliculaResource;
use App\Http\Resources\PersonajeResource;
use App\Http\Resources\PlanetaSwapiResource;
use App\Http\Resources\VehiculoResource;
use App\Services\SwapiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SwapiController extends Controller
{
    public function __construct(
        private readonly SwapiService $swapiService,
    ) {}

    // -------------------------------------------------------------------------
    // Personajes
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/personajes',
        summary: 'Listar personajes de Star Wars (paginado)',
        tags: ['SWAPI – Personajes'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'pagina', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de personajes en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
            new OA\Response(response: 502, description: 'Error en SWAPI externa'),
        ]
    )]
    public function personajes(Request $request): JsonResponse
    {
        $pagina = (int) $request->query('pagina', 1);
        $data   = $this->swapiService->listarPersonajes($pagina);

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['personajes'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/personajes/{id}',
        summary: 'Obtener un personaje por ID',
        tags: ['SWAPI – Personajes'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Personaje mapeado al español con DTO'),
            new OA\Response(response: 401, description: 'API Key inválida'),
            new OA\Response(response: 404, description: 'Personaje no encontrado en SWAPI'),
        ]
    )]
    public function personaje(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerPersonaje($id);

        return response()->json([
            'success' => true,
            'datos'   => new PersonajeResource($dto),
        ]);
    }

    // -------------------------------------------------------------------------
    // Planetas (SWAPI)
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/planetas',
        summary: 'Listar planetas de Star Wars (paginado)',
        tags: ['SWAPI – Planetas'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'pagina', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de planetas en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ]
    )]
    public function planetasSwapi(Request $request): JsonResponse
    {
        $pagina = (int) $request->query('pagina', 1);
        $data   = $this->swapiService->listarPlanetas($pagina);

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['planetas'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/planetas/{id}',
        summary: 'Obtener un planeta de SWAPI por ID',
        tags: ['SWAPI – Planetas'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Planeta mapeado al español'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
        ]
    )]
    public function planetaSwapi(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerPlaneta($id);

        return response()->json([
            'success' => true,
            'datos'   => new PlanetaSwapiResource($dto),
        ]);
    }

    // -------------------------------------------------------------------------
    // Naves
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/naves',
        summary: 'Listar naves estelares de Star Wars (paginado)',
        tags: ['SWAPI – Naves'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'pagina', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de naves en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ]
    )]
    public function naves(Request $request): JsonResponse
    {
        $pagina = (int) $request->query('pagina', 1);
        $data   = $this->swapiService->listarNaves($pagina);

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['naves'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/naves/{id}',
        summary: 'Obtener una nave estelar por ID',
        tags: ['SWAPI – Naves'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Nave mapeada al español'),
            new OA\Response(response: 404, description: 'Nave no encontrada'),
        ]
    )]
    public function nave(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerNave($id);

        return response()->json([
            'success' => true,
            'datos'   => new NaveResource($dto),
        ]);
    }

    // -------------------------------------------------------------------------
    // Películas
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/peliculas',
        summary: 'Listar todas las películas de Star Wars',
        tags: ['SWAPI – Películas'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista de películas en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ]
    )]
    public function peliculas(): JsonResponse
    {
        $data = $this->swapiService->listarPeliculas();

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['peliculas'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/peliculas/{id}',
        summary: 'Obtener una película por ID',
        tags: ['SWAPI – Películas'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Película mapeada al español con DTO'),
            new OA\Response(response: 404, description: 'Película no encontrada'),
        ]
    )]
    public function pelicula(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerPelicula($id);

        return response()->json([
            'success' => true,
            'datos'   => new PeliculaResource($dto),
        ]);
    }

    // -------------------------------------------------------------------------
    // Especies
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/especies',
        summary: 'Listar especies de Star Wars (paginado)',
        tags: ['SWAPI – Especies'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'pagina', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de especies en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ]
    )]
    public function especies(Request $request): JsonResponse
    {
        $pagina = (int) $request->query('pagina', 1);
        $data   = $this->swapiService->listarEspecies($pagina);

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['especies'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/especies/{id}',
        summary: 'Obtener una especie por ID',
        tags: ['SWAPI – Especies'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Especie mapeada al español con DTO'),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
        ]
    )]
    public function especie(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerEspecie($id);

        return response()->json([
            'success' => true,
            'datos'   => new EspecieResource($dto),
        ]);
    }

    // -------------------------------------------------------------------------
    // Vehículos
    // -------------------------------------------------------------------------

    #[OA\Get(
        path: '/swapi/vehiculos',
        summary: 'Listar vehículos de Star Wars (paginado)',
        tags: ['SWAPI – Vehículos'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'pagina', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de vehículos en español'),
            new OA\Response(response: 401, description: 'API Key inválida'),
        ]
    )]
    public function vehiculos(Request $request): JsonResponse
    {
        $pagina = (int) $request->query('pagina', 1);
        $data   = $this->swapiService->listarVehiculos($pagina);

        return response()->json([
            'success'   => true,
            'total'     => $data['total'],
            'siguiente' => $data['siguiente'],
            'anterior'  => $data['anterior'],
            'datos'     => $data['vehiculos'],
        ]);
    }

    #[OA\Get(
        path: '/swapi/vehiculos/{id}',
        summary: 'Obtener un vehículo por ID',
        tags: ['SWAPI – Vehículos'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vehículo mapeado al español con DTO'),
            new OA\Response(response: 404, description: 'Vehículo no encontrado'),
        ]
    )]
    public function vehiculo(int $id): JsonResponse
    {
        $dto = $this->swapiService->obtenerVehiculo($id);

        return response()->json([
            'success' => true,
            'datos'   => new VehiculoResource($dto),
        ]);
    }
}
