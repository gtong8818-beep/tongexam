<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [TweetController::class, 'index'])->name('home');
    // Ensure home is reachable via /home as well (some places expect named route 'home')
    Route::get('/home', [TweetController::class, 'index'])->name('home');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::get('/tweets/{tweet}/edit', [TweetController::class, 'edit'])->name('tweets.edit');
    Route::put('/tweets/{tweet}', [TweetController::class, 'update'])->name('tweets.update');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');
    
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('tweets.like');
    
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect to login for root if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');
