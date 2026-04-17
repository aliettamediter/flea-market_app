@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
@endsection

@section('content')
    <div class="address">
        <h1 class="address__title">住所の変更</h1>
        <form class="address__form" method="POST" action="{{ route('purchase.address.update', $item) }}" novalidate>
            @csrf

            <div class="address__group">
                <label class="address__label" for="postal_code">郵便番号</label>
                <input class="address__input" type="text" name="postal_code" id="postal_code"
                    value="{{ old('postal_code', $user->profile->postal_code ?? '' ) }}" placeholder="123-4567">
                <p class="address__error">@error('postal_code'){{ $message }}@enderror</p>
            </div>

            <div class="address__group">
                <label class="address__label" for="address">住所</label>
                <input class="address__input" type="text" name="address" id="address"
                    value="{{ old('address', $user->profile->address ?? '') }}">
                <p class="address__error">@error('address'){{ $message }}@enderror</p>
            </div>

            <div class="address__group">
                <label class="address__label" for="building">建物名</label>
                <input class="address__input" type="text" name="building" id="building"
                    value="{{ old('building', $user->profile->building ?? '') }}">
            </div>

            <button class="address__btn" type="submit">更新する</button>
        </form>
    </div>
@endsection