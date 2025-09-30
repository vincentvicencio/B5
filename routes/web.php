<?php

use App\Http\Controllers\AddSection;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RespondentsController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'respondents'], function () {
     Route::get('/', [RespondentsController::class, 'index'])->name('respondents.index');
     Route::post('/list', [RespondentsController::class, 'list'])->name('respondents.list');
     Route::get('/show', [RespondentsController::class, 'show'])->name('respondents.show');
 });