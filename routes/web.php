<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Frontend\AnimalController as FrontendAnimalController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

// frontend routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/koty', [FrontendAnimalController::class, 'index'])->name('frontend.animals.index');
Route::get('/koty/{animal}', [FrontendAnimalController::class, 'show'])->name('frontend.animals.show');
Route::get('/blog', [FrontendBlogController::class, 'index'])->name('frontend.blog.index');
Route::get('/blog/{post}', [FrontendBlogController::class, 'show'])->name('frontend.blog.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('frontend.contact.store');
Route::view('/about', 'frontend.about')->name('about');
Route::view('/o-hodowli', 'frontend.cattery')->name('cattery');
Route::view('/polityka-prywatnosci', 'frontend.privacy')->name('privacy');
Route::view('/regulamin', 'frontend.terms')->name('terms');
// backend dashboard
Route::get('/backend', function () {
    Gate::authorize('view-backend-dashboard');

    return view('backend.dashboard');
})->middleware(['auth', 'verified', 'active'])->name('backend.dashboard');

Route::get('/dashboard', function () {
    Gate::authorize('view-backend-dashboard');

    return view('backend.dashboard');
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

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

        Route::resource('animals', \App\Http\Controllers\Backend\AnimalController::class);
        Route::patch('animals/{id}/restore', [\App\Http\Controllers\Backend\AnimalController::class, 'restore'])
            ->name('animals.restore');
        Route::delete('animals/{id}/force-delete', [\App\Http\Controllers\Backend\AnimalController::class, 'forceDelete'])
            ->name('animals.forceDelete');

        Route::get('media-api', [MediaController::class, 'api'])->name('media.api');
        Route::resource('media', MediaController::class);
    });

require __DIR__.'/settings.php';
