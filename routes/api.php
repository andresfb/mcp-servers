<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/me', static function (Request $request) {
            return $request->user();
        })
            ->name('me');
    });
