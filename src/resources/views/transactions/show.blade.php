{{-- 取引チャット画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions.css') }}">
@endsection

@section('content')
<div class="transaction-wrapper">
    {{-- サイドバーの読み込み --}}
    @include('transactions.sidebar', ['sidebarTransactions' => $sidebarTransactions, 'transaction' => $transaction])

    <div class="transaction-main">
        <div class="transaction-header">
            {{-- 商品画像 --}}
            <img src="{{ $transaction->item->image_url }}" alt="{{ $transaction->item->name }}" class="transaction-item-image">
            
            <div class="transaction-info">
                <h3>{{ $transaction->item->name }}</h3>
                <p>出品者: {{ $transaction->item->user->name }}</p>
                <p>購入者: {{ $transaction->buyer->name }}</p>
                <p>ステータス: {{ $transaction->status }}</p>
            </div>
        </div>

        <button id="completeTransactionButton">取引を完了する</button>

        {{-- 評価モーダル --}}
        <div id="evaluationModal" class="modal">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h2>取引が完了しました。</h2>
                <p>今回の取引相手はどうでしたか？</p>

                {{-- 星評価 --}}
                <div class="stars">
                    <img class="star" data-value="1" src="{{ asset('images/star_empty.png') }}">
                    <img class="star" data-value="2" src="{{ asset('images/star_empty.png') }}">
                    <img class="star" data-value="3" src="{{ asset('images/star_empty.png') }}">
                    <img class="star" data-value="4" src="{{ asset('images/star_empty.png') }}">
                    <img class="star" data-value="5" src="{{ asset('images/star_empty.png') }}">
                </div>

                <form id="ratingForm" method="POST" action="{{ route('transactions.rate', $transaction->id) }}">
                    @csrf
                    <input type="hidden" id="ratingValue" name="rating" value="0">
                    <button type="submit" class="submit-button">送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- モーダルの読み込み --}}
@section('js')
    <script src="{{ asset('js/transaction.js') }}"></script>
@endsection
