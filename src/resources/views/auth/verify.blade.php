{{-- メール認証画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>メールアドレスの確認</h2>
    <p>確認メールを送信しました。メール内のリンクをクリックして認証を完了してください。</p>

    @if (session('resent'))
        <div class="alert alert-success">
            新しい認証メールを送信しました！
        </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection
