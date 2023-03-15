<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriLaporanController;
use App\Http\Controllers\API\LaporanController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
  Route::post('auth/logout', [AuthController::class, 'logout']);

  Route::prefix('category')->group(function () {
    Route::get('get-all-categories', [KategoriLaporanController::class, 'index']);
    Route::get('detail-category', [KategoriLaporanController::class, 'show']);
    Route::post('create-category', [KategoriLaporanController::class, 'store']);
    Route::put('update-category/{id}', [KategoriLaporanController::class, 'update']);
    Route::delete('delete-category/{id}', [KategoriLaporanController::class, 'destroy']);
  });

  Route::prefix('laporan')->group(function () {
    Route::get('get-all-laporan', [LaporanController::class, 'index']);
    Route::get('get-laporan-by-mahasiswa', [LaporanController::class, 'getLaporanByMahasiswa']);
    Route::get('detail-laporan', [LaporanController::class, 'show']);
    Route::post('create-laporan', [LaporanController::class, 'store']);
    Route::post('update-laporan', [LaporanController::class, 'update']);
    Route::post('delete-laporan', [LaporanController::class, 'destroy']);
  });
});

Route::prefix('auth')->group(function () {
  Route::post('login', [AuthController::class, 'login']);
  Route::post('register', [AuthController::class, 'register']);
});

// Route::prefix('posts')->group(function () {
//   Route::get('/get-all-posts', [PostController::class, 'index']);
//   Route::get('/detail-post', [PostController::class, 'show']);
//   Route::post('/create-post', [PostController::class, 'store']);
//   Route::put('/update-post', [PostController::class, 'update']);
//   Route::delete('/delete-post', [PostController::class, 'destroy']);
// });
