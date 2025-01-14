<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // 会員登録フォームを表示する
    public function showForm()
    {
        return view('auth.register');
    }

    // 会員登録処理
    public function store(RegisterRequest $request)
    {
        // バリデーションが成功した後、ユーザーを作成
        $validated = $request->validated();

        // パスワードをハッシュ化
        $validated['password'] = Hash::make($validated['password']);

        // 新しいユーザーを作成（パスワード確認フィールドは除外）
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // 登録後にログイン
        Auth::login($user);

        // プロフィール設定画面へリダイレクト
        return redirect()->route('profile.edit');
    }
}
