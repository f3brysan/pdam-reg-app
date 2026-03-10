<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MsJenisMeteranController;
use App\Http\Controllers\MsJenisTempatTinggalController;
use App\Http\Controllers\MsPekerjaanController;
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
        route::get('/master/pekerjaan', [MsPekerjaanController::class, 'index'])->name('ms_pekerjaans.index');

        route::get('/master/jenis-meteran', [MsJenisMeteranController::class, 'index'])->name('ms_jenis_meterans.index');
        
        route::get('/master/jenis-tempat-tinggal', [MsJenisTempatTinggalController::class, 'index'])->name('ms_jenis_tempat_tinggals.index');
    });    
});