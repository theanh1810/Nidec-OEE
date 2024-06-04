<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Statistic\OeeReportController;
use App\Http\Controllers\Api\Statistic\ExportReportController;

Route::prefix('statistic')->group(function() {
    Route::get('oee-report-by-day', [OeeReportController::class, 'oeeReportByDay']);
    Route::get('oee-report-by-machine', [OeeReportController::class, 'oeeReportByMachine']);
    Route::get('product-defective-report', [OeeReportController::class, 'productDefectiveReport']);
    Route::get('product-defective-report-by-line', [OeeReportController::class, 'productDefectiveReportByLine']);
    Route::get('error-and-not-error', [OeeReportController::class, 'errorAndNotError']);
    Route::get('machine-error', [OeeReportController::class, 'machineError']);
    Route::get('stop-not-error', [OeeReportController::class, 'stopNotError']);
    Route::get('stop-quality', [OeeReportController::class, 'stopQuality']);
    Route::get('stop-report', [OeeReportController::class, 'stopReport']);

    Route::prefix('export')->group(function() {
        Route::get('oee-report-by-day', [ExportReportController::class, 'oeeReportByDay']);
        Route::get('oee-report-by-machine', [ExportReportController::class, 'oeeReportByMachine']);
        Route::get('stop-report', [ExportReportController::class, 'stopReport']);
        Route::get('product-defective-report', [ExportReportController::class, 'productDefectiveReport']);
    });
});
