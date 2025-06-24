<?php

use Http\Admin\Product\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::prefix('product')
    ->group(function () {
        Route::apiResource('brand', BrandController::class);
    });
