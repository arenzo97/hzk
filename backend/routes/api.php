<?php

use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\Pages\FeaturedLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);

Route::get('/pages/{id}/featured', [FeaturedLinkController::class, 'index']);
