@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <h2>ログイン</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">ユーザー名 / メールアドレス</label>
            <input type="text" name="email" id="email" value="{{ old('email') }}">
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

        <button type="submit">ログインする</button>

        <div class="register-link">
            <a href="{{ route('register.form') }}">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection
