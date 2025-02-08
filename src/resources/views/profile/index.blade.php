{{-- プロフィール画面（マイページ） --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="profile-header">
        <!-- プロフィール画像の表示 -->
        <div class="profile-image">
            @if ($user->profile_image)
                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="プロフィール画像">
            @else
                <div class="profile-placeholder"></div>
            @endif
        </div>
        
        <h2 class="profile-name">{{ $user->name }}</h2>

        <!-- プロフィール編集ボタン -->
        <a href="{{ route('profile.edit') }}" class="profile-edit-btn">プロフィールを編集</a>
    </div>

    <!-- タブの切り替え -->
    <div class="tab-menu">
        <a href="{{ route('profile.index', ['tab' => 'sell']) }}" class="{{ $tab == 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('profile.index', ['tab' => 'buy']) }}" class="{{ $tab == 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <!-- 商品リスト -->
    <div class="product-list">
        @if ($tab == 'sell')
            @if ($sellItems->isEmpty())
                <p>出品した商品はありません。</p>
            @else
                @foreach ($sellItems as $item)
                    <div class="product-item">
                        <img src="{{ asset('storage/item_images/' . $item->image) }}" alt="商品画像">
                        <p>{{ $item->name }}</p>
                    </div>
                @endforeach
            @endif
        @elseif ($tab == 'buy')
            @if ($buyItems->isEmpty())
                <p>購入した商品はありません。</p>
            @else
                @foreach ($buyItems as $purchase)
                    @if ($purchase->item) {{-- item が存在する場合のみ表示 --}}
                        <div class="product-item">
                            <img src="{{ asset('storage/item_images/' . $purchase->item->image_path) }}" alt="商品画像">
                            <p>{{ $purchase->item->name }}</p>
                        </div>
                    @endif
                @endforeach

            @endif
        @endif
    </div>
</div>
@endsection

