@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="container items-container">
    <div class="tabs-container">
        <div class="tabs">
            <span class="active">おすすめ</span>
            <span>マイリスト</span>
        </div>
    </div>

    <div class="item-list">
        @foreach($items as $item)
            <div class="item">
                <a href="{{ route('items.show', $item->id) }}">
                    <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
                    <h3>{{ $item->name }}</h3>
                </a>
                <p>¥{{ number_format($item->price) }}</p>
                <p>{{ $item->condition_label }}</p>
                @if ($item->is_sold)
                    <p class="sold">Sold</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
