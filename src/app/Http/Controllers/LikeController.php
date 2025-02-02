<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $item_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'ログインが必要です。'], 401);
        }

        $user_id = Auth::id();
        $existingLike = Like::where('user_id', $user_id)->where('item_id', $item_id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $likeCount = Like::where('item_id', $item_id)->count(); // いいねの合計を取得
            return response()->json([
                'message' => 'いいねを解除しました',
                'liked' => false,
                'likeCount' => $likeCount
            ]);
        } else {
            Like::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
            $likeCount = Like::where('item_id', $item_id)->count(); // いいねの合計を取得
            return response()->json([
                'message' => 'いいねしました',
                'liked' => true,
                'likeCount' => $likeCount
            ]);
        }
    }
}