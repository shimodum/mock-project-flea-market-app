{{-- プロフィール編集画面（設定画面） --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">プロフィール設定</h1>

    {{-- プロフィール画像 --}}
    <div class="profile-image-container">
        <h2 class="image-title"></h2>
        <div class="profile-image-placeholder">
            <img src="{{ $user->profile_image_url }}" alt="プロフィール画像" class="profile-image" id="preview-image">
        </div>
        <button type="button" class="upload-button" onclick="document.getElementById('profile_image').click()">画像を選択する</button>
    </div>

    {{-- フォーム --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <input type="file" id="profile_image" name="profile_image" style="display:none;">

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}">
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building ?? '') }}">
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/profile.js') }}" defer></script>
@endsection
