<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ログインフォームを表示
    public function showForm()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // 認証情報を使ってログインを試みる
        if (Auth::attempt($credentials)) {
            // 認証成功
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    }

    // ログアウト処理
    public function logout()
    {
        Auth::logout(); // ユーザーをログアウト
        request()->session()->invalidate(); // セッションを無効化
        request()->session()->regenerateToken(); // CSRFトークンを再生成

        return redirect()->route('login.form'); // ログイン画面へリダイレクト
    }
}
