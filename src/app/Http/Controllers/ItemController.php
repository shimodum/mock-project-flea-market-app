<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

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
        if ($request->has('query') && !empty($request->input('query'))) {
            $query->where('name', 'LIKE', '%' . $request->input('query') . '%');
        }

        $items = $query->get();

        return view('items.index', [
            'items' => $items,
            'query' => $request->input('query') // 検索キーワードをビューに渡す
        ]);    
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('items.show', compact('item'));
    }

    //出品画面表示
    public function create()
    {
        return view('items.create');
    }

    //出品処理
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|integer|min:1',
        'condition' => 'required|integer|between:1,4',
        'brand' => 'nullable|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 画像を storage/app/public/item_images に保存
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('item_images', 'public');
    } else {
        $imagePath = null;
    }

    Item::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'condition' => $request->condition,
        'brand' => $request->brand,
        'image_path' => $imagePath, // ここに保存したパスを設定
        'is_sold' => false,
    ]);

    return redirect()->route('items.index');
}

}
