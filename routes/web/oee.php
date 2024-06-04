<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Oee\OeeViewController;

Route::prefix('oee')->group(function() {
    Route::get('visualization', [OeeViewController::class, 'visualization'])->name('oee.visualization');
    Route::get('visualization/detail/{id?}', [OeeViewController::class, 'detail'])->name('oee.detail');
    Route::get('report', [OeeViewController::class, 'report'])->name('oee.report');
});
