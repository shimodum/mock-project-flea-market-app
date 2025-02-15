<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    //商品一覧画面（トップページ）
    public function index(Request $request)
    {
        $query = Item::query()
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id()); // 自分が出品した商品を除外
            });

        // マイリスト（いいねした商品のみ）を表示する場合
        if ($request->query('tab') === 'mylist' && auth()->check()) {
            $likedItemIds = auth()->user()->likes()->pluck('item_id');
            $query->whereIn('id', $likedItemIds);
        }

        // 商品名での部分一致検索
        if ($request->has('query') && !empty($request->input('query'))) {
            $query->where('name', 'LIKE', '%' . $request->input('query') . '%');
        }

        $items = $query->get();

        return view('items.index', [
            'items' => $items,
            'query' => $request->input('query'),
            'tab' => $request->query('tab') // 現在のタブ状態をビューに渡す
        ]);
    }


    //商品詳細画面表示
    public function show($id)
    {
        $item = Item::with(['categories', 'comments.user'])->findOrFail($id);
        return view('items.show', compact('item'));
    }

    //出品画面表示
    public function create()
    {
        return view('items.create');
    }

    // 出品処理
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:1',
            'condition' => 'required|integer|between:1,4',
            'brand' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array',  // カテゴリの選択が必須
        ]);

        // 商品画像を保存
        $imagePath = $request->file('image')->store('item_images', 'public');

        // 商品を作成
        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'brand' => $request->brand,
            'image_path' => $imagePath,
            'is_sold' => false,
        ]);

        // カテゴリを関連付ける
        $categories = Category::whereIn('name', $request->categories)->pluck('id');  // カテゴリ名からIDを取得
        $item->categories()->attach($categories);

        return redirect()->route('items.index');
    }

}
