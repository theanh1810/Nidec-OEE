<?php

use App\Http\Controllers\Api\ControlAGV\TransController;
use Illuminate\Support\Facades\Route;


Route::prefix('control-agv')->group(function () {
    Route::prefix('trans')->group(function () {
        Route::get('list-command-api', [TransController::class, 'index']);
    });
});
