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

        // 取引中の商品一覧を取得
        $transactions = Transaction::where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhereHas('item', function ($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
        })
        ->where('status', 'negotiating')
        ->with('item')
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

        // メッセージ一覧を取得
        $messages = $transaction->chatMessages()->with('user')->orderBy('created_at', 'asc')->get();

        return view('transactions.show', compact('transaction', 'messages'));
    }
}
