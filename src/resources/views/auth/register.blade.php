{{-- 会員登録画面 --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2>会員登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="password-container">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password">
            <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="password-container">
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
            <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
            @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">登録する</button>

        <div class="login-link">
            <a href="{{ route('login.form') }}">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection
