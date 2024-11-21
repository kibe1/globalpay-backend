<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TransactionsController;


// Route to get the authenticated user's username
Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'getUser']);

//get user transaction details
Route::get('transactions', [TransactionsController::class, 'getByCode']);

// Routes for registration and login
Route::post('register', [UsersController::class, 'register']);
Route::post('login', [UsersController::class, 'login']);

//Route for creating transaction
Route::post('transaction', [TransactionsController::class, 'store']);

