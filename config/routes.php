<?php
use Core\Router\Route; 

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// ROTAS PÚBLICAS

Route::get('/', [HomeController::class, 'index'])->name('home.index');

// ROTAS DE AUTENTICAÇÃO

// Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); 
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

// ROTAS DO PAINEL ADM
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
