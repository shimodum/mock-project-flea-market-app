{{-- 商品出品画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div id="sell-form-container">
    <h1 class="page-title">商品を出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像</label>
            <div class="image-upload">
                <label for="image">画像を選択する</label>
                <input type="file" id="image" name="image" required>
            </div>
        </div>

        {{-- 商品の詳細 --}}
        <div class="form-group">
            <h2 class="section-title">商品の詳細</h2>

            {{-- カテゴリー選択 --}}
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach (['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
                    <div>
                        <input type="checkbox" name="categories[]" value="{{ $category }}" id="category-{{ $loop->index }}">
                        <label for="category-{{ $loop->index }}">{{ $category }}</label>
                    </div>
                @endforeach
            </div>

            {{-- 商品の状態 --}}
            <label for="condition">商品の状態</label>
            <select id="condition" name="condition" required>
                <option value="" selected disabled>選択してください</option>
                <option value="1">良好</option>
                <option value="2">目立った傷や汚れなし</option>
                <option value="3">やや傷や汚れあり</option>
                <option value="4">状態が悪い</option>
            </select>
        </div>

        {{-- 商品名と説明 --}}
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price">販売価格</label>
            <input type="text" id="price" name="price" value="￥" required>
        </div>

        {{-- 出品ボタン --}}
        <button type="submit" class="sell-button">出品する</button>
    </form>
</div>
@endsection
