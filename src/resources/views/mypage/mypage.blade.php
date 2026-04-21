@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/mypage.css') }}">
@endsection

@section('content')
    @php
        $isSellTab     = ($page ?? '') === 'sell';
        $displayItems  = $isSellTab ? $items : $purchases->map(fn ($p) => $p->item);
        $emptyMessage  = $isSellTab ? '出品した商品はありません' : '購入した商品はありません';
        $showSoldLabel = $isSellTab;
    @endphp
    <div class="mypage">
        <div class="mypage__header">
            <div class="mypage__user">
                @if($profile && $profile->profile_image)
                    <img class="mypage__avatar" src="{{ asset('storage/' . $profile->profile_image) }}"
                        alt="{{ $profile->name }}">
                @else
                    <div class="mypage__avatar-placeholder"></div>
                @endif
                <p class="mypage__name">{{ $profile->name ?? '' }}</p>
            </div>
            <a class="mypage__edit-btn" href="{{ route('mypage.profile.edit') }}">プロフィールを編集</a>
        </div>
        <div class="mypage__tab">
            <a class="mypage__tab-link {{ $isSellTab ? 'mypage__tab-link--active' : '' }}"
                href="{{ route('mypage.index', ['page' => 'sell']) }}">出品した商品</a>
            <a class="mypage__tab-link {{ $isSellTab ? '' : 'mypage__tab-link--active' }}"
                href="{{ route('mypage.index', ['page' => 'buy']) }}">購入した商品</a>
        </div>
        <div class="mypage__list">
            @forelse($displayItems as $item)
                <div class="mypage__card">
                    <a class="mypage__link" href="{{ route('items.show', $item) }}">
                        <div class="mypage__img-wrap">
                            <img class="mypage__img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                            @if($showSoldLabel && $item->status === 'sold')
                                <div class="mypage__sold">Sold</div>
                            @endif
                        </div>
                        <p class="mypage__item-name">{{ $item->name }}</p>
                    </a>
                </div>
            @empty
                <p class="mypage__empty">{{ $emptyMessage }}</p>
            @endforelse
        </div>
    </div>
@endsection
