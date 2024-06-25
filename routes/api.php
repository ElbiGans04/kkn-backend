<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthCustomController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\ProgramKerjaController;
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
    Route::prefix("/kelompok")->group(function () {
        Route::get('/', [KelompokController::class, 'GetAllKelompokData'])->name('kelompok.all');
        Route::get('/{id}', [KelompokController::class, 'GetKelompokData'])->name('kelompok.spesific');
        Route::post('/', [KelompokController::class, 'StoreKelompokData'])->name('kelompok.store');
        Route::put('/{id}', [KelompokController::class, 'UpdateKelompokData'])->name('kelompok.update');
        Route::delete('/{id}', [KelompokController::class, 'DeleteKelompokData'])->name('kelompok.delete');
        Route::put('/{id}/status', [KelompokController::class, 'UpdateKelompokStatus'])->name('kelompok.updateStatus');
        Route::put('/{id}/dospem', [KelompokController::class, 'UpdateKelompokDospem'])->name('kelompok.updateDosepm');
    });

    Route::prefix("/program-kerja")->group(function () {
        Route::get('/', [ProgramKerjaController::class, 'GetAllProgramKerjaData'])->name('program-kerja.all');
        Route::get('/{id}', [ProgramKerjaController::class, 'GetProgramKerjaData'])->name('program-kerja.spesific');
        Route::post('/', [ProgramKerjaController::class, 'StoreProgramKerjaData'])->name('program-kerja.store');
        Route::put('/{id}', [ProgramKerjaController::class, 'UpdateProgramKerjaData'])->name('program-kerja.update');
        Route::delete('/{id}', [ProgramKerjaController::class, 'DeleteProgramKerjaData'])->name('program-kerja.delete');
        Route::put('/{id}/status', [ProgramKerjaController::class, 'UpdateProgramKerjaStatus'])->name('program-kerja.update_status');
    });


});


Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthCustomController::class, 'loginGet'])->name('login')->middleware('guest');
    Route::post('/login', [AuthCustomController::class, 'login'])->name('auth_custom.login');
    Route::get('/user', [AuthCustomController::class, 'getUserDetail'])->middleware('auth:sanctum')->name('auth_custom.user');
    Route::get('/logout', [AuthCustomController::class, 'logout'])->middleware('auth:sanctum')->name('auth_custom.logout');
});
