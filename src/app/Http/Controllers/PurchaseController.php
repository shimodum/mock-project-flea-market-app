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
            "〒 {$user->postal_code} {$user->address} " . ($user->address_building ? "({$user->address_building})" : "") 
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

        try {
            DB::transaction(function () use ($request, $item) {
                Purchase::create([
                    'user_id' => Auth::id(),
                    'item_id' => $item->id,
                    'payment_method' => $request->payment_method,
                    'shipping_address' => Auth::user()->postal_code . ' ' . Auth::user()->address . ' ' . (Auth::user()->building ?? ''),
                ]);

                $item->update(['is_sold' => true]);

                \Log::info('PurchaseController@store called');
            });

            return redirect()->route('items.show', ['item_id' => $item_id])->with('success', '商品を購入しました');
        } catch (\Exception $e) {
            return back()->with('error', '購入処理中にエラーが発生しました。もう一度お試しください。');
        }
    }

    // 送付先住所変更画面を表示
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase.address', compact('item', 'user'));
    }

    // 送付先住所を更新する処理
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'address_building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'address_building' => $request->address_building,
        ]);

        // 購入情報を更新する（該当商品が購入済みである場合のみ）
        $purchase = Purchase::where('user_id', $user->id)->where('item_id', $item_id)->first();
        if ($purchase) {
            $purchase->update([
                'shipping_address' => "{$request->postal_code} {$request->address} " .
                                    ($request->address_building ? "({$request->address_building})" : "")
            ]);
        }

        return redirect()->route('purchase.editAddress', ['item_id' => $item_id])->with('success', '住所が更新されました');
    }


    // Stripe 決済処理
    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::findOrFail($request->item_id);
        $paymentMethod = $request->payment_method;  // 支払い方法を取得

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/purchase/success/' . $item->id . '/?payment_method=' . $paymentMethod),
            'cancel_url' => url('/purchase/cancel'),
        ]);

        return response()->json(['url' => $session->url]);
    }

    // 成功画面処理
    public function success($item_id, Request $request)
    {
        $item = Item::findOrFail($item_id);

        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'shipping_address' => Auth::user()->postal_code . ' '
                . Auth::user()->address . ' ' . (Auth::user()->building ?? ''),
        ]);

        if (!$item->is_sold) {
            $item->update(['is_sold' => true]);
        }
        return view('purchase.success', compact('item', 'purchase'));
    }

    // キャンセル画面処理
    public function cancel()
    {
        return view('purchase.cancel');
    }
}
