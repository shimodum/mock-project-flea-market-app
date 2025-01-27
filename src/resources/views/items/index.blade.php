@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="header">
    <form method="GET" action="{{ route('items.index') }}">
        <input
            type="text"
            name="search"
            placeholder="商品名で検索"
            value="{{ old('search', $search ?? '') }}" {{-- 検索キーワードを保持 --}}
        >
        <button type="submit">検索</button>
    </form>
</div>

<div class="tabs">
    <span>おすすめ</span>
    <span>マイリスト</span>
</div>

<div class="item-list">
    @foreach($items as $item)
        <div class="item">
            <img src="{{ $item->image_path }}" alt="{{ $item->name }}">
            <h3>{{ $item->name }}</h3>
            <p>¥{{ number_format($item->price) }}</p>
            <p>{{ $item->condition_label }}</p>
            @if ($item->is_sold)
                <p class="sold">Sold</p>
            @endif
        </div>
    @endforeach
</div>
@endsection
