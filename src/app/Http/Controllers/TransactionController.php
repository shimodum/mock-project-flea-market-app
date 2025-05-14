<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ChatMessage;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompleteMail;

class TransactionController extends Controller
{
    /**
     * 取引中商品の一覧表示
     *
     * ユーザーが取引中の商品の一覧を取得し、最新メッセージの時間でソート
     */
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
        ->orderByDesc(
            ChatMessage::select('created_at')
                ->whereColumn('transaction_id', 'transactions.id')
                ->latest()
                ->take(1)
        )
        ->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * 取引中商品の詳細表示
     *
     * 選択した取引の詳細を表示し、未読メッセージの更新も行う
     */
    public function show($id)
    {
        $user = Auth::user();

        $transaction = Transaction::with([
                'item',
                'item.user',
                'chatMessages'
            ])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->firstOrFail();

        // 出品者が評価済みかどうかを確認
        $transaction->seller_rated = $transaction->seller_rated ?? false;

        $messages = $transaction->chatMessages()->with('user')->orderBy('created_at', 'asc')->get();
        $sidebarTransactions = Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->with('item')
            ->get();

        return view('transactions.show', compact('transaction', 'messages', 'sidebarTransactions'));
    }

    /*
     * 取引評価の保存処理など
     */
    public function rate(Request $request, $id)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);
    
        // 購入者が評価した場合
        if ($transaction->buyer_id === $user->id) {
            $evaluatorId = $transaction->buyer_id;
            $evaluateeId = $transaction->item->user_id;
    
            if ($transaction->buyer_rated) {
                return response()->json([
                    'success' => false,
                    'message' => '購入者の評価は既に完了しています。'
                ]);
            }
    
            $transaction->buyer_rated = true;
    
            // 購入者が評価した後、出品者の評価が完了している場合にステータスを更新
            if ($transaction->seller_rated) {
                $transaction->status = 'completed';
                
                // 出品者へメール送信
                \Mail::to($transaction->item->user->email)->send(new \App\Mail\TransactionCompleteMail($transaction));
            }
    
        // 出品者が評価した場合
        } else if ($transaction->item->user_id === $user->id) {
            $evaluatorId = $transaction->item->user_id;
            $evaluateeId = $transaction->buyer_id;
    
            if ($transaction->seller_rated) {
                return response()->json([
                    'success' => false,
                    'message' => '出品者の評価は既に完了しています。'
                ]);
            }
    
            $transaction->seller_rated = true;
    
            // 出品者が評価した後、購入者の評価が完了している場合にステータスを更新
            if ($transaction->buyer_rated) {
                $transaction->status = 'completed';
    
                // 購入者へメール送信
                \Mail::to($transaction->buyer->email)->send(new \App\Mail\TransactionCompleteMail($transaction));
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => '権限がありません。'
            ], 403);
        }
    
        Evaluation::create([
            'transaction_id' => $transaction->id,
            'evaluator_id' => $evaluatorId,
            'evaluatee_id' => $evaluateeId,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment')
        ]);
    
        $transaction->save();
    
        return response()->json([
            'success' => true,
            'message' => __('評価が完了しました。'),
            'redirect' => route('items.index')
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
}
