<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィール設定画面の表示
    public function edit()
    {
        return view('profile.edit');
    }

    // プロフィール更新処理
    public function update(Request $request)
    {
        // 現在のユーザーを取得
        $user = Auth::user();

        // バリデーション（必要なら追加）
        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:8',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        // ユーザー情報を更新
        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // 更新後に商品一覧画面（トップページ）へリダイレクト
        return redirect()->route('items.index')->with('success', 'プロフィールが更新されました');
    }
}
