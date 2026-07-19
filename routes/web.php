<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;

use App\Http\Controllers\Web\AuthWebController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/schemes', [HomeController::class, 'schemes']);




Route::get('/', [HomeController::class, 'index']);
Route::get('/schemes', [HomeController::class, 'schemes']);

// Auth Web Routes
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthWebController::class, 'showLogin'])->name('register');
Route::get('/schemes/{id}', [HomeController::class, 'schemeDetail']);
Route::get('/dashboard', [HomeController::class, 'dashboard']);
Route::get('/profile/setup', [HomeController::class, 'profileSetup']);
Route::get('/sathi', [HomeController::class, 'sathi']);
Route::get('/search', [HomeController::class, 'search']);
Route::get('/documents', [HomeController::class, 'documents']);
Route::get('/applications', [HomeController::class, 'applications']);
Route::get('/life-events', [HomeController::class, 'lifeEvents']);
Route::get('/nagrik-score', [HomeController::class, 'nagrikScore']);
Route::get('/calculator', [HomeController::class, 'calculator']);
Route::get('/awasar', [HomeController::class, 'awasar']);
Route::get('/awasar/{id}', [HomeController::class, 'awasarDetail']);
Route::get('/seva-mitra-banen', [HomeController::class, 'joinAsAgent']);
Route::get('/csc/dashboard',      [HomeController::class, 'cscDashboard']);
Route::get('/csc/toolkit',        [HomeController::class, 'cscToolkit']);
Route::get('/csc/portal-status',  [HomeController::class, 'cscPortalStatus']);
Route::get('/subscription', [HomeController::class, 'subscription']);
Route::get('/hunar', [HomeController::class, 'hunar']);
Route::get('/seva-mitra', [HomeController::class, 'findSevaMitra']);
Route::get('/seva-mitra/{id}', [HomeController::class, 'sevaMitraDetail']);
