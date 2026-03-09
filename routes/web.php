<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


route::get('/login', [AuthController::class, 'login'])->name('login');
route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
route::post('/logout', [AuthController::class, 'logout'])->name('logout');
route::get('/register', [AuthController::class, 'register'])->name('register');
route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');