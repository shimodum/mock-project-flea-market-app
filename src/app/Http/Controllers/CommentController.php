<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Rules\Password;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $itemId)
    {
        // Fortify の認証を適用（認証ユーザー以外は処理しない）
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'ログインが必要です。']);
        }

        // Fortify のバリデーションルールを適用
        $validated = $request->validated();

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'コメントを追加しました。');
    }
}
