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


// Route::get('/', function () {
//  return redirect()->route('login');
// });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::group(['prefix' => 'user'], function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/overview', [LandingController::class, 'showOverview'])->name('overview');
    Route::get('/personal-info', [LandingController::class, 'showPersonalInfo'])->name('personal-info');
    Route::post('/personal-info', [LandingController::class, 'storePersonalInfo'])->name('personal-info.store');
    
    // Assessment Routes - Multi-page flow (one trait per page)
    Route::group(['prefix' => 'assessment'], function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('assessment.index');
        Route::get('/trait/{traitId}', [AssessmentController::class, 'showTrait'])->name('assessment.trait');
        Route::post('/trait/{traitId}', [AssessmentController::class, 'storeTrait'])->name('assessment.trait.store');
        Route::get('/complete', [AssessmentController::class, 'showComplete'])->name('assessment.complete');
    });
});


Route::group(['prefix' => 'api'], function () {
    Route::resource('manage', ManageController::class)->only([
        'store', 'update', 'destroy'
    ]);
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

Route::middleware('auth')->group(function () {
  
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    Route::group(['prefix' => 'respondents'], function () {
        Route::get('/', [RespondentsController::class, 'index'])->name('respondents.index');
        Route::post('/list', [RespondentsController::class, 'list'])->name('respondents.list');
        Route::get('/show', [RespondentsController::class, 'show'])->name('respondents.show');
    });
    

    Route::get('/interpretation', [InterpretationController::class, 'index'])->name('interpretation');
    

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
    

    Route::group(['prefix' => 'api'], function () {
        Route::resource('manage', ManageController::class)->only([
            'store', 'update', 'destroy'
        ]);
    });
    

    Route::group(['prefix' => 'api/score-matrix'], function () {

        Route::get('/likert-scales', [ScoreMatrixController::class, 'getLikertScales']);
        Route::post('/likert-scales', [ScoreMatrixController::class, 'storeLikertScale']);
        Route::put('/likert-scales/{id}', [ScoreMatrixController::class, 'updateLikertScale']);
        Route::delete('/likert-scales/{id}', [ScoreMatrixController::class, 'destroyLikertScale']);
         

        Route::get('/sub-traits', [ScoreMatrixController::class, 'getSubTraits']);
        Route::get('/interpretations', [ScoreMatrixController::class, 'getInterpretations']);
        

        Route::get('/sub-trait-interpretations', [ScoreMatrixController::class, 'getSubTraitInterpretations']);
        Route::get('/trait-interpretations', [ScoreMatrixController::class, 'getTraitInterpretations']);
        
        Route::get('/sub-trait-matrices', [ScoreMatrixController::class, 'getSubTraitMatrices']);
        Route::post('/sub-trait-matrices', [ScoreMatrixController::class, 'storeSubTraitMatrix']);
        Route::put('/sub-trait-matrices/{id}', [ScoreMatrixController::class, 'updateSubTraitMatrix']);
        Route::delete('/sub-trait-matrices/{id}', [ScoreMatrixController::class, 'destroySubTraitMatrix']);
        

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
    
});