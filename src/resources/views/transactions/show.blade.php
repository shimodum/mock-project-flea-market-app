""{{-- 取引チャット画面 --}}
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

        {{-- メッセージ一覧 --}}
        <div class="chat-container">
            @foreach ($messages as $message)
                <div class="chat-message {{ $message->user_id == Auth::id() ? 'my-message' : 'their-message' }}">
                    <p>{{ $message->message }}</p>
                    @if ($message->image_path)
                        <img src="{{ asset('storage/' . $message->image_path) }}" alt="画像" class="chat-image">
                    @endif
                    <span class="timestamp">{{ $message->created_at->format('Y/m/d H:i') }}</span>
                </div>
            @endforeach
        </div>

        {{-- メッセージ送信フォーム --}}
        <form action="{{ route('chat_messages.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="message-input">
                <textarea name="message" placeholder="取引メッセージを記入してください" required></textarea>
            </div>
            <div class="file-input">
                <label for="image">画像を追加</label>
                <input type="file" name="image">
            </div>
            <button type="submit" class="send-button">
                <img src="{{ asset('images/send_icon.png') }}" alt="送信">
                送信
            </button>
        </form>

        {{-- 評価モーダル --}}
        <button id="completeTransactionButton">取引を完了する</button>

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