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

    if (Auth::attempt($credentials)) {
        // ユーザーが認証済みか確認
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login.form')->with('error', 'メール認証を完了してください。');
        }

        // 認証成功
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'メールアドレスまたはパスワードが違います。',
    ]);
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
