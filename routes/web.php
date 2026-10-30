<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FilmController,
    AuthController,
    AdminController,
    AdminFilmController,
    KritikController,
};

// Public Film Routes
Route::get('/', [FilmController::class, 'movieHome'])->name('home');
Route::get('/movies', [FilmController::class, 'movies'])->name('movies');
Route::get('/movies/{film}', [FilmController::class, 'show'])->name('movies.show');
Route::get('/movies/genre/{genre}', [FilmController::class, 'moviesByGenre'])->name('genre');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::post('/comments', [KritikController::class, 'store'])->name('comments.store');
});

// Admin Routes (Only accessible by authenticated users with role='admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Film CRUD
    Route::resource('films', AdminFilmController::class);

    // Comments Management
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');

    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});
