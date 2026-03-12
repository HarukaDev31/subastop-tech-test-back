<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'SWAPI Laravel API',
    description: 'API REST que integra la Star Wars API (SWAPI) con caché, reintentos y DTOs. Gestiona planetas y habitantes localmente con relaciones Eloquent.',
    contact: new OA\Contact(email: 'admin@example.com')
)]
#[OA\Server(url: '/api', description: 'API v1 – local')]
#[OA\SecurityScheme(
    securityScheme: 'ApiKeyAuth',
    type: 'apiKey',
    name: 'X-API-KEY',
    in: 'header',
    description: 'API Key requerida. Configura API_KEY en tu .env y envíala como header X-API-KEY.'
)]
#[OA\Tag(name: 'SWAPI – Personajes', description: 'Consulta personajes de Star Wars')]
#[OA\Tag(name: 'SWAPI – Planetas', description: 'Consulta planetas de Star Wars')]
#[OA\Tag(name: 'SWAPI – Naves', description: 'Consulta naves estelares de Star Wars')]
#[OA\Tag(name: 'SWAPI – Películas', description: 'Consulta películas de Star Wars')]
#[OA\Tag(name: 'SWAPI – Especies', description: 'Consulta especies de Star Wars')]
#[OA\Tag(name: 'SWAPI – Vehículos', description: 'Consulta vehículos de Star Wars')]
#[OA\Tag(name: 'Planetas Locales', description: 'CRUD de planetas persistidos en base de datos')]
#[OA\Tag(name: 'Especies Locales', description: 'CRUD de especies persistidas en base de datos')]
#[OA\Tag(name: 'Naves Locales', description: 'CRUD de naves persistidas en base de datos')]
#[OA\Tag(name: 'Películas Locales', description: 'CRUD de películas persistidas en base de datos')]
#[OA\Tag(name: 'Vehículos Locales', description: 'CRUD de vehículos persistidos en base de datos')]
#[OA\Tag(name: 'Habitantes', description: 'Gestión de habitantes asociados a planetas')]
abstract class Controller {}
