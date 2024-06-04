<?php

use App\Http\Controllers\Web\ControlAGV\AgvController;
use App\Http\Controllers\Web\ControlAGV\TransController;
use Illuminate\Support\Facades\Route;


Route::prefix('/control-agv')->group(function () {
    // Trans
    Route::prefix('/trans')->group(function () {
        Route::get('/list-command', [TransController::class, 'index'])->name('index.trans');
        Route::get('/success', [TransController::class, 'successTrans'])->name('success.trans');
        Route::get('/destroy', [TransController::class, 'destroyTrans'])->name('destroy.trans');
    });
    Route::prefix('/efficiency-agv')->group(function () {
        Route::get('/efficiency-error', [TransController::class, 'errorAgv'])->name('controlAGV.trans.errorAgv');
        Route::get('/efficiency-agv', [TransController::class, 'efficienciesAGV'])->name('controlAGV.trans.efficienciesAGV');
    });

    Route::prefix('/transport-system/agv-control')->group(function () {
        Route::get('/select/{id}', [AgvController::class, 'index'])->name('controlAGV.agvControl.index');
        Route::get('/layout', [AgvController::class, 'layoutAgv'])->name('controlAGV.agvControl.layoutAgv');
        Route::get('/point-list', [AgvController::class, 'pointList'])->name('controlAGV.agvControl.pointList');
        Route::get('/map', [AgvController::class, 'map'])->name('controlAGV.agvControl.map');
        Route::get('/create-map', [AgvController::class, 'createMap'])->name('controlAGV.agvControl.createMap');
        Route::get('/update-map', [AgvController::class, 'updateMap'])->name('controlAGV.agvControl.updateMap');
        Route::get('/delete-map', [AgvController::class, 'deleteMap'])->name('controlAGV.agvControl.deleteMap');

        Route::get('/update-all-layout-point', [AgvController::class, 'updateAllLayoutPoint'])->name('controlAGV.agvControl.updateAllLayoutPoint');

        Route::get('/delete-point', [AgvController::class, 'deletePoint'])->name('controlAGV.agvControl.deletePoint');

        Route::get('/update-line-point', [AgvController::class, 'deletePoint'])->name('controlAGV.agvControl.updateLinePoint');

        Route::get('/update-line', [AgvController::class, 'updateLine'])->name('controlAGV.agvControl.updateLine');
    });
});
