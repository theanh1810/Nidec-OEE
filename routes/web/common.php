<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/change-language/{language}',[HomeController::class, 'change_language'])->name('home.changeLanguage');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
            return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');