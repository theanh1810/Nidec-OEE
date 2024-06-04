<?php

use App\Http\Controllers\Web\Account\AccountController;
use App\Http\Controllers\Web\MasterData\MasterAGVController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\MasterData\MasterUnitController;
use App\Http\Controllers\Web\MasterData\MasterProductController;
use App\Http\Controllers\Web\MasterData\MasterMaterialsController;
use App\Http\Controllers\Web\MasterData\MasterMachineController;
use App\Http\Controllers\Web\MasterData\MasterStatusController;
use App\Http\Controllers\Web\MasterData\MasterShiftController;
use App\Http\Controllers\Web\MasterData\MasterMoldController;
use App\Http\Controllers\Web\MasterData\MasterHolidayController;
use App\Http\Controllers\Web\MasterData\MasterLineController;

Route::prefix('/setting')->group(function () {
    Route::prefix('/setting-unit')->group(function () {
        Route::get('/', [MasterUnitController::class, 'index'])->name('masterData.unit');
        Route::get('/return', [MasterUnitController::class, 'return'])->name('masterData.unit.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
        Route::get('/show', [MasterUnitController::class, 'show'])->name('masterData.unit.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterUnitController::class, 'filter'])->name('masterData.unit.filter');
        Route::post('/add-or-update', [MasterUnitController::class, 'add_or_update'])->name('masterData.unit.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterUnitController::class, 'destroy'])->name('masterData.unit.destroy')->middleware('role:delete_master');
    });
    Route::prefix('/setting-product')->group(function () {
        Route::get('/', [MasterProductController::class, 'index'])->name('masterData.product');
        Route::get('/show', [MasterProductController::class, 'show'])->name('masterData.product.show')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/filter-bom', [MasterProductController::class, 'get_id_bom_and_materials'])->name('masterData.product.getIdBomAndMaterials');
        Route::post('/filter', [MasterProductController::class, 'filter'])->name('masterData.product.filter');
        Route::post('/add-or-update', [MasterProductController::class, 'add_or_update'])->name('masterData.product.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterProductController::class, 'destroy'])->name('masterData.product.destroy')->middleware('role:delete_master');
        Route::post('/import_file', [MasterProductController::class, 'import_file'])->name('masterData.product.import_file');
        Route::get('/export_file', [MasterProductController::class, 'export_file'])->name('masterData.product.export_file')->middleware(['role:create_master', 'role:export_master']);
        Route::get('/bom', [MasterProductController::class, 'get_id_bom_and_materials'])->name('masterData.product.bom')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/add_product_and_materials_to_bom', [MasterProductController::class, 'add_product_and_materials_to_bom'])->name('masterData.product.add_product_and_materials_to_bom')->middleware(['role:create_master', 'role:update_master']);

        Route::get('/return', [MasterProductController::class, 'return'])->name('masterData.product.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
    });
    Route::prefix('/setting-materials')->group(function () {
        Route::get('/', [MasterMaterialsController::class, 'index'])->name('masterData.materials');
        Route::get('/show', [MasterMaterialsController::class, 'show'])->name('masterData.materials.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterMaterialsController::class, 'filter'])->name('masterData.materials.filter');
        Route::post('/add-or-update', [MasterMaterialsController::class, 'add_or_update'])->name('masterData.materials.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/import-file-excel', [MasterMaterialsController::class, 'import_file_excel'])->name('masterData.materials.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
        Route::get('/destroy', [MasterMaterialsController::class, 'destroy'])->name('masterData.materials.destroy')->middleware('role:delete_master');
        Route::get('/export_file', [MasterMaterialsController::class, 'export_file'])->name('masterData.materials.export_file')->middleware(['role:create_master', 'role:export_master']);

        Route::get('/return', [MasterMaterialsController::class, 'return'])->name('masterData.materials.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
    });
    Route::prefix('/setting-line')->group(function () {
        Route::get('/', [MasterLineController::class, 'index'])->name('masterData.line');
        Route::get('/show', [MasterLineController::class, 'show'])->name('masterData.line.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterLineController::class, 'filter'])->name('masterData.line.filter');
        Route::post('/add-or-update', [MasterLineController::class, 'add_or_update'])->name('masterData.line.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterLineController::class, 'destroy'])->name('masterData.line.destroy')->middleware('role:delete_master');
        Route::post('/import-file-excel', [MasterLineController::class, 'import_file_excel'])->name('masterData.line.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
        Route::get('/export_file', [MasterLineController::class, 'export_file'])->name('masterData.line.export_file')->middleware(['role:create_master', 'role:export_master']);

        Route::get('/return', [MasterLineController::class, 'return'])->name('masterData.line.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
    });
    Route::prefix('/setting-machine')->group(function () {
        Route::get('/', [MasterMachineController::class, 'index'])->name('masterData.machine');
        Route::get('/show', [MasterMachineController::class, 'show'])->name('masterData.machine.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterMachineController::class, 'filter'])->name('masterData.machine.filter');
        Route::post('/add-or-update', [MasterMachineController::class, 'add_or_update'])->name('masterData.machine.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterMachineController::class, 'destroy'])->name('masterData.machine.destroy')->middleware('role:delete_master');
        Route::post('/import-file-excel', [MasterMachineController::class, 'import_file_excel'])->name('masterData.machine.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
        Route::get('/export_file', [MasterMachineController::class, 'export_file'])->name('masterData.machine.export_file')->middleware(['role:create_master', 'role:export_master']);

        Route::get('/return', [MasterMachineController::class, 'return'])->name('masterData.machine.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
    });
    Route::prefix('/setting-status')->group(function () {
        Route::get('/', [MasterStatusController::class, 'index'])->name('masterData.status');
        Route::get('/show', [MasterStatusController::class, 'show'])->name('masterData.status.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/add-or-update', [MasterStatusController::class, 'add_or_update'])->name('masterData.status.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/show-add', [MasterStatusController::class, 'show_add'])->name('masterData.status.show.add')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/show-update', [MasterStatusController::class, 'show_update'])->name('masterData.status.show.update')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterStatusController::class, 'filter'])->name('masterData.status.filter');
        Route::post('/add', [MasterStatusController::class, 'add'])->name('masterData.status.add')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/update', [MasterStatusController::class, 'update'])->name('masterData.status.update')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterStatusController::class, 'destroy'])->name('masterData.status.destroy')->middleware('role:delete_master');
    });
    Route::prefix('/setting-shift')->group(function () {
        Route::get('/', [MasterShiftController::class, 'index'])->name('masterData.shift');
        Route::get('/show', [MasterShiftController::class, 'show'])->name('masterData.shift.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterShiftController::class, 'filter'])->name('masterData.shift.filter');
        Route::post('/add-or-update', [MasterShiftController::class, 'add_or_update'])->name('masterData.shift.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterShiftController::class, 'destroy'])->name('masterData.shift.destroy')->middleware('role:delete_master');
        Route::get('/return', [MasterShiftController::class, 'return'])->name('masterData.shift.return')->middleware(['role:create_master', 'role:update_master', 'role:delete_master']);
    });
    Route::prefix('/setting-holiday')->group(function () {
        Route::get('/', [MasterHolidayController::class, 'index'])->name('masterData.holiday');
        Route::get('/show', [MasterHolidayController::class, 'show'])->name('masterData.holiday.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterHolidayController::class, 'filter'])->name('masterData.holiday.filter');
        Route::post('/add-or-update', [MasterHolidayController::class, 'add_or_update'])->name('masterData.holiday.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterHolidayController::class, 'destroy'])->name('masterData.holiday.destroy')->middleware('role:delete_master');
    });
    Route::prefix('/setting-mold')->group(function () {
        Route::get('/', [MasterMoldController::class, 'index'])->name('masterData.mold');
        Route::get('/show', [MasterMoldController::class, 'show'])->name('masterData.mold.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterMoldController::class, 'filter'])->name('masterData.mold.filter');
        Route::post('/add-or-update', [MasterMoldController::class, 'add_or_update'])->name('masterData.mold.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterMoldController::class, 'destroy'])->name('masterData.mold.destroy')->middleware('role:delete_master');;
        Route::post('/import-file-excel', [MasterMoldController::class, 'import_file_excel'])->name('masterData.mold.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
        Route::get('/export_file', [MasterMoldController::class, 'export_file'])->name('masterData.mold.export_file')->middleware(['role:create_master', 'role:export_master']);
        Route::get('/accessories', [MasterMoldController::class, 'accessories'])->name('masterData.mold.accessories')->middleware(['role:create_master', 'role:update_master']);
    });
    Route::prefix('/setting-agv')->group(function () {
        Route::get('/', [MasterAGVController::class, 'index'])->name('masterData.agv');
        Route::get('/show', [MasterAGVController::class, 'show'])->name('masterData.agv.show')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/filter', [MasterAGVController::class, 'filter'])->name('masterData.agv.filter');
        Route::post('/add-or-update', [MasterAGVController::class, 'add_or_update'])->name('masterData.agv.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
        Route::get('/destroy', [MasterAGVController::class, 'destroy'])->name('masterData.agv.destroy')->middleware('role:delete_master');

        Route::get('/efficiency', [MasterAGVController::class, 'efficiencyAgv'])->name('masterData.agv.efficiencyAgv');
    });
});
Route::prefix('/account')->group(function () {
    // User
    Route::prefix('/user')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');
        Route::get('/show', [AccountController::class, 'show'])->name('account.show');
        Route::post('/add-or-update', [AccountController::class, 'add_or_update'])->name('account.addOrUpdate');
        Route::post('/reset-password', [AccountController::class, 'reset_password'])->name('account.resetPassword');
        // Route::get('/check-password', 'AccountController@check_password')->name('account.checkPassword');

        Route::get('/destroy', [AccountController::class, 'destroy'])->name('account.destroy')->middleware(['role:create_master', 'role:delete_master']);
    });

    Route::prefix('/role')->group(function () {
        Route::get('/', [AccountController::class, 'index_role'])->name('account.role');
        Route::get('/show', [AccountController::class, 'show_role'])->name('account.show.role')->middleware(['role:create_master', 'role:update_master']);
        Route::post('/add-or-update', [AccountController::class, 'add_or_update_role'])->name('account.addOrUpdate.role')->middleware(['role:create_master', 'role:update_master']);
        // Route::post('/reset-password', [AccountController::class, 'reset_password'])->name('account.resetPassword');
        // Route::get('/check-password', 'AccountController@check_password')->name('account.checkPassword');

        Route::get('/destroy', [AccountController::class, 'destroy_role'])->name('account.destroy.role')->middleware(['role:create_master', 'role:delete_master']);
    });
});
