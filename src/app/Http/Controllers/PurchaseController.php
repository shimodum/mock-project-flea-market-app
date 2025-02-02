<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入画面を表示
    public function create($item_id)
    {
        // 指定されたIDの商品を取得（存在しない場合は404エラー）
        $item = Item::findOrFail($item_id);

        // ログインユーザーの住所情報を取得
        $user = Auth::user();
        $address = $user->postal_code ? 
            "〒 {$user->postal_code} {$user->address} " . ($user->building ? "({$user->building})" : "") 
            : "住所が登録されていません。";

        return view('purchase.create', compact('item', 'address'));
    }

    // 商品購入処理
    public function store(Request $request, $item_id)
    {
        // 支払い方法の入力が必須であることをバリデーション
        $request->validate([
            'payment_method' => 'required',
        ]);

        // 購入情報をデータベースに保存
        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'payment_method' => $request->payment_method,
        ]);

        // Stripe の決済ページにリダイレクト
        return response()->json([
            'url' => route('stripe.checkout', ['item_id' => $item_id])
        ]);
    }

    // Stripe 決済処理
    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::findOrFail($request->item_id);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
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


    // 送付先住所変更画面を表示
    public function editAddress($item_id)
    {
        // 指定されたIDの商品を取得（存在しない場合は404エラー）
        $item = Item::findOrFail($item_id);
        return view('purchase.address', compact('item'));
    }

    // 送付先住所を更新
    public function updateAddress(Request $request, $item_id)
    {
        // 住所情報のバリデーション（郵便番号・住所は必須、建物名は任意）
        $request->validate([
            'postal_code' => 'required|max:8',
            'address' => 'required|max:255',
            'building' => 'nullable|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase.create', ['item_id' => $item_id])->with('success', '住所が更新されました');
    }
}
