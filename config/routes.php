<?php

use Core\Router\Route;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\PostController;
use App\Controllers\PostOccurrencesController;

// ROTAS PÚBLICAS
Route::get('/', [HomeController::class, 'index'])->name('home.index');/* 
Route::post('/search', [HomeController::class, 'search'])->name('home.search'); */

Route::get('/posts/index', [PostController::class, 'index'])->name('posts.index');

Route::get('/api/posts/search', [PostController::class, 'search'])->name('api.posts.search');

Route::get('/posts/index', [PostController::class, 'index'])->name('posts.index');

// ROTAS DE AUTENTICACAO
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::middleware('auth')->group(function () {
    // Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

    //create
    Route::get('/posts/new', [PostController::class, 'new'])->name('posts.new');
    Route::post('/posts', [PostController::class, 'create'])->name('posts.create');

    //edit
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

    //deleete
    Route::delete('/', [PostController::class, 'destroy'])->name('posts.destroy');

    //create
    Route::get('/posts/new', [PostController::class, 'new'])->name('posts.new');
    Route::post('/posts', [PostController::class, 'create'])->name('posts.create');

    //show
    Route::get('/posts/{id}/show', [PostController::class, 'show'])->name('posts.show');

    //edit
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

    //delete individual photo
    Route::delete('/posts/{id}/show/{photo_id}', [PostController::class, 'destroyPhoto'])->name('posts.photos.destroy');

    //delete post
    Route::delete('/', [PostController::class, 'destroy'])->name('posts.destroy');

    // Occurrences
    Route::post('/posts/{id}/occurrences', [PostOccurrencesController::class, 'create'])->name('occurrences.create');
    Route::delete('/posts/{post_id}/occurrences/{occurrence_id}', [PostOccurrencesController::class, 'destroy'])->name('occurrences.destroy');


    Route::middleware('admin')->group(function () {
    // ROTAS DO PAINEL ADM
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });
});

Route::get('/{page}', [HomeController::class, 'index'])->name('posts.paginate');
