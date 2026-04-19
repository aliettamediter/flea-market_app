<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('images/title_logo.png') }}" alt="ロゴ">
            </a>
            <div class="header__search">
                <form class="header__search-form" method="GET" action="/">
                    <input class="header__search-input" type="text" name="search" value="{{ request('search') }}"
                        placeholder="なにをお探しですか？">
                    <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">
                </form>
            </div>
            <nav class="header__nav">
                @auth
                    <form class="header__logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="header__nav-link" type="submit">ログアウト</button>
                    </form>
                @else
                    <a class="header__nav-link" href="{{ route('login') }}">ログイン</a>
                @endauth
                <a class="header__nav-link" href="{{ route('mypage.index') }}">マイページ</a>
                <a class="header__nav-btn" href="{{ route('sell') }}">出品</a>
            </nav>
        </div>
    </header>
    <main class="main">
        @yield('content')
    </main>
    @yield('js')
</body>

</html>
