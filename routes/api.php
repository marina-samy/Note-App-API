<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Public routes (not requiring authentication)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


// Authenticated routes
    Route::get('/user', [AuthController::class, 'getAuthUser']);
    Route::post('/notes', [NoteController::class, 'create']);
    Route::get('/notes', [NoteController::class, 'getAllNotes']);
    Route::get('/notes/{id}', [NoteController::class, 'getNoteById']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'delete']);
    Route::post('/notes/{id}/attach-file', [NoteController::class, 'attachFile']);
    Route::delete('/attach-file/{id}', [NoteController::class, 'deleteFile']);
