<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use Core\Router\Route;


// Home
Route::get('/', [HomeController::class, 'index'])->name('root');

// Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin.index');
