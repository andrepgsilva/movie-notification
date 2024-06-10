<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SubscribeForAvailableMovieController;
use App\Http\Controllers\ReceiveMovieSubscriptionMessageController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::post('/subscribe-for-movie', SubscribeForAvailableMovieController::class);

Route::post(
    '/receive-movie-subscription-message',
    ReceiveMovieSubscriptionMessageController::class
)->name('receive-movie-subscription-message');