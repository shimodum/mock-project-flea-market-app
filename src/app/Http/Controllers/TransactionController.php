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

        // 未読メッセージを既読にする処理
        $unreadMessages = ChatMessage::where('transaction_id', $transaction->id)
            ->where('is_read', false)
            ->where('user_id', '!=', Auth::id()) // 自分のメッセージはスキップ
            ->get();

        if ($unreadMessages->isNotEmpty()) {
            foreach ($unreadMessages as $message) {
                $message->is_read = true;
                $message->save();
            }
        }

        // メッセージ一覧を取得
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

        return view('transactions.show', compact('transaction', 'messages', 'sidebarTransactions'));
    }

}
