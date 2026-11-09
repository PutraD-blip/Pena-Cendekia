<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\BookReviewApiController;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| REST API Routes for Pena Cendekia Mobile App & Integrations
|--------------------------------------------------------------------------
| Semua response berbentuk JSON { success, message, data }.
| Base URL: http://10.0.2.2:8000/api/ (Emulator) atau http://<IP_LAN>:8000/api/ (HP Fisik)
*/

// Books & Catalog Endpoints (CRUD)
Route::get('/books', [BookApiController::class, 'index']);
Route::get('/books/{id}', [BookApiController::class, 'show']);
Route::get('/reviews', [BookReviewApiController::class, 'index']);
Route::post('/reviews', [BookReviewApiController::class, 'store'])->middleware('throttle:30,1');
Route::get('/notifications', [BookApiController::class, 'notifications']);
Route::post('/books', [BookApiController::class, 'store']);
Route::put('/books/{id}', [BookApiController::class, 'update']);
Route::delete('/books/{id}', [BookApiController::class, 'destroy']);

// Categories & Mobile Home Curated Data
Route::get('/categories', [BookApiController::class, 'categories']);
Route::get('/home', [BookApiController::class, 'home']);

// Authentication Endpoints for Mobile
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/google-login', [AuthApiController::class, 'googleLogin']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::get('/user', [AuthApiController::class, 'user']);
