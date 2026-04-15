@extends('layouts.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
    <div class="verify">
        <p class="verify__text">登録していただいたアドレスに認証メールを送付しました。</p>
        <p class="verify__text">メール認証を完了してください。</p>
        <a class="verify__btn" href="http://localhost:8025" target="_blank">認証はこちらから</a>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="verify__resend" type="submit">認証メールを再送する</button>
        </form>
    </div>
@endsection