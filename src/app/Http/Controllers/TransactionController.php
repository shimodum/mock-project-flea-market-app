<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 取引中商品の一覧表示
    public function index()
    {
        $user = Auth::user();

        // 取引中の商品一覧を取得（最新メッセージの時間でソート）
        $transactions = Transaction::where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhereHas('item', function ($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
        })
        ->where('status', 'negotiating')
        ->with(['item', 'item.user', 'chatMessages'])
        ->get();

        return view('transactions.index', compact('transactions'));
    }

    // 取引中商品の詳細表示
    public function show($id)
    {
        $user = Auth::user();

        // 取引の詳細を取得
        $transaction = Transaction::with(['item', 'item.user', 'chatMessages'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->firstOrFail();

        // 更新処理
        $updated = $transaction->chatMessages()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // 更新後の確認
        \Log::info('After Update - Unread Messages:', $transaction->chatMessages()->where('is_read', false)->pluck('id')->toArray());
        \Log::info("Updated unread messages: {$updated}");

        // 再度データを取得して反映
        $messages = $transaction->chatMessages()->with('user')->orderBy('created_at', 'asc')->get();

        // サイドバーの取引一覧を追加
        $sidebarTransactions = Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->where('status', 'negotiating')
            ->with('item')
            ->get();

        // ここで未読件数の確認
        foreach ($sidebarTransactions as $sidebarTransaction) {
            
        }

        return view('transactions.show', compact('transaction', 'messages', 'sidebarTransactions'));
    }


}
