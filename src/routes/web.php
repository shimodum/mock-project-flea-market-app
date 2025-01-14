<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;


// 会員登録フォーム表示
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
// 会員登録処理
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// プロフィール設定（編集）画面表示
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// プロフィール更新処理
Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');