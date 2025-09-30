
<?php

use App\Http\Controllers\AddSection;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespondentsController;

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



