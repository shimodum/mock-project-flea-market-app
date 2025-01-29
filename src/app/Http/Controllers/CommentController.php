<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Rules\Password;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $validated = $request->validated(); // バリデーション適用

         //コメント作成
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'コメントを追加しました。');
    }

}
