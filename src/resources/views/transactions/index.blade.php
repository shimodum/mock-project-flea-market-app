{{-- 取引中の商品一覧画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>取引中の商品一覧</h2>

    {{-- 取引中の商品がある場合 --}}
    @if ($transactions->isNotEmpty())
        <div class="transaction-list">
            @foreach ($transactions as $transaction)
                <div class="transaction-item">
                    <a href="{{ route('transactions.show', ['transaction' => $transaction->id]) }}">
                        @if ($transaction->item && $transaction->item->image_url)
                            <img src="{{ $transaction->item->image_url }}" alt="{{ $transaction->item->name }}" class="transaction-image">
                        @else
                            <div class="transaction-placeholder">
                                画像がありません
                            </div>
                        @endif
                        <div class="transaction-details">
                            <h3>{{ $transaction->item->name }}</h3>
                            <p>取引状態: {{ $transaction->status }}</p>
                            <p>出品者: {{ $transaction->item->user->name }}</p>
                            <p>購入者: {{ $transaction->buyer->name }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p>現在、取引中の商品はありません。</p>
    @endif
</div>
@endsection
