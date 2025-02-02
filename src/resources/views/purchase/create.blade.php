{{-- 商品購入画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-item">
        <div class="purchase-image">
            <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
        </div>
        <div class="purchase-info">
            <h2>{{ $item->name }}</h2>
            <p class="price">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <div class="purchase-form">
        <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            <div class="payment-method">
                <label for="payment_method" class="bold-label">支払い方法</label>
                <select name="payment_method" id="payment_method">
                    <option value="" disabled selected>選択してください</option>
                    <option value="コンビニ支払い">コンビニ支払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>
            </div>

            <div class="shipping-info">
                <h3>配送先</h3>
                <p>{{ $address }}</p>
                <a href="{{ route('purchase.editAddress', ['item_id' => $item->id]) }}" class="change-address">変更する</a>
            </div>

            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>
</div>
@endsection
