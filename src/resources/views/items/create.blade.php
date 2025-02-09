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
            <label for="image" class="form-label">商品画像</label>
            <div class="image-upload">
                <label for="image" class="upload-button">画像を選択する</label>
                <input type="file" id="image" name="image" required style="display:none;">
            </div>
        </div>

        {{-- 商品の詳細 --}}
        <div class="form-group">
            <h2 class="section-title">商品の詳細</h2>

            {{-- カテゴリー選択 --}}
            <label class="form-label">カテゴリー</label>
            <div class="category-list">
                @foreach (['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
                    <label class="category">
                        <input type="checkbox" name="categories[]" value="{{ $category }}">
                        <span>{{ $category }}</span>
                    </label>
                @endforeach
            </div>

            {{-- 商品の状態 --}}
            <label for="condition" class="form-label">商品の状態</label>
            <select id="condition" name="condition" class="form-control" required>
                <option value="" selected disabled>選択してください</option>
                <option value="1">良好</option>
                <option value="2">目立った傷や汚れなし</option>
                <option value="3">やや傷や汚れあり</option>
                <option value="4">状態が悪い</option>
            </select>
        </div>

        {{-- 商品名と説明 --}}
        <div class="form-group">
            <h2 class="section-title">商品名と説明</h2>
            <label for="name" class="form-label">商品名</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">商品の説明</label>
            <textarea id="description" name="description" class="form-control" required></textarea>
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price" class="form-label">販売価格</label>
            <div class="price-container">
                <input type="text" id="price" name="price" class="form-control with-yen" value="￥" required>
            </div>
        </div>

        {{-- 出品ボタン --}}
        <div class="form-group button-spacing">
            <button type="submit" class="sell-button">出品する</button>
        </div>
    </form>
</div>
@endsection
