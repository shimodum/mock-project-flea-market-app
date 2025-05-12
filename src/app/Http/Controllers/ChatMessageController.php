<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatMessageRequest;
use App\Models\ChatMessage;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatMessageController extends Controller
{
    // 取引チャット画面の表示
    public function index($transactionId)
    {
        $transaction = Transaction::with(['item', 'buyer', 'seller', 'messages'])->findOrFail($transactionId);

        // ログインユーザーが購入者か出品者か確認
        if ($transaction->buyer_id === Auth::id()) {
            $role = 'buyer';
        } elseif ($transaction->item->user_id === Auth::id()) {
            $role = 'seller';
        } else {
            abort(403, 'アクセス権限がありません');
        }

        return view('transactions.show', compact('transaction', 'role'));
    }

    // メッセージの送信処理
    public function store(ChatMessageRequest $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // ログインユーザーが購入者か出品者か確認
        if ($transaction->buyer_id !== Auth::id() && $transaction->item->user_id !== Auth::id()) {
            abort(403, 'アクセス権限がありません');
        }

        $messageData = [
            'transaction_id' => $transactionId,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ];

        // 画像がある場合の処理
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
            $messageData['image'] = $imagePath;
        }

        ChatMessage::create($messageData);

        return redirect()->route('transactions.show', ['id' => $transactionId])->with('success', 'メッセージを送信しました。');
    }

    // メッセージの更新処理
    public function update(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);

        if ($message->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => '編集権限がありません。'], 403);
        }

        $message->message = $request->message;
        $message->save();

        return response()->json(['success' => true, 'message' => 'メッセージを更新しました。']);
    }

    // メッセージの削除処理
    public function delete($id)
    {
        $message = ChatMessage::findOrFail($id);

        if ($message->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => '削除権限がありません。'], 403);
        }

        if ($message->image_path && Storage::disk('public')->exists($message->image_path)) {
            Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'メッセージを削除しました。']);
    }
}
