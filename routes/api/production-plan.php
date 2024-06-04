<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductionPlan\ProductionPlanController;
use App\Http\Controllers\Api\ProductionPlan\ProductionPlanDetailController;

Route::prefix('productionplan')->group(function() {
    Route::prefix('command')->group(function() {
        Route::get('/', [ProductionPlanController::class, 'index']);
    });
    Route::prefix('detail')->group(function() {
        Route::get('/', [ProductionPlanDetailController::class, 'index']);
        Route::get('/history', [ProductionPlanDetailController::class, 'history']);
        Route::get('/visualization/data', [ProductionPlanDetailController::class,'visualization_data'])->name('kitting.plan.visualization.data');
    });
    
});