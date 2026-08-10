@extends('layouts.app')

@section('title', '商品一覧画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="product-list">
        {{-- おすすめ・マイリストの一覧 --}}
        <nav class="product-list__nav">
            <div class="l-container">
                <ul class="product-list__tabs">
                    <li>
                        <a href="{{ route('items.index', ['keyword' => $keyword ?? '']) }}"
                            class="product-list__tab-link {{ request()->routeIs('items.index') && request('tab') != 'mylist' ? 'active' : '' }}">おすすめ</a>
                    </li>
                    <li>
                        <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword ?? '']) }}"
                            class="product-list__tab-link {{ request()->routeIs('items.index') && request('tab') == 'mylist' ? 'active' : '' }}">マイリスト</a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- -商品画像・商品名 --}}
        <div class="l-container">
            <div class="product-grid">
                @foreach($items as $item)
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
                @endforeach
            </div>
        </div>
    </div>
@endsection