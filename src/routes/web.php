<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// 会員登録関連
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// ログイン/ログアウト関連
Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 認証不要なルート
Route::get('/', [ItemController::class, 'index'])->name('items.index'); // 商品一覧
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show'); // 商品詳細

// メール認証のルート
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify'); // 認証待ち画面
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.resend');
});

// いいね機能(auth ミドルウェアでログインユーザーのみが使用可能)
Route::middleware(['auth'])->group(function () {
    Route::post('/like/{item_id}', [LikeController::class, 'toggleLike'])->name('like.toggle');
});

// コメント機能(auth ミドルウェアでログインユーザーのみがコメント投稿できるよう制御)
Route::middleware(['auth'])->group(function () {
    Route::post('/comments/{item_id}', [CommentController::class, 'store'])->name('comments.store'); // 商品へのコメント投稿
});

// 認証が必要なルートをグループ化
Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 商品購入関連
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.editAddress');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');

    // Stripe の決済処理関連
    Route::post('/stripe/checkout', [PurchaseController::class, 'checkout'])->name('stripe.checkout');

    // 商品出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});
