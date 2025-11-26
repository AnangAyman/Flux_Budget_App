<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

// 1. Localization Route (Switch Language)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]); // Store preference in session
    }
    return redirect()->back();
})->name('lang.switch');

// 2. Guest Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Protected Routes (Require Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Resource Controller handles index, create, store, edit, update, destroy
    Route::resource('transactions', TransactionController::class);
});

// Default redirect
Route::get('/', function () {
    return redirect()->route('transactions.index');
});