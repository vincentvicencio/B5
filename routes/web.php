<?php

use App\Http\Controllers\AddSection;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterpretationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespondentsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController; 


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'respondents'], function () {
    Route::get('/', [RespondentsController::class, 'index'])->name('respondents.index');
    Route::post('/list', [RespondentsController::class, 'list'])->name('respondents.list');
    Route::get('/show', [RespondentsController::class, 'show'])->name('respondents.show');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/interpretation', [InterpretationController::class, 'index'])->name('interpretation');



Route::get('/user', [LandingController::class, 'index'])->name('landing');
Route::get('/user/assessment/overview', [LandingController::class, 'showOverview'])->name('assessment.overview');
Route::get('/user/assessment/personal-info', [LandingController::class, 'showPersonalInfo'])->name('assessment.personal-info');
Route::post('/user/assessment/personal-info', [LandingController::class, 'storePersonalInfo'])->name('assessment.personal-info.store');


Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create'); 
    Route::post('/', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit'); 
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
});