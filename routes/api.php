<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterAgamaController;
use App\Http\Controllers\MasterJabatanController;
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

Route::get('/user-details', [AuthController::class, 'userDetails'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::put('/new-kd-password', [AuthController::class, 'newKdPass'])->middleware('auth:sanctum');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/list-jabatan', [MasterJabatanController::class, 'getJabatan']);
Route::get('/list-agama', [MasterAgamaController::class, 'getAgama']);

