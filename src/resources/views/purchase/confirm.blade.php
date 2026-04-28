@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/confirm.css') }}">
@endsection

@section('content')
    <div class="purchase">
        <form class="purchase__form" method="POST" action="{{ route('purchase.store', $item) }}" novalidate>
            @csrf
            <div class="purchase__inner">
                <div class="purchase__left">
                    <div class="purchase__item">
                        <img class="purchase__item-img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        <div class="purchase__item-info">
                            <p class="purchase__item-name">{{ $item->name }}</p>
                            <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                    <div class="purchase__border"></div>
                    <div class="purchase__section">
                        <h2 class="purchase__section-title">支払い方法</h2>
                        <div class="purchase__select-wrap">
                            <select class="purchase__select" name="payment_method" id="payment_method">
                                <option value="">選択してください</option>
                                <option value="credit_card">クレジットカード</option>
                                <option value="konbini">コンビニ払い</option>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="purchase__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="purchase__border"></div>
                    <div class="purchase__section">
                        <div class="purchase__section-header">
                            <h2 class="purchase__section-title">配送先</h2>
                            <a class="purchase__change-link" href="{{ route('purchase.address.edit', $item) }}">変更する</a>
                        </div>
                        <p class="purchase__address">〒{{ $user->profile->postal_code ?? '' }}</p>
                        <p class="purchase__address">
                            {{ trim(($user->profile->address ?? '') . ' ' . ($user->profile->building ?? '')) }}
                        </p>
                        <input type="hidden" name="postal_code" value="{{ $user->profile->postal_code ?? '' }}">
                        <input type="hidden" name="address" value="{{ $user->profile->address ?? '' }}">
                        <input type="hidden" name="building" value="{{ $user->profile->building ?? '' }}">
                        @error('postal_code')
                            <p class="purchase__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="purchase__border"></div>
                </div>
                <div class="purchase__right">
                    <div class="purchase__summary">
                        <div class="purchase__summary-row">
                            <span class="purchase__summary-label">商品代金</span>
                            <span class="purchase__summary-value">¥{{ number_format($item->price) }}</span>
                        </div>
                        <div class="purchase__summary-row purchase__summary-row--border">
                            <span class="purchase__summary-label">支払い方法</span>
                            <span class="purchase__summary-value" id="selected-payment">未選択</span>
                        </div>
                    </div>
                    <button class="purchase__btn" type="submit">購入する</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/purchase.js') }}"></script>
@endsection
