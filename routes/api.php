<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function (): void {
    require base_path('routes/api_swapi.php');
    require base_path('routes/api_local.php');
});
