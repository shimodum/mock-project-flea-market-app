{{-- 商品一覧画面（トップページ） --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="container items-container">
    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('items.index') }}" class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('items.index', ['tab' => 'mylist']) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
    </div>

    <div class="item-list">
        @if (request('tab') === 'mylist' && !auth()->check())
            <p class="alert alert-warning">
                マイリストを表示するには <a href="{{ route('login.form') }}">ログイン</a> が必要です。
            </p>
        @elseif ($items->isEmpty())
            <p class="alert alert-info">表示する商品がありません。</p>
        @else
            @foreach($items as $item)
                <div class="item">
                    <a href="{{ route('items.show', $item->id) }}">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                        <h3>{{ $item->name }}</h3>
                    </a>
                    <p>¥{{ number_format($item->price) }}</p>
                    <p>{{ $item->condition_label }}</p>
                    @if ($item->is_sold)
                        <p class="sold-label">Sold</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
