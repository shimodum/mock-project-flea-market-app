<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

// 会員登録フォーム表示
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');

// 会員登録処理
Route::post('/register', [RegisterController::class, 'store'])->name('register');
