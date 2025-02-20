<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // 会員登録フォームを表示
    public function showForm()
    {
        return view('auth.register');
    }

    // 会員登録処理
    public function store(RegisterRequest $request)
    {
        // バリデーションが成功したデータを取得
        $validated = $request->validated();

        // パスワードをハッシュ化して保存
        $validated['password'] = Hash::make($validated['password']);

        // 新しいユーザーを作成
        $user = User::create($validated);

        // **ユーザーをログイン状態にする**
        Auth::login($user);

        // メール認証の通知を送る
        $user->sendEmailVerificationNotification();

        // 認証待ちページへリダイレクト
        return redirect()->route('verification.notice');
    }
}
