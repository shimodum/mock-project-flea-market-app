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
        $tab = $request->query('tab', 'sell'); // デフォルトは 'sell'

        // 出品した商品を取得（itemsテーブルと関連付け）
        $sellItems = Item::where('user_id', $user->id)->get();

        // 購入した商品を取得（purchasesテーブルと関連付け）
        $buyItems = $user->purchases()->with('item')->get();

        // 取引中の商品 (出品者 or 購入者が自分で、状態が negotiating のもの)
        $transactions = Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->where('status', 'negotiating')
            ->with(['item', 'item.user', 'chatMessages'])
            ->get()
            ->map(function ($transaction) {
                // メソッドを使って未読メッセージの数を取得
                $transaction->unread_messages_count = $transaction->unreadMessagesCount();
                return $transaction;
            });

        // ここで合計未読件数を計算
        $totalUnreadCount = $transactions->sum('unread_messages_count');

        // タブに応じたリストの選択
        $items = [];
        if ($tab === 'sell') {
            $items = $sellItems;
        } elseif ($tab === 'buy') {
            $items = $buyItems;
        } elseif ($tab === 'transaction') {
            $items = $transactions;
        }

        return view('profile.index', [
            'user' => $user,
            'tab' => $tab,
            'items' => $items,
            'totalUnreadCount' => $totalUnreadCount,
            'averageRating' => $user->average_rating
        ]);
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
