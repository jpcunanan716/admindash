<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//User Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
});
route::middleware(['auth', 'user'])->group(function () {
    Route::get('/home', [App\Http\Controllers\UserController::class, 'index'])->name('home');
});

Route::get('/redirect-by-role', function () {
    return RoleRedirectService::redirectByRole();
})->middleware('auth')->name('role.redirect');


// Product Routes
Route::get('/products', function () {
    return view('products');
});

Route::get('/products/create', function () {
    return view ('productform');
});

Route::get('/videos', function () {
    return view ('videos');
});

require __DIR__.'/auth.php';
