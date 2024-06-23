<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthCustomController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/* 

- Custom

*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("/anggota")->group(function () {
        Route::get('/', [AnggotaController::class, 'GetAllAnggotaData'])->name('anggota.all');
        Route::get('/{id}', [AnggotaController::class, 'GetAnggotaData'])->name('anggota.spesific');
        Route::post('/', [AnggotaController::class, 'StoreAnggotaData'])->name('anggota.store');
        Route::put('/{id}', [AnggotaController::class, 'UpdateAnggotaData'])->name('anggota.update');
        Route::delete('/{id}', [AnggotaController::class, 'DeleteAnggotaData'])->name('anggota.delete');
    });
});


Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthCustomController::class, 'loginGet'])->name('login')->middleware('guest');
    Route::post('/login', [AuthCustomController::class, 'login'])->name('auth_custom.login');
    Route::get('/user', [AuthCustomController::class, 'getUserDetail'])->middleware('auth:sanctum')->name('auth_custom.user');
    Route::get('/logout', [AuthCustomController::class, 'logout'])->middleware('auth:sanctum')->name('auth_custom.logout');
});
