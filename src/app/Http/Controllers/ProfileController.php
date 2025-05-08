<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Transaction;

class ProfileController extends Controller
{
    // プロフィール画面（マイページ）の表示
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'sell');

        // 出品した商品
        $sellItems = $user->items;

        // 購入した商品
        $buyItems = $user->purchases()->with('item')->get();

        // 取引中の商品 (出品者 or 購入者が自分で、状態が negotiating のもの)
        $transactions = Transaction::where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhereHas('item', function ($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
        })
        ->where('status', 'negotiating')
        ->with(['item', 'item.user'])
        ->get();

        return view('profile.index', compact('user', 'tab', 'sellItems', 'buyItems', 'transactions'));
    }

    // プロフィール設定画面の表示
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
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

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'address_building' => $request->address_building,
            'profile_image' => $user->profile_image,
        ]);

        return redirect()->route('profile.edit')->with('success', 'プロフィールが更新されました');
    }
}
