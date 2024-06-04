<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\ProductionPlan\ProductionPlanController;


Route::prefix('/production-plan')->group(function () {
	Route::get('/', [ProductionPlanController::class, 'index'])->name('productionplan');
	Route::post('/add-or-update', [ProductionPlanController::class, 'add_or_update'])->name('productionplan.addOrUpdate')->middleware('role:create_plan');
	Route::post('/filter', [ProductionPlanController::class, 'filter'])->name('productionplan.filter');
	Route::get('/destroy', [ProductionPlanController::class, 'destroy'])->name('productionplan.destroy')->middleware('role:delete_plan');

	Route::get('/detail', [ProductionPlanController::class, 'detail'])->name('productionplan.detail');
	Route::post('/detail/filter', [ProductionPlanController::class, 'detail_filter'])->name('productionplan.detail.filter');
	Route::post('/detail/add-or-update', [ProductionPlanController::class, 'detail_add_or_update'])->name('productionplan.detail.add_or_update');
	Route::get('/detail/destroy', [ProductionPlanController::class, 'detail_destroy'])->name('productionplan.detail.destroy')->middleware('role:delete_plan');
	Route::get('/detail/visualation', [ProductionPlanController::class, 'detail_visualation'])->name('productionplan.detail.visualation');
	Route::get('/detail/export-materials', [ProductionPlanController::class, 'detail_export_materials'])->name('productionplan.detail.export_materials');
	Route::post('/detail/import_file_excel', [ProductionPlanController::class, 'import_file_excel'])->name('productionplan.detail.import_file_excel');
	Route::get('/visualization', [ProductionPlanController::class, 'visualization'])->name('kitting.plan.visualization');
	Route::get('/export', [ProductionPlanController::class, 'export'])->name('kitting.plan.export');
	Route::get('/cancel', [ProductionPlanController::class, 'cancel'])->name('kitting.plan.cancel');
});
