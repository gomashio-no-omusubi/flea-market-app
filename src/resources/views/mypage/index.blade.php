@extends('layouts.app')

@section('title', 'プロフィール画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage-header">

            <div class="profile-image__preview {{ $profile && $profile->img_url ? '' : 'is-empty' }}">
                @if ($profile && $profile->img_url)
                    <img id="preview" src="{{ asset('storage/' . $profile->img_url) }}" alt="">
                @else
                    <img id="preview" src="" alt="" style="display: none;">
                @endif
            </div>
            <h1 class="mypage__user-name">{{ $user->name }}</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('mypage.profile.edit') }}" class="mypage-edit">プロフィールを編集</a>

            <!-- -------------------------------------------------- -->
            {{-- 【ここから新規追加】タブ切り替えメニュー --}}
            <!-- -------------------------------------------------- -->
            <nav class="product-list__nav" style="margin-top: 20px;">
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
            </nav>

            <!-- -------------------------------------------------- -->
            {{-- 【ここから新規追加】購入した商品の一覧表示 --}}
            <!-- -------------------------------------------------- -->
            <div class="product-grid">
                @forelse($items as $item)
                    <div class="product-card">
                        @if($item->purchase)
                            <div class="badge-sold">
                                SOLD
                            </div>
                        @endif

                        <a href="{{ route('items.show', $item->id) }}">
                            <div class="product-image">
                                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
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