@extends('layouts.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register">
        <h1 class="register__title">会員登録</h1>
        <form class="register__form" method="POST" action="/register" novalidate>
            @csrf
            <div class="register__group">
                <label class="register__label" for="name">ユーザー名</label>
                <input class="register__input" type="text" name="name" id="name" value="{{ old('name') }}">
                <p class="register__error">@error('name'){{ $message }}@enderror</p>
            </div>
            <div class="register__group">
                <label class="register__label" for="email">メールアドレス</label>
                <input class="register__input" type="email" name="email" id="email" value="{{ old('email') }}">
                <p class="register__error">@error('email'){{ $message }}@enderror</p>
            </div>
            <div class="register__group">
                <label class="register__label" for="password">パスワード</label>
                <input class="register__input" type="password" name="password" id="password">
                <p class="register__error">@error('password'){{ $message }}@enderror</p>
            </div>
            <div class="register__group">
                <label class="register__label" for="password_confirmation">確認用パスワード</label>
                <input class="register__input" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <button class="register__btn" type="submit">登録する</button>
        </form>
        <div class="register__login">
            <a class="register__login-link" href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
@endsection