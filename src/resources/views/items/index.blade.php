@extends('layouts.app')

@section('title', '商品一覧画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="product-list">
        <nav class="product-list__nav">
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
        </nav>

        <div class="product-grid">
            @foreach($items as $item)
                <div class="product-card">
                    @if($item->purchase)
                        <div class="badge-sold">
                            Sold
                        </div>
                    @endif
                    <a href="{{ route('items.show', $item->id) }}">
                        <div class="product-image">
                            <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
                        </div>
                        <p class="product-name">{{ $item->name }}</p>
                    </a>

                </div>
            @endforeach
        </div>
    </div>
@endsection