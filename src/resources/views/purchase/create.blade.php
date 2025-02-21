{{-- 商品購入画面 --}}
@extends('layouts.app')

@section('content')
<div class="purchase-container">
    <div class="purchase-item">
        <div class="purchase-image">
            <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}" class="responsive-img">
        </div>
        <div class="purchase-info">
            <h2>{{ $item->name }}</h2>
            @if($item->is_sold)
                <span class="sold-label">Sold</span>
            @endif
            <p class="price">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <div class="purchase-summary">
        <h3>注文内容</h3>
        <table class="summary-table">
            <tr>
                <th>商品代金</th>
                <td>¥{{ number_format($item->price) }}</td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td id="payment-summary">未選択</td>
            </tr>
        </table>
    </div>

    @if(!$item->is_sold)
    <div class="purchase-form">
        <form id="purchase-form" method="POST">
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

            <button type="button" id="purchase-button" class="purchase-button" data-item-id="{{ $item->id }}">購入する</button>
        </form>
    </div>
    @else
        <p class="alert alert-info">この商品はすでに購入されています。</p>
    @endif
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/purchase.js') }}" defer></script>
@endsection
