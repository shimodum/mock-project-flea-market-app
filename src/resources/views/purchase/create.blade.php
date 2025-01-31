@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-item">
        <div class="purchase-image">
            <img src="{{ $item->image_path }}" alt="{{ $item->name }}">
        </div>
        <div class="purchase-info">
            <h2>{{ $item->name }}</h2>
            <p>¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <div class="purchase-form">
        <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            <label for="payment_method">支払い方法</label>
            <select name="payment_method" id="payment_method">
                <option value="コンビニ支払い">コンビニ支払い</option>
                <option value="カード支払い">カード支払い</option>
            </select>

            <div class="address-section">
                <h3>配送先</h3>
                <p>〒 XXX-YYYY</p>
                <p>ここには住所と建物が入ります</p>
                <a href="{{ route('purchase.editAddress', ['item_id' => $item->id]) }}">変更する</a>
            </div>

            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>
</div>
@endsection
