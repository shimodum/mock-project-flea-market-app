@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
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
        </div>
    @endforeach
</div>
@endsection