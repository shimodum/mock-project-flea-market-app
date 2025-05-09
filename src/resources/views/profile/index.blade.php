{{-- プロフィール画面（マイページ） --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="profile-header">
        {{-- プロフィール画像 --}}
        <div class="profile-image">
            <img src="{{ $user->profile_image_url }}" alt="プロフィール画像">
        </div>
        
        <h2 class="profile-name">{{ $user->name }}</h2>

        {{-- ★評価表示 --}}
        <div class="rating-stars">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= round($user->average_rating))
                    <img src="{{ asset('images/star-yellow.png') }}" alt="星">
                @else
                    <img src="{{ asset('images/star-gray.png') }}" alt="星">
                @endif
            @endfor
        </div>

        {{-- プロフィール編集ボタン --}}
        <a href="{{ route('profile.edit') }}" class="profile-edit-btn">
            <img src="{{ asset('images/edit-button.png') }}" alt="プロフィールを編集">
        </a>
    </div>

    {{-- タブメニュー --}}
    <div class="tab-menu">
        <a href="{{ route('profile.index', ['tab' => 'sell']) }}" class="{{ $tab === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.index', ['tab' => 'buy']) }}" class="{{ $tab === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
        <a href="{{ route('profile.index', ['tab' => 'transaction']) }}" class="{{ $tab === 'transaction' ? 'active' : '' }}">
            取引中の商品
            @if ($totalUnreadCount > 0)
                <span class="badge">{{ $totalUnreadCount }}</span>
            @endif
        </a>
    </div>

    {{-- 商品リスト --}}
    <div class="product-list">
        {{-- 出品した商品 --}}
        @if ($tab === 'sell')
            @foreach ($sellItems as $item)
                <div class="product-item">
                    <a href="{{ route('items.show', $item->id) }}">
                        <div class="product-image-wrapper">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
                        </div>
                        <p>{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        @endif

        {{-- 購入した商品 --}}
        @if ($tab === 'buy')
            @foreach ($buyItems as $purchase)
                <div class="product-item">
                    <a href="{{ route('items.show', $purchase->item->id) }}">
                        <div class="product-image-wrapper">
                            <img src="{{ asset('storage/' . $purchase->item->image_path) }}" alt="商品画像">
                        </div>
                        <p>{{ $purchase->item->name }}</p>
                    </a>
                </div>
            @endforeach
        @endif

        {{-- 取引中の商品 --}}
        @if ($tab === 'transaction')
            @foreach ($transactions as $transaction)
                <div class="product-item">
                    <a href="{{ route('transactions.show', $transaction->id) }}">
                        <div class="product-image-wrapper">
                            <img src="{{ asset('storage/' . $transaction->item->image_path) }}" alt="商品画像">
                            @if ($transaction->unreadMessagesCount() > 0)
                                <div class="badge">{{ $transaction->unreadMessagesCount() }}</div>
                            @endif
                        </div>
                        <p>{{ $transaction->item->name }}</p>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
