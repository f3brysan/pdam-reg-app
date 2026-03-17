<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MsJenisMeteranController;
use App\Http\Controllers\MsJenisTempatTinggalController;
use App\Http\Controllers\MsPekerjaanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


route::get('/login', [AuthController::class, 'login'])->name('login');
route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
route::post('/logout', [AuthController::class, 'logout'])->name('logout');
route::get('/register', [AuthController::class, 'register'])->name('register');
route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');

route::group(['middleware' => 'auth'], function () {
    route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    route::group(['middleware' => ['role:admin']], function () {
        // * Master (prefix)
        Route::prefix('master')->group(function () {
            // * Master Pekerjaan
            route::get('/pekerjaan', [MsPekerjaanController::class, 'index'])->name('ms_pekerjaans.index');
            route::post('/pekerjaan', [MsPekerjaanController::class, 'store'])->name('ms_pekerjaans.store');
            route::get('/pekerjaan/{id}', [MsPekerjaanController::class, 'show'])->name('ms_pekerjaans.show');
            route::post('/pekerjaan/delete', [MsPekerjaanController::class, 'destroy'])->name('ms_pekerjaans.delete');

            // * Master Jenis Meteran
            route::get('/jenis-meteran', [MsJenisMeteranController::class, 'index'])->name('ms_jenis_meterans.index');
            route::post('/jenis-meteran', [MsJenisMeteranController::class, 'store'])->name('ms_jenis_meterans.store');
            route::get('/jenis-meteran/{id}', [MsJenisMeteranController::class, 'show'])->name('ms_jenis_meterans.show');
            route::post('/jenis-meteran/delete', [MsJenisMeteranController::class, 'destroy'])->name('ms_jenis_meterans.delete');

            // * Master Jenis Tempat Tinggal
            route::get('/jenis-tempat-tinggal', [MsJenisTempatTinggalController::class, 'index'])->name('ms_jenis_tempat_tinggals.index');
            route::post('/jenis-tempat-tinggal', [MsJenisTempatTinggalController::class, 'store'])->name('ms_jenis_tempat_tinggals.store');
            route::get('/jenis-tempat-tinggal/{id}', [MsJenisTempatTinggalController::class, 'show'])->name('ms_jenis_tempat_tinggals.show');
            route::post('/jenis-tempat-tinggal/delete', [MsJenisTempatTinggalController::class, 'destroy'])->name('ms_jenis_tempat_tinggals.delete');
        });

        // * User (prefix)
        Route::prefix('user')->group(function () {
            route::get('/', [UserController::class, 'index'])->name('users.index');
            route::post('/', [UserController::class, 'store'])->name('users.store');
            route::get('/{id}', [UserController::class, 'show'])->name('users.show');
            route::post('/{id}/delete', [UserController::class, 'destroy'])->name('users.delete');
        });
    });
});