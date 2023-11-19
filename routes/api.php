<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;


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
//
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [LoginController::class, 'show']);


Route::middleware(['auth:sanctum','checkAdmin'])->group(function () {
    Route::post('/transaction', [TransactionController::class,'create']);
    Route::post('/transaction/{id}/payment', [TransactionController::class,'recordPayment']);
    Route::get('/report/{userId}', [TransactionController::class,'generateReport']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class,'index']);
    Route::get('/transaction/{id}', [TransactionController::class,'show']);
});
