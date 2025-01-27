<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query()
            ->where('is_sold', false) // 売り切れていない商品
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id()); // 自分が出品した商品を除外
            });

        // 商品名での部分一致検索
        if ($request->has('search') && !empty($request->input('search'))) {
            $query->where('name', 'LIKE', '%' . $request->input('search') . '%');
        }

        $items = $query->get();

        return view('items.index', [
            'items' => $items,
            'search' => $request->input('search') // 検索キーワードをビューに渡す
        ]);
    }
}
