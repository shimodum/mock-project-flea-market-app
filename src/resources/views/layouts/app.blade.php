<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>COACHTECH フリマアプリ</title>
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        @yield('css')
        <script src="{{ asset('js/common.js') }}" defer></script>
    </head>
    <body>
        <header class="header">
            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH ロゴ">

        {{-- 現在のURLが "register" または "login" でない場合に適用 --}}
        @if (!request()->is('register') && !request()->is('login'))
            <input type="text" placeholder="なにをお探しですか？">
            <div class="navigation">
                <a href="/logout">ログアウト</a>
                <a href="/mypage">マイページ</a>
                <a href="/sell" class="sell-button">出品</a>
            </div>
        @endif
        </header>

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
