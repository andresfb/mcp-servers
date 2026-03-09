<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function (): void {
        Route::middleware('auth:api')->group(function (): void {
            Route::post('/logout', [LoginController::class, 'destroy'])
                ->name('logout');
        });
    });

});
