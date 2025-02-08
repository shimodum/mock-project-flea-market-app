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
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');

        // 出品した商品一覧
        $sellItems = Item::where('user_id', $user->id)->get();

        // 購入した商品一覧（削除された商品を除外）
        $buyItems = Purchase::where('user_id', $user->id)
            ->whereHas('item')
            ->with('item')
            ->get();

        return view('profile.index', compact('user', 'sellItems', 'buyItems', 'tab'));
    }

    // プロフィール設定画面の表示
    public function edit()
    {
        return view('profile.edit');
    }

    // プロフィール更新処理
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:8',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('items.index')->with('success', 'プロフィールが更新されました');
    }
}
