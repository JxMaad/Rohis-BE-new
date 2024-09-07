<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DokumentasiController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\MentoringController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'index']);

Route::post('/daftar', [PendaftaranController::class, 'tambahPendaftar']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'semuaUser'])->middleware('permission:users.index');
        Route::post('/tambah', [UserController::class, 'tambahUser'])->middleware('permission:users.create');
        Route::post('/edit/{id}', [UserController::class, 'editUser'])->middleware('permission:users.edit');
        Route::get('/tampilkan/{id}', [UserController::class, 'tampilUser'])->middleware('permission:users.index');
        Route::delete('/hapus/{id}', [UserController::class, 'hapusUser'])->middleware('permission:users.delete');
    });

    Route::prefix('kegiatan')->group(function () {
        Route::get('/', [KegiatanController::class, 'semuaKegiatan'])->middleware('permission:kegiatan.index');
        Route::post('/tambah', [KegiatanController::class, 'tambahKegiatan'])->middleware('permission:kegiatan.create');
        Route::post('/edit/{id}', [KegiatanController::class, 'editKegiatan'])->middleware('permission:kegiatan.edit');
        Route::get('/tampilkan/{id}', [KegiatanController::class, 'tampilKegiatan'])->middleware('permission:kegiatan.index');
        Route::delete('/hapus/{id}', [KegiatanController::class, 'hapusKegiatan'])->middleware('permission:kegiatan.delete');
    });

    Route::prefix('dokumentasi')->group(function () {
        Route::get('/', [DokumentasiController::class, 'semuaDokumentasi'])->middleware('permission:dokumentasi.index');
        Route::post('/tambah', [DokumentasiController::class, 'tambahDokumentasi'])->middleware('permission:dokumentasi.create');
        Route::post('/edit/{id}', [DokumentasiController::class, 'editDokumentasi'])->middleware('permission:dokumentasi.edit');
        Route::get('/tampilkan/{id}', [DokumentasiController::class, 'tampilDokumentasi'])->middleware('permission:dokumentasi.index');
        Route::delete('/hapus/{id}', [DokumentasiController::class, 'hapusDokumentasi'])->middleware('permission:dokumentasi.delete');
    });

    Route::prefix('pendaftar')->group(function () {
        Route::get('/', [PendaftaranController::class, 'semuaPendaftar'])->middleware('permission:pendaftaran.index');
        Route::post('/edit/{id}', [PendaftaranController::class, 'editPendaftar'])->middleware('permission:pendaftaran.edit');
        Route::get('/tampilkan/{id}', [PendaftaranController::class, 'tampilPendaftar'])->middleware('permission:pendaftaran.index');
        Route::delete('/hapus/{id}', [PendaftaranController::class, 'hapusPendaftar'])->middleware('permission:pendaftaran.delete');
    });

    Route::prefix('mentoring')->group(function () {
        Route::get('/', [MentoringController::class, 'semuaMentoring'])->middleware('permission:mentoring.index');
        Route::post('/tambah', [MentoringController::class, 'tambahMentoring'])->middleware('permission:mentoring.create');
        Route::post('/edit/{id}', [MentoringController::class, 'editMentoring'])->middleware('permission:mentoring.edit');
        Route::get('/tampilkan/{id}', [MentoringController::class, 'tampilMentoring'])->middleware('permission:mentoring.index');
        Route::delete('/hapus/{id}', [MentoringController::class, 'hapusMentoring'])->middleware('permission:mentoring.delete');
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmin'])->middleware('role:superadmin');
    Route::get('/dashboard/pembina', [DashboardController::class, 'pembina'])->middleware('role:pembina');
    Route::get('/dashboard/mentor', [DashboardController::class, 'mentor'])->middleware('role:mentor');
    Route::get('/dashboard/alumni', [DashboardController::class, 'alumni'])->middleware('role:alumni');
    Route::get('/dashboard/bph', [DashboardController::class, 'bph'])->middleware('role:bph');
    Route::get('/dashboard/pengurus-kegiatan', [DashboardController::class, 'pengurusKegiatan'])->middleware('role:pengurus_kegiatan');
    Route::get('/dashboard/pengurus-dokumentasi', [DashboardController::class, 'pengurusDokumentasi'])->middleware('role:pengurus_dokumentasi');
    Route::get('/dashboard/pengurus-rohis', [DashboardController::class, 'pengurusRohis'])->middleware('role:pengurus_rohis');
});