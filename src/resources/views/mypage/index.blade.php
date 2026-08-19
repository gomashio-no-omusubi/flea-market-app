@extends('layouts.app')

@section('title', 'プロフィール画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage-header">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- プロフィール関連 --}}
            <div class="mypage-profile__inner">
                <div class="profile-image__preview {{ $profile && $profile->img_url ? '' : 'is-empty' }}">
                    @if ($profile && $profile->img_url)
                        <img id="preview" class="mypage-profile__img" src="{{ asset('storage/' . $profile->img_url) }}"
                            alt="プロフィール画像">
                    @endif
                </div>
                <h1 class="mypage__user-name">{{ $user->name }}</h1>

                <a href="{{ route('mypage.profile.edit') }}" class="mypage-edit">プロフィールを編集</a>
            </div>
        </div>

        {{-- 出品した商品・購入した商品の一覧 --}}
        <nav class="product-list__nav">
            <div class="l-container">
                <ul class="product-list__tabs">
                    <li>
                        <a href="{{ route('mypage.index', ['page' => 'sell']) }}"
                            class="product-list__tab-link {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
                    </li>
                    <li>
                        <a href="{{ route('mypage.index', ['page' => 'buy']) }}"
                            class="product-list__tab-link {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- -商品画像・商品名 --}}
        <div class="l-container">
            <div class="product-grid">
                @forelse($items as $item)
                    <div class="product-card">
                        <a href="{{ route('items.show', $item->id) }}">
                            <div class="product-image-box">
                                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
                                @if($item->purchase)
                                    <div class="badge-sold">Sold</div>
                                @endif
                            </div>
                            <p class="product-name">{{ $item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p class="empty-message">
                        @if ($page === 'buy')
                            購入した商品はありません。
                        @else
                            出品した商品はありません。
                        @endif
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection