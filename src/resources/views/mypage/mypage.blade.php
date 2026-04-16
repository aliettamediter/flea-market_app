@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/mypage.css') }}">
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage__header">
            <div class="mypage__user">
                @if($user->profile_image)
                    <img class="mypage__avatar" src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                @else
                    <div class="mypage__avatar-placeholder"></div>
                @endif
                <p class="mypage__name">{{ $user->name }}</p>
            </div>
            <a class="mypage__edit-btn" href="/mypage/profile">プロフィールを編集</a>
        </div>
        <div class="mypage__tab">
            <a class="mypage__tab-link {{ $page === 'sell' ? 'mypage__tab-link--active' : '' }}"
                href="/mypage?page=sell">出品した商品</a>
            <a class="mypage__tab-link {{ $page === 'buy' ? 'mypage__tab-link--active' : '' }}"
                href="/mypage?page=buy">購入した商品</a>
        </div>
        <div class="mypage__list">
            @if($page === 'sell')
                @forelse($items as $item)
                    <div class="mypage__card">
                        <a class="mypage__link" href="/item/{{ $item->id }}">
                            <div class="mypage__img-wrap">
                                <img class="mypage__img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                @if($item->status === 'sold')
                                    <div class="mypage__sold">Sold</div>
                                @endif
                            </div>
                            <p class="mypage__item-name">{{ $item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p class="mypage__empty">出品した商品はありません</p>
                @endforelse
            @else
                @forelse($purchases as $purchase)
                    <div class="mypage__card">
                        <a class="mypage__link" href="/item/{{ $purchase->item->id }}">
                            <div class="mypage__img-wrap">
                                <img class="mypage__img" src="{{ $purchase->item->image_url }}" alt="{{ $purchase->item->name }}">
                            </div>
                            <p class="mypage__item-name">{{ $purchase->item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p class="mypage__empty">購入した商品はありません</p>
                @endforelse
            @endif
        </div>
    </div>
@endsection