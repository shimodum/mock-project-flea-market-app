{{-- 取引チャット画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="transaction-title">{{ $transaction->item->name }}の取引チャット</h2>

    {{-- 出品者か購入者の表示 --}}
    <div class="transaction-info">
        <p>出品者: {{ $transaction->item->user->name }}</p>
        <p>購入者: {{ $transaction->buyer->name }}</p>
        <p>ステータス: {{ $transaction->status }}</p>
    </div>

    {{-- チャット履歴 --}}
    <div class="chat-container">
        @foreach ($messages as $message)
            <div class="chat-message {{ $message->user_id === auth()->id() ? 'my-message' : 'their-message' }}">
                <p>{{ $message->message }}</p>
                
                @if ($message->image_path)
                    <img src="{{ asset('storage/' . $message->image_path) }}" alt="画像" class="chat-image">
                @endif

                <span class="timestamp">{{ $message->created_at->format('Y-m-d H:i') }}</span>
            </div>
        @endforeach
    </div>

    {{-- メッセージ送信フォーム --}}
    <form action="{{ route('chat_messages.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="message-input">
            <textarea name="message" placeholder="メッセージを入力してください" rows="3" required></textarea>
        </div>

        <div class="file-input">
            <label for="image">画像を添付:</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="send-button">送信</button>
    </form>
</div>
@endsection
