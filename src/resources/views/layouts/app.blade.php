{{-- レイアウトファイル --}}
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

    {{-- CSRF トークンを埋め込む --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/common.js') }}" defer></script>
</head>

<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH ロゴ">

        {{-- 会員登録画面とログイン画面以外で表示 --}}
        @if (!request()->is('register') && !request()->is('login'))
            <div class="search-container">
                <form method="GET" action="{{ route('items.index') }}">
                    <input type="text" name="query" value="{{ request('query') }}" placeholder="何をお探しですか？">
                    <button type="submit">検索</button>
                </form>
            </div>
            <div class="navigation">
                {{-- ユーザーがログインしているかを判定し、ログインしている場合は「ログアウト」を表示する --}}
                @if (auth()->check())
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                {{-- 未ログインの場合は「ログイン」を表示する --}}
                @else
                    <a href="{{ route('login.form') }}">ログイン</a>
                @endif
                <a href="/mypage">マイページ</a>
                <a href="{{ route('items.create') }}" class="sell-button">出品</a>
            </div>
        @endif
    </header>


    <div class="container">
        @yield('content')
    </div>

    {{-- JavaScript の読み込み --}}
    @yield('js')
</body>

</html>
