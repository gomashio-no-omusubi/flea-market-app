@extends('layouts.app')

@section('title', '商品詳細画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-show">
        <div class="item-show__container">
            {{-- 左側：商品画像エリア --}}
            <div class="item-show__image-box">
                {{-- 画像がない場合の代替え画像や、storageからの取得を想定 --}}
                <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
            </div>

            {{-- 右側：商品詳細エリア --}}
            <div class="item-show__content">
                <div class="item-show__header">
                    <h1 class="item-show__name">{{ $item->name }}</h1>
                    <p class="item-show__brand">{{ $item->brand }}</p>
                    <p class="item-show__price">
                        <span class="currency-symbol">¥</span>{{ number_format($item->price) }}<span
                            class="tax-label">(税込)</span>
                    </p>
                    {{-- いいね・コメントアイコン --}}
                    <div class="item-show__status">
                        {{-- いいねアイコン --}}
                        <div class="status-item">
                            <form action="{{ route('favorites.toggle', ['item_id' => $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="icon-btn">
                                    @if($item->is_favorited_by_user)
                                        <img src="{{ asset('images/heart_pink.png') }}" alt="いいね解除" class="status-icon">
                                    @else
                                        <img src="{{ asset('images/heart_default.png') }}" alt="いいね" class="status-icon">
                                    @endif
                                </button>
                            </form>
                            <span class="status-count">{{ $item->favorites_count ?? 0 }}</span>
                        </div>
                        {{-- コメントアイコン --}}
                        <div class="status-item">
                            <div class="icon-wrapper">
                                <a href="#comment-form">
                                    <img src="{{ asset('images/comment_icon.png') }}" alt="コメント" class="status-icon">
                                </a>
                            </div>
                            <span class="status-count">{{ $item->comments->count() }}</span>
                        </div>
                    </div>
                    <a class="purchase-btn btn" href="{{ route('purchase.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
                </div>
                {{-- 商品説明 --}}
                <section class="item-show__section">
                    <h2 class="section-title">商品説明</h2>
                    <div class="item-description">
                        <p>{{ $item->description}}</p>
                    </div>
                </section>
                {{-- 商品の情報 --}}
                <section class="item-show__section">
                    <h2 class="section-title">商品の情報</h2>
                    <div class="info-group">
                        <span class="info-label">カテゴリー</span>
                        <div class="category-tags">
                            @foreach($item->categories as $category)
                                <span class="tag">

                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="info-group">
                        <span class="info-label">商品の状態</span>
                        <span class="info-value">{{ $item->condition->name }}</span>
                    </div>
                </section>
                {{-- コメント --}}
                <section class="item-show__comments">
                    <h2 class="section-title__comment">コメント({{ $item->comments->count() }})</h2>

                    <div class="comment-list">
                        @foreach($item->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-user">
                                    <div class="use-icon">
                                        {{-- ユーザーアイコン画像がある場合 --}}
                                        {{-- <img src="{{ asset('storage/' . $comment->user->image_url) }}" alt=""> --}}
                                    </div>
                                    <span class="user-name">{{ $comment->user->name }}</span>
                                </div>
                                <div class="comment-body">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- コメント入力 --}}
                    <form class="comment-form" action="{{ route('comments.store', ['item_id' => $item->id]) }}"
                        method="POST">
                        @csrf
                        <div id="comment-form">
                            <h3 class="form-title">
                                <label for="content">商品へのコメント</label>
                            </h3>
                            <textarea class="form-textarea" name="content" id="content">{{ old('content') }}</textarea>

                        </div>

                        @error('content')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <button class="comment-post_btn btn" type="submit">コメントを送信する</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection