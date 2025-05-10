<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ChatMessage;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

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

        // 更新処理：未読メッセージの既読化
        $updated = $transaction->chatMessages()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // ログで確認
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

        return view('transactions.show', compact('transaction', 'messages', 'sidebarTransactions'));
    }

    /**
     * 取引評価の保存処理
     * 
     * モーダルで評価された星の数を保存し、取引ステータスを「completed」に変更
     */
    public function rate(Request $request, $id)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);

        // 既に評価済みの場合はリダイレクト
        $existingEvaluation = Evaluation::where('transaction_id', $id)
            ->where('evaluator_id', $user->id)
            ->first();

        if ($existingEvaluation) {
            return redirect()->route('items.index')->with('message', '既に評価済みです。');
        }

        // 購入者が評価した場合
        if ($transaction->buyer_id === $user->id) {
            $evaluatorId = $transaction->buyer_id;
            $evaluateeId = $transaction->item->user_id;
        } 
        // 出品者が評価した場合
        else if ($transaction->item->user_id === $user->id) {
            $evaluatorId = $transaction->item->user_id;
            $evaluateeId = $transaction->buyer_id;
        } 
        else {
            abort(403, '権限がありません');
        }

        // 評価を保存
        Evaluation::create([
            'transaction_id' => $transaction->id,
            'evaluator_id' => $evaluatorId,
            'evaluatee_id' => $evaluateeId,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment')
        ]);

        // 取引ステータスを完了に更新
        $transaction->status = 'completed';
        $transaction->save();

        // 商品一覧へリダイレクト
        return redirect()->route('items.index')->with('message', '評価が完了しました。');
    }


}
