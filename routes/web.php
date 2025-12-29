<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InviteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/download', [HomeController::class, 'download'])
        ->name('dashboard.download');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
    });

    Route::prefix('urls')->group(function () {
        Route::get('/', [UrlController::class, 'index'])->name('urls.index');
        Route::get('/create', [UrlController::class, 'create'])->name('urls.create');
        Route::post('/', [UrlController::class, 'store'])->name('urls.store');
    });

   Route::get('/invite', [InviteController::class, 'create'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'send'])->name('invite.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/invite/accept/{token}', [InviteController::class, 'acceptForm']);
Route::post('/invite/accept/{token}', [InviteController::class, 'accept']);

Route::get('/s/{code}', [UrlController::class, 'resolve'])
    ->name('short.resolve');

require __DIR__.'/auth.php';
