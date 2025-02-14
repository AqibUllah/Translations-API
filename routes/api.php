<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
       Route::post('login','login');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('translations/export', [TranslationController::class, 'export']);
    Route::apiResource('translations', TranslationController::class);
});
