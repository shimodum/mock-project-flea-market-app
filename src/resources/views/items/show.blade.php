@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="item-detail">
    {{-- 商品情報 --}}
    <div class="item-detail-header">
        <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="item-image">
        <div class="item-info">
            <h1>{{ $item->name }}</h1>
            <p>ブランド名: {{ $item->brand ?? '不明' }}</p>
            <p>¥{{ number_format($item->price) }} (税込)</p>
            <div class="item-actions">
                <button class="like-button">☆ {{ $item->likes_count ?? 0 }}</button>
                <button class="comment-button">💬 {{ $item->comments_count ?? 0 }}</button>
            </div>
            <a href="/purchase/{{ $item->id }}" class="purchase-button">購入手続きへ</a>
        </div>
    </div>

    {{-- 商品説明 --}}
    <div class="item-description">
        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>
    </div>

    {{-- 商品の情報 --}}
    <div class="item-meta">
        <h3>商品の情報</h3>
        <p>カテゴリー: {{ $item->category->name ?? '未設定' }}</p>
        <p>商品の状態: {{ $item->condition_label }}</p>
    </div>

    {{-- コメントセクション --}}
    <div class="item-comments">
        <h3>コメント ({{ $item->comments->count() }})</h3> <!-- コメント数をリアルタイムで更新 -->
        <div class="comments-container">
            @foreach($item->comments as $comment)
                <div class="comment">
                    {{-- ユーザーのプロフィール画像 --}}
                    <img src="{{ $comment->user->profile_image ?? asset('images/default-profile.png') }}"
                        alt="{{ $comment->user->name }}"
                        class="profile-image">
                    <div class="comment-content">
                        <p><strong>{{ $comment->user->name }}</strong></p>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 商品へのコメント投稿フォーム --}}
    <h3>商品へのコメント</h3>
    @auth
    <form method="POST" action="{{ route('comments.store', ['item_id' => $item->id]) }}" class="comment-form">
        @csrf
        <textarea name="content" placeholder="商品へのコメントを入力">{{ old('content') }}</textarea>

        @if ($errors->has('content'))
            <p class="error-message">⚠️  {{ $errors->first('content') }}</p>
        @endif

        <button type="submit">コメントを送信する</button>
    </form>
    @endauth
    </div>
@endsection
