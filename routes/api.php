<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/signup', [AuthController::class, 'signup'])->name('auth.signup');

Route::middleware(['ApiProtectedRoutes'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
    Route::get('/', function () {
        return [];
    })->name('home');
});

