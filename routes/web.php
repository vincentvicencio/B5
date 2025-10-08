<?php

use App\Http\Controllers\AddSection;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RespondentsController;
use App\Http\Controllers\InterpretationController;


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

// User Routes (Public Assessment Flow)
Route::get('/user', [LandingController::class, 'index'])->name('landing');
Route::get('/user/overview', [LandingController::class, 'showOverview'])->name('overview');
Route::get('/user/personal-info', [LandingController::class, 'showPersonalInfo'])->name('personal-info');
Route::post('/user/personal-info', [LandingController::class, 'storePersonalInfo'])->name('personal-info.store');

// Assessment Routes - Multi-page flow (one trait per page)
Route::group(['prefix' => 'user/assessment'], function () {
    // Redirect to first trait
    Route::get('/', [AssessmentController::class, 'index'])->name('assessment.index');
    
    // Show specific trait page
    Route::get('/trait/{traitId}', [AssessmentController::class, 'showTrait'])->name('assessment.trait');
    
    // Store trait responses and move to next (IMPORTANT: traitId must match the parameter name)
    Route::post('/trait/{traitId}', [AssessmentController::class, 'storeTrait'])->name('assessment.trait.store');
    
    // Show completion page
    Route::get('/complete', [AssessmentController::class, 'showComplete'])->name('assessment.complete');
});

// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create'); 
    Route::post('/', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit'); 
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

Route::group(['prefix' => 'api'], function () {
    Route::resource('manage', ManageController::class)->only([
        'store', 'update', 'destroy'
    ]);
});

Route::resource('manage', ManageController::class)->except([
    'store', 'update', 'destroy'
]);
