<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    // 商品購入画面を表示
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $address = $user->postal_code ? 
            "〒 {$user->postal_code} {$user->address} " . ($user->building ? "({$user->building})" : "") 
            : "住所が登録されていません。";

        return view('purchase.create', compact('item', 'address'));
    }

    // 商品購入処理
    public function store(Request $request, $item_id)
    {
        $request->validate(['payment_method' => 'required']);
        $item = Item::findOrFail($item_id);

        if ($item->is_sold) {
            return redirect()->route('items.show', $item_id)->with('error', 'この商品はすでに売り切れています');
        }

        DB::transaction(function () use ($request, $item) {
            // 購入情報を作成
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => $request->payment_method,
            ]);

            // 商品の `is_sold` フラグを更新
            $item->is_sold = true;
            $item->save();
        });

        return redirect()->route('profile.index', ['tab' => 'buy'])->with('success', '商品を購入しました');
    }


    // Stripe 決済処理（応用機能用に残す）
    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::findOrFail($request->item_id);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/purchase/success'),
            'cancel_url' => url('/purchase/cancel'),
        ]);

        return response()->json(['url' => $session->url]);
    }
}
