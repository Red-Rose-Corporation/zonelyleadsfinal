<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ── Lipto (virtual currency) ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('game/lipto')->group(function () {
    Route::get('balance',  [\App\Http\Controllers\Game\LiptoController::class, 'balance']);
    Route::post('earn',    [\App\Http\Controllers\Game\LiptoController::class, 'earn']);
    Route::post('spend',   [\App\Http\Controllers\Game\LiptoController::class, 'spend']);
    Route::post('transfer',[\App\Http\Controllers\Game\LiptoController::class, 'transfer']);
});
