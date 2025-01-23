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
    {{-- 商品情報をここにリスト表示させる--}}
</div>
@endsection