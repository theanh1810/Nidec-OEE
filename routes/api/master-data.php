<?php

use App\Http\Controllers\Api\Account\AccountController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MasterData\MasterAGVController;
use App\Http\Controllers\Api\MasterData\MasterUnitController;
use App\Http\Controllers\Api\MasterData\MasterStatusController;
use App\Http\Controllers\Api\MasterData\MasterShiftController;
use App\Http\Controllers\Api\MasterData\MasterProductController;
use App\Http\Controllers\Api\MasterData\MasterMaterialsController;
use App\Http\Controllers\Api\MasterData\MasterMachineController;
use App\Http\Controllers\Api\MasterData\MasterHolidayController;
use App\Http\Controllers\Api\MasterData\MasterMoldController;
use App\Http\Controllers\Api\MasterData\MasterLineController;

Route::prefix('settings')->group(function () {
    Route::prefix('unit')->group(function () {
        Route::get('/', [MasterUnitController::class, 'index']);
        Route::get('/history', [MasterUnitController::class, 'history']);
    });
    Route::prefix('status')->group(function () {
        Route::get('/', [MasterStatusController::class, 'index']);
    });
    Route::prefix('shift')->group(function () {
        Route::get('/', [MasterShiftController::class, 'index']);
        Route::get('/history', [MasterShiftController::class, 'history']);
    });
    Route::prefix('product')->group(function () {
        Route::get('/', [MasterProductController::class, 'index']);
        Route::get('/history', [MasterProductController::class, 'history']);
    });
    Route::prefix('materials')->group(function () {
        Route::get('/', [MasterMaterialsController::class, 'index']);
        Route::get('/history', [MasterMaterialsController::class, 'history']);
    });
    Route::prefix('line')->group(function () {
        Route::get('/', [MasterLineController::class, 'index']);
        Route::get('/history', [MasterLineController::class, 'history']);
    });
    Route::prefix('machine')->group(function () {
        Route::get('/', [MasterMachineController::class, 'index']);
        Route::get('/history', [MasterMachineController::class, 'history']);
    });
    Route::prefix('holiday')->group(function () {
        Route::get('/', [MasterHolidayController::class, 'index']);
    });
    Route::prefix('mold')->group(function () {
        Route::get('/', [MasterMoldController::class, 'index']);
        Route::get('/history', [MasterMoldController::class, 'history']);
    });

    Route::prefix('agv')->group(function () {
        Route::get('/', [MasterAGVController::class, 'index']);
        Route::get('/history', [MasterAGVController::class, 'history']);
    });

    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::get('/role', [AccountController::class, 'index_role']);
        Route::get('/history', [AccountController::class, 'history']);
    });
});

Route::prefix('master-machine')->group(function () {
    Route::get('/', [MasterMachineController::class, 'get']);
    Route::get('/{id}', [MasterMachineController::class, 'show']);
    Route::get('/byline/{id}', [MasterMachineController::class, 'byLine']);
});
