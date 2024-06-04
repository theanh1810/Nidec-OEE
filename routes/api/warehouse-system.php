<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\WarehouseSystem\ExportMaterialsController;

Route::prefix('warehouse-system')->group(function () {
    Route::prefix('export')->group(function () {
        Route::get('/', [ExportMaterialsController::class, 'index']);
    });
});
