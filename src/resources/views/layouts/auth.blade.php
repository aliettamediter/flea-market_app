<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/auth.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('images/title_logo.png') }}" alt="ロゴ">
            </a>
        </div>
    </header>
    <main class="main">
        @yield('content')
    </main>
    @yield('js')
</body>

</html>