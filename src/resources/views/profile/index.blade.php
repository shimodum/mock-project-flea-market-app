{{-- プロフィール画面（マイページ） --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="profile-header">
        {{-- プロフィール画像の表示 --}}
        <div class="profile-image">
            <img src="{{ $user->profile_image_url }}" alt="プロフィール画像">
        </div>
        
        <h2 class="profile-name">{{ $user->name }}</h2>

        {{-- プロフィール編集ボタン --}}
        <a href="{{ route('profile.edit') }}" class="profile-edit-btn">プロフィールを編集</a>
    </div>

    {{-- タブで切り替える --}}
    <div class="tab-menu">
        <a href="{{ route('profile.index', ['tab' => 'sell']) }}" class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('profile.index', ['tab' => 'buy']) }}" class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="{{ route('profile.index', ['tab' => 'transaction']) }}" class="{{ $tab === 'transaction' ? 'active' : '' }}">取引中の商品</a>
    </div>

    {{-- 商品リスト --}}
    <div class="product-list">
        @if ($tab === 'sell')
            @if ($sellItems->isEmpty())
                <p>出品した商品はありません。</p>
            @else
                @foreach ($sellItems as $item)
                    <div class="product-item">
                        @if ($item->image_url)
                            <img src="{{ $item->image_url }}" alt="商品画像">
                        @endif
                        <p>{{ $item->name }}</p>
                    </div>
                @endforeach
            @endif

        @elseif ($tab === 'buy')
            @if ($buyItems->isEmpty())
                <p>購入した商品はありません。</p>
            @else
                @foreach ($buyItems as $purchase)
                    @if ($purchase->item && $purchase->item->image_url)
                        <div class="product-item">
                            <img src="{{ $purchase->item->image_url }}" alt="商品画像">
                            <p>{{ $purchase->item->name }}</p>
                        </div>
                    @else
                        <p>商品の情報が見つかりません。</p>
                    @endif
                @endforeach
            @endif

        @elseif ($tab === 'transaction')
            @if ($transactions->isEmpty())
                <p>取引中の商品はありません。</p>
            @else
                @foreach ($transactions as $transaction)
                    <div class="product-item">
                        {{-- 商品画像 --}}
                        @if ($transaction->item->image_url)
                            <img src="{{ $transaction->item->image_url }}" alt="商品画像">
                        @endif
                        <p>{{ $transaction->item->name }}</p>
                        
                        {{-- 出品者と購入者の情報表示 --}}
                        <p>出品者: {{ $transaction->item->user->name }}</p>
                        <p>購入者: {{ $transaction->buyer->name }}</p>

                        {{-- 詳細ページへのリンク --}}
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="detail-link">
                            詳細を確認
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
</div>
@endsection
