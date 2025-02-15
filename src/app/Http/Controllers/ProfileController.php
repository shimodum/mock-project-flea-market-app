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

        // 購入した商品一覧
        $buyItems = Purchase::where('user_id', $user->id)
        ->whereHas('item') // item が存在するものだけ取得
            ->with('item') // item の情報を取得
            ->get();

        return view('profile.index', compact('user', 'sellItems', 'buyItems', 'tab'));
    }

    // プロフィール設定画面の表示
    public function edit()
    {
        $user = Auth::user(); // ログイン中のユーザー情報を取得
        return view('profile.edit', compact('user')); //
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 画像をアップロードして保存
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'profile_image' => $user->profile_image,
        ]);

        return redirect()->route('profile.edit')->with('success', 'プロフィールが更新されました');
    }

}
