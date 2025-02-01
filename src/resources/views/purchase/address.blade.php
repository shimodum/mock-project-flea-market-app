{{-- 送付先住所変更画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h2 class="page-title">住所の変更</h2>
    <form action="{{ route('purchase.updateAddress', ['item_id' => $item->id]) }}" method="POST" class="address-form">
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" required>
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" required>
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building">
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
