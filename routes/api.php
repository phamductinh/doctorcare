<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'getAllUsers']);
Route::get('/get-total-row-user', [UserController::class, 'getTotalRowUser']);
Route::get('/get-pagination-users', [UserController::class, 'getPaginationUsers']);
Route::get('/get-user', [UserController::class, 'getUser']);
Route::post('/create-user', [UserController::class, 'createUser']);
Route::put('/update-user', [UserController::class, 'updateUser']);
Route::delete('/delete-user', [UserController::class, 'deleteUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
