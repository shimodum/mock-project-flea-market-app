<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;

class ProfileController extends Controller
{
    // プロフィール画面（マイページ）の表示
    public function index(Request $request)
    {
        // 現在のユーザーを取得
        $user = Auth::user();

        // クエリパラメータ 'tab' を取得 (デフォルトは 'sell' 出品リスト)
        $tab = $request->query('tab', 'sell');

        // 出品した商品
        $sellItems = Item::where('user_id', $user->id)->get();

        // 購入した商品（削除された商品を除外）
        $buyItems = Purchase::where('user_id', $user->id)
            ->whereHas('item') // item が存在するデータのみ取得
            ->with('item')
            ->get();

        return view('profile.index', [
            'user' => $user,
            'sellItems' => $sellItems,
            'buyItems' => $buyItems,
            'tab' => $tab
        ]);
    }

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
