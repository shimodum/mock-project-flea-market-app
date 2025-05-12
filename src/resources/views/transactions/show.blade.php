{{-- 取引チャット画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions.css') }}">
@endsection

@section('content')
{{-- ハンバーガーメニュー --}}
<button class="hamburger-menu" onclick="toggleSidebar()">☰</button>

<div class="transaction-wrapper">
    {{-- サイドバーの読み込み --}}
    @include('transactions.sidebar', ['sidebarTransactions' => $sidebarTransactions, 'transaction' => $transaction])

    <div class="transaction-main">
        <div class="transaction-header">
            <div class="header-left">
                <div class="profile-header">
                    {{-- ログイン中のユーザーが購入者の場合 --}}
                    @if (Auth::id() === $transaction->buyer_id)
                        {{-- 出品者のプロフィール画像を表示 --}}
                        <img src="{{ asset('storage/' . $transaction->item->user->profile_image) }}" alt="User Image" class="profile-image">
                        <h2>{{ $transaction->item->user->name }}さんとの取引画面</h2>
                    @else
                        {{-- 購入者のプロフィール画像を表示 --}}
                        <img src="{{ asset('storage/' . $transaction->buyer->profile_image) }}" alt="User Image" class="profile-image">
                        <h2>{{ $transaction->buyer->name }}さんとの取引画面</h2>
                    @endif
                </div>

                {{-- 区切り線 --}}
                <hr class="divider-line">
                
                {{-- 商品情報 --}}
                <div class="product-display">
                    <div class="product-image-container">
                        <img src="{{ $transaction->item->image_url }}" alt="{{ $transaction->item->name }}" class="transaction-item-image">
                    </div>
                    <div class="product-info">
                        <h3>{{ $transaction->item->name }}</h3>
                        <p>¥{{ number_format($transaction->item->price) }}</p>
                    </div>
                </div>
            </div>

            {{-- 購入者のみ表示 --}}
            @if (Auth::id() === $transaction->buyer_id)
                <button id="completeTransactionButton" class="complete-transaction-btn">取引を完了する</button>
            @endif
        </div>

        {{-- 区切り線 --}}
        <hr class="divider-line">

        {{-- メッセージ一覧 --}}
        <div class="chat-container">
            @foreach ($messages as $message)
                <div class="chat-message {{ $message->user_id == Auth::id() ? 'my-message' : 'their-message' }}" id="message-{{ $message->id }}">
                    <img src="{{ asset('storage/' . $message->user->profile_image) }}" alt="User Image" class="profile-image-small">
                    
                    {{-- 表示部分 --}}
                    <div class="message-content">
                        <p>{{ $message->message }}</p>
                        @if ($message->image_path)
                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="Image Attachment" class="attached-image">
                        @endif
                    </div>

                    {{-- 編集フォーム（非表示状態） --}}
                    <form class="message-edit-form" style="display: none;">
                        <textarea>{{ $message->message }}</textarea>
                        <button type="button" class="save-edit" data-id="{{ $message->id }}">保存</button>
                        <button type="button" class="cancel-edit">キャンセル</button>
                    </form>

                    <div class="message-options">
                        <span>{{ $message->created_at->format('Y/m/d H:i') }}</span>
                        
                        {{-- 自分のメッセージのみ編集・削除が表示される --}}
                        @if ($message->user_id == Auth::id())
                            <span class="message-action edit-message" data-id="{{ $message->id }}">編集</span>
                            <span class="message-action delete-message" data-id="{{ $message->id }}">削除</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- メッセージ送信フォーム --}}
        <form action="{{ route('chat_messages.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- エラーメッセージの表示 --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; margin-bottom: 10px;">
                    @foreach ($errors->all() as $error)
                        <p class="error-message">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="message-input">
                <textarea 
                    name="message" 
                    placeholder="取引メッセージを記入してください" 
                    id="messageTextarea">{{ old('message') }}</textarea>
                <div class="send-controls">
                    <label for="image">
                        <img src="{{ asset('images/add_image.png') }}" alt="Add Image" class="send-icon">
                    </label>
                    <input type="file" id="image" name="image" style="display: none;">
                    <button type="submit" class="send-button">
                        <img src="{{ asset('images/send_message.png') }}" alt="Send">
                    </button>
                </div>
            </div>
        </form>

        {{-- モーダルの読み込み --}}
        @include('transactions.modal', ['transaction' => $transaction])
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/transaction.js') }}"></script>
@endsection
