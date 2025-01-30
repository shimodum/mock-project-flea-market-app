<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $item_id)
    {
        // 1. ユーザーがログインしているか確認
        if (!Auth::check()) {
            return response()->json(['error' => 'ログインが必要です。'], 401);
        }

        // 2. 現在のログインユーザーのIDを取得
        $user_id = Auth::id();

        // 3. 既に「いいね」しているかどうかを確認
        $existingLike = Like::where('user_id', $user_id)->where('item_id', $item_id)->first();

        if ($existingLike) {
            // 4. 既に「いいね」している場合は削除（解除処理）
            $existingLike->delete();
            return response()->json([
                'message' => 'いいねを解除しました',
                'liked' => false
            ]);
        } else {
            // 5. まだ「いいね」していない場合は新規作成
            Like::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
            return response()->json([
                'message' => 'いいねしました',
                'liked' => true
            ]);
        }
    }
}
