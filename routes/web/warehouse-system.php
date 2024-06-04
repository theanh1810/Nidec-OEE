<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\WarehouseSystem\ExportMaterialsController;


Route::prefix('/warehouse-system')->group(function () {
	Route::get('/export-materials', [ExportMaterialsController::class, 'index'])->name('warehouse_system.export_materials');
	Route::post('/export-materials/export', [ExportMaterialsController::class, 'export'])->name('warehouse_system.export_materials.export');
	Route::get('/export-materials/cancel-exp', [ExportMaterialsController::class, 'cancel'])->name('warehouse_system.export_materials.cancel');
});
