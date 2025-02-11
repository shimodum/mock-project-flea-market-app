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
            'building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase.editAddress', ['item_id' => $item_id])->with('success', '住所が更新されました');
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
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
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
