@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    @php
        $isMyList     = $tab === 'mylist' && auth()->check();
        $displayItems = $isMyList ? $likedItems : $items;
        $emptyMessage = $isMyList ? 'いいねした商品はありません' : '商品がありません';
    @endphp
    <div class="item">
        <div class="item__tab">
            <a href="{{ route('items.index', ['tab' => 'recommend', 'search' => request('search')]) }}"
                class="item__tab-link {{ $tab === 'recommend' ? 'item__tab-link--active' : '' }}">おすすめ</a>
            @auth
                <a href="{{ route('items.index', ['tab' => 'mylist', 'search' => request('search')]) }}"
                    class="item__tab-link {{ $tab === 'mylist' ? 'item__tab-link--active' : '' }}">マイリスト</a>
            @endauth
        </div>
        <div class="item__list">
            @forelse($displayItems as $item)
                <div class="item__card">
                    <a class="item__link" href="{{ route('items.show', $item) }}">
                        <div class="item__img-wrap">
                            <img class="item__img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                            @if($item->status === 'sold')
                                <div class="item__sold">Sold</div>
                            @endif
                        </div>
                        <p class="item__name">{{ $item->name }}</p>
                    </a>
                </div>
            @empty
                <p class="item__empty">{{ $emptyMessage }}</p>
            @endforelse
        </div>
    </div>
@endsection
