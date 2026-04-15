@extends('layouts.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login">
        <h1 class="login__title">ログイン</h1>
        <form class="login__form" method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="login__group">
                <label class="login__label" for="email">メールアドレス</label>
                <input class="login__input" type="email" name="email" id="email" value="{{ old('email') }}">
                <p class="login__error">@error('email'){{ $message }}@enderror</p>
            </div>
            <div class="login__group">
                <label class="login__label" for="password">パスワード</label>
                <input class="login__input" type="password" name="password" id="password">
                <p class="login__error">@error('password'){{ $message }}@enderror</p>
            </div>
            <button class="login__btn" type="submit">ログインする</button>
        </form>
        <div class="login__register">
            <a class="login__register-link" href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
@endsection