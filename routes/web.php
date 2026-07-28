<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

// frontend routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/contact', 'frontend.contact')->name('contact');
Route::view('/about', 'frontend.about')->name('about');
// backend dashboard
Route::get('/backend', function () {
    Gate::authorize('view-backend-dashboard');

    return view('backend.dashboard');
})->middleware(['auth', 'verified', 'active'])->name('backend.dashboard');

// backend routes
Route::middleware(['auth', 'verified', 'active'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {

        Route::resource('users', UserController::class);
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])
            ->name('users.forceDelete');

        Route::resource('roles', RoleController::class);
        Route::patch('roles/{id}/restore', [RoleController::class, 'restore'])
            ->name('roles.restore');
        Route::delete('roles/{id}/force-delete', [RoleController::class, 'forceDelete'])
            ->name('roles.forceDelete');

        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{id}/restore', [CategoryController::class, 'restore'])
            ->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])
            ->name('categories.forceDelete');

        Route::resource('posts', PostController::class);
        Route::patch('posts/{id}/restore', [PostController::class, 'restore'])
            ->name('posts.restore');
        Route::delete('posts/{id}/force-delete', [PostController::class, 'forceDelete'])
            ->name('posts.forceDelete');

        Route::resource('media', MediaController::class);
    });

require __DIR__.'/settings.php';
