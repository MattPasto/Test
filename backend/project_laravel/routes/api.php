<?php

use App\Http\Controllers\AutomezzoController;
use App\Http\Controllers\FilialeController;
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

// Rotte Automezzo
Route::post(   uri: 'automezzo/create',         action: [AutomezzoController::class, 'create']);
Route::put(    uri: 'automezzo/update',         action: [AutomezzoController::class, 'update']);
Route::get(    uri: 'automezzo/list',           action: [AutomezzoController::class, 'list']);
Route::get(    uri: 'automezzo/details/{id}',   action: [AutomezzoController::class, 'details']);
Route::delete( uri: 'automezzo/delete',         action: [AutomezzoController::class, 'delete']);

// Rotte Filaile
Route::post(   uri: 'filiale/create',           action: [FilialeController::class, 'create']);
Route::put(    uri: 'filiale/update',           action: [FilialeController::class, 'update']);
Route::get(    uri: 'filiale/list',             action: [FilialeController::class, 'list']);
Route::get(    uri: 'filiale/details/{id}',     action: [FilialeController::class, 'details']);
Route::delete( uri: 'filiale/delete',           action: [FilialeController::class, 'delete']);
