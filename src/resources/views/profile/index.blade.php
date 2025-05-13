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
            @if ($user->average_rating)
                <div class="rating-stars">
                    @for ($i = 0; $i < 5; $i++)
                        @if ($i < $user->average_rating)
                            <img src="{{ asset('images/star_filled.png') }}" alt="star">
                        @else
                            <img src="{{ asset('images/star_empty.png') }}" alt="star">
                        @endif
                    @endfor
                </div>
            @else
                <p>評価はまだありません。</p>
            @endif
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
                <span class="badge">{{ $totalUnreadCount }}</span>
            @endif
        </a>
    </div>

    {{-- 商品リスト --}}
    <div class="product-list">
        @foreach ($items as $item)
            <div class="product-item">
                <a href="{{ route('transactions.show', $item->id) }}">
                    <div class="product-image-wrapper">
                        @if ($tab === 'transaction')
                            <img src="{{ asset('storage/' . $item->item->image_path) }}" alt="商品画像">
                        @else
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
                        @endif

                        {{-- 取引中商品のみバッジを表示 --}}
                        @if ($tab === 'transaction' && $item->unread_messages_count > 0)
                            <div class="badge">{{ $item->unread_messages_count }}</div>
                        @endif
                    </div>
                    <p>
                        @if ($tab === 'transaction')
                            {{ $item->item->name }}
                        @else
                            {{ $item->name }}
                        @endif
                    </p>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
