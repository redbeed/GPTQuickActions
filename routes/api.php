<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/ical', [\App\Http\Controllers\IcalController::class, 'parse'])->name('ical.parse');
Route::get('/date', \App\Http\Controllers\DateController::class)->name('date');


