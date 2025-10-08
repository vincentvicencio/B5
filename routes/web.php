<?php

use App\Http\Controllers\AddSection;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\ManageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RespondentsController;

use App\Http\Controllers\ScoreMatrixController;
use App\Http\Controllers\InterpretationController;

// FIX: Change the root route to call the ManageController@index method.
Route::get('/', [ManageController::class, 'index'])->name('root');

// Route::get('/', function () {
//  return redirect()->route('login');
// });

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

Route::group(['prefix' => 'api'], function () {
    Route::resource('manage', ManageController::class)->only([
        'store', 'update', 'destroy'
    ]);
});


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

Route::resource('manage', ManageController::class)->except([
    'store', 'update', 'destroy'
]);

Route::get('/score-matrix', [ScoreMatrixController::class, 'index'])->name('score-matrix');


Route::group(['prefix' => 'api/score-matrix'], function () {
    // Likert Scale Routes
    Route::get('/likert-scales', [ScoreMatrixController::class, 'getLikertScales']);
    Route::post('/likert-scales', [ScoreMatrixController::class, 'storeLikertScale']);
    Route::put('/likert-scales/{id}', [ScoreMatrixController::class, 'updateLikertScale']);
    Route::delete('/likert-scales/{id}', [ScoreMatrixController::class, 'destroyLikertScale']);
     
    // Sub-Trait Matrix Routes
    Route::get('/sub-traits', [ScoreMatrixController::class, 'getSubTraits']);
    Route::get('/interpretations', [ScoreMatrixController::class, 'getInterpretations']);
    
    // NEW: Separate endpoints for Sub-Trait and Trait interpretations
    Route::get('/sub-trait-interpretations', [ScoreMatrixController::class, 'getSubTraitInterpretations']);
    Route::get('/trait-interpretations', [ScoreMatrixController::class, 'getTraitInterpretations']);
    
    Route::get('/sub-trait-matrices', [ScoreMatrixController::class, 'getSubTraitMatrices']);
    Route::post('/sub-trait-matrices', [ScoreMatrixController::class, 'storeSubTraitMatrix']);
    Route::put('/sub-trait-matrices/{id}', [ScoreMatrixController::class, 'updateSubTraitMatrix']);
    Route::delete('/sub-trait-matrices/{id}', [ScoreMatrixController::class, 'destroySubTraitMatrix']);
    
    // Trait Matrix Routes
    Route::get('/traits', [ScoreMatrixController::class, 'getTraits']);
    Route::get('/trait-matrices', [ScoreMatrixController::class, 'getTraitMatrices']);
    Route::post('/trait-matrices', [ScoreMatrixController::class, 'storeTraitMatrix']);
    Route::put('/trait-matrices/{id}', [ScoreMatrixController::class, 'updateTraitMatrix']);
    Route::delete('/trait-matrices/{id}', [ScoreMatrixController::class, 'destroyTraitMatrix']);
});

Route::group(['prefix' => 'api/interpretations'], function () {
    Route::get('/list', [InterpretationController::class, 'getInterpretations']);
    Route::get('/{id}', [InterpretationController::class, 'show']);
    Route::post('/', [InterpretationController::class, 'store']);
    Route::put('/{id}', [InterpretationController::class, 'update']);
    Route::delete('/{id}', [InterpretationController::class, 'destroy']);
});


Route::group(['prefix' => 'api/interpretation-types'], function () {
    Route::get('/', [InterpretationController::class, 'getInterpretationTypes']);
});