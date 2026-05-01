@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-detail">
        <div class="item-detail__inner">
            <div class="item-detail__left">
                <div class="item-detail__img-wrap">
                    <img class="item-detail__img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    @if($item->status === 'sold')
                        <div class="item-detail__sold">Sold</div>
                    @endif
                </div>
            </div>
            <div class="item-detail__right">
                <h1 class="item-detail__name">{{ $item->name }}</h1>
                <p class="item-detail__brand">{{ $item->brand }}</p>
                <p class="item-detail__price">¥{{ number_format($item->price) }}<span class="item-detail__tax">（税込）</span>
                </p>
                @php
                    $isLiked   = auth()->check() && $item->isLikedBy(auth()->user());
                    $likeImage = $isLiked ? 'heart_logo_pink.png' : 'heart_logo_white.png';
                    $likeClass = 'item-detail__like-btn' . ($isLiked ? ' item-detail__like-btn--active' : '');
                @endphp
                <div class="item-detail__actions">
                    @auth
                        <form method="POST"
                            action="{{ $isLiked ? route('item.like.destroy', $item) : route('item.like.store', $item) }}">
                            @csrf
                            @if($isLiked)
                                @method('DELETE')
                            @endif
                            <button class="{{ $likeClass }}" type="submit">
                                <img src="{{ asset('images/' . $likeImage) }}" alt="いいね">
                                {{ $item->likes->count() }}
                            </button>
                        </form>
                    @else
                        <span class="{{ $likeClass }}">
                            <img src="{{ asset('images/' . $likeImage) }}" alt="いいね">
                            {{ $item->likes->count() }}
                        </span>
                    @endauth
                    <span class="item-detail__comment-count">
                        <img src="{{ asset('images/comment_logo.png') }}" alt="コメント">
                        {{ $item->comments->count() }}
                    </span>
                </div>
                @if(!$item->isSoldOut() && $item->user_id !== auth()->id())
                    <a class="item-detail__buy-btn" href="{{ route('purchase.create', $item) }}">購入手続きへ</a>
                @endif
                <div class="item-detail__section">
                    <h2 class="item-detail__section-title">商品説明</h2>
                    <p class="item-detail__description">{{ $item->description }}</p>
                </div>
                <div class="item-detail__section">
                    <h2 class="item-detail__section-title">商品情報</h2>
                    <div class="item-detail__info-row">
                        <span class="item-detail__info-label">カテゴリ</span>
                        <div class="item-detail__categories">
                            @foreach($item->categories as $category)
                                <span class="item-detail__category">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="item-detail__info-row">
                        <span class="item-detail__info-label">商品の状態</span>
                        <span class="item-detail__condition">{{ $item->condition->name }}</span>
                    </div>
                </div>
                <div class="item-detail__section">
                    <h2 class="item-detail__section-title">コメント（{{ $item->comments->count() }}）</h2>
                    @forelse($item->comments as $comment)
                        <div class="item-detail__comment">
                            <div class="item-detail__comment-user">
                                @if($comment->user->profile && $comment->user->profile->profile_image)
                                    <img class="item-detail__comment-avatar" src="{{ asset('storage/' . $comment->user->profile->profile_image) }}"
                                        alt="{{ $comment->user->profile->name }}">
                                @else
                                    <div class="item-detail__comment-avatar-placeholder"></div>
                                @endif
                                <span class="item-detail__comment-username">{{ $comment->user->profile->name ?? '' }}</span>
                            </div>
                            <p class="item-detail__comment-body">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="item-detail__no-comment">コメントはありません</p>
                    @endforelse
                </div>
                <div class="item-detail__section">
                    <h2 class="item-detail__section-title">商品へのコメント</h2>
                    @auth
                    <form method="POST" action="{{ route('item.comments.store', $item) }}">
                        @csrf
                        <textarea class="item-detail__textarea" name="body" placeholder="コメントを入力してください"></textarea>
                        <p class="item-detail__error">@error('body'){{ $message }}@enderror</p>
                        <button class="item-detail__comment-btn" type="submit">コメントを送信する</button>
                    </form>
                    @else
                        <textarea class="item-detail__textarea" placeholder="コメントを入力してください" readonly></textarea>
                        <a class="item-detail__comment-btn" href="{{ route('login') }}">コメントを送信する</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
