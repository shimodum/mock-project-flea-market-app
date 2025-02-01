@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h2>住所の変更</h2>
    <form action="{{ route('purchase.updateAddress', ['item_id' => $item->id]) }}" method="POST">
        @csrf
        <label for="postal_code">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code" required>

        <label for="address">住所</label>
        <input type="text" name="address" id="address" required>

        <label for="building">建物名</label>
        <input type="text" name="building" id="building">

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
