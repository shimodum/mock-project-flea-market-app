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

        <div class="profile-info">
            {{-- 名前の表示 --}}
            <h2 class="profile-name">{{ $user->name }}</h2>

            {{-- ★評価の表示 --}}
            <div class="rating-stars">
                <img src="{{ asset('images/star_filled.png') }}" alt="star">
                <img src="{{ asset('images/star_filled.png') }}" alt="star">
                <img src="{{ asset('images/star_filled.png') }}" alt="star">
                <img src="{{ asset('images/star_empty.png') }}" alt="star">
                <img src="{{ asset('images/star_empty.png') }}" alt="star">
            </div>
        </div>

        {{-- プロフィール編集ボタン --}}
        <a href="{{ route('profile.edit') }}" class="profile-edit-btn">プロフィールを編集</a>
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
                <div class="badge">
                    <img src="{{ asset('storage/Ellipse 6@2x.png') }}" alt="バッジ">
                    <span class="badge-count">{{ $totalUnreadCount }}</span>
                </div>
            @endif
        </a>
    </div>

    {{-- 商品リスト --}}
    <div class="product-list">
        @foreach ($transactions as $transaction)
            <div class="product-item">
                <a href="{{ route('transactions.show', $transaction->id) }}">
                    <div class="product-image-wrapper">
                        <img src="{{ asset('storage/' . $transaction->item->image_path) }}" alt="商品画像">
                        @if ($transaction->unreadMessagesCount() > 0)
                            <div class="badge">
                                <img src="{{ asset('storage/Ellipse 6.png') }}" alt="未読バッジ">
                                <span class="badge-count">{{ $transaction->unreadMessagesCount() }}</span>
                            </div>
                        @endif
                    </div>
                    <p>{{ $transaction->item->name }}</p>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
