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
        @if ($tab === 'transaction')
            @if ($transactions->isEmpty())
                <p>取引中の商品はありません。</p>
            @else
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
        @endif
        <pre>
        未読メッセージ一覧:
        @foreach ($transactions as $transaction)
            商品ID: {{ $transaction->id }}, 未読: {{ $transaction->unreadMessagesCount() }}
        @endforeach
        </pre>
    </div>
</div>
@endsection
