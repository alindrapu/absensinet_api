<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\MasterAgamaController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\PegawaiCurrentController;
use App\Http\Controllers\PegawaiCurrentPositionController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RekapController;
use App\Models\PegawaiCurrent;
use App\Models\PegawaiCurrentPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

// Auth routes
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::put('/new-kd-password', [AuthController::class, 'newKdPass'])->middleware('auth:sanctum');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users/export-xls', [AuthController::class, 'export']);
Route::get('/presensi/export-xls', [PresensiController::class, 'export']);
Route::get('/cuti/export-xls', [CutiController::class, 'export']);
Route::get('/rekap/export-xls', [RekapController::class, 'export']);

// User routes
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user-details', [AuthController::class, 'userDetails']);
  Route::get('/user', function (Request $request) {
    return $request->user();
  });


  // Other authenticated routes
  Route::post('/add-pegawai-current', [PegawaiCurrentController::class, 'addPegawaiCurrent']);
  Route::post('/update-current-position', [PegawaiCurrentPositionController::class, 'updateCurrentPosition']);
  Route::post('/presensi-pegawai', [PresensiController::class, 'presensiPegawai']);
  Route::post('/check-presensi', [PresensiController::class, 'checkPresensi']);
  Route::post('/last-v-days', [PresensiController::class, 'last5Days']);
  Route::post('/histories', [PresensiController::class, 'allHistory']);
  Route::get('/presensi/export_excel', [PresensiController::class, 'export_excel']);
  Route::post('/request-cuti', [CutiController::class, 'requestCuti']);
  Route::post('/update-cuti', [CutiController::class, 'approvalCuti']);
  Route::post('/get-list-permohonan-cuti', [CutiController::class, 'getListPermohonanCuti']);
  Route::put('/update-password', [AuthController::class, 'updatePassword']);
});

// Other non-authenticated routes
Route::get('/list-jabatan', [MasterJabatanController::class, 'getJabatan']);
Route::get('/list-agama', [MasterAgamaController::class, 'getAgama']);
Route::get('/list-jenis-cuti', [CutiController::class, 'getJenisCuti']);
