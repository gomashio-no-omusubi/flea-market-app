@extends('layouts.app')

@section('title', '商品出品画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <div class="sell-container">
        <h1 class="sell-title">商品の出品</h1>

        <form action="{{ route('items.store') }}" class="sell-form" method="POST" enctype="multipart/form-data">
            @csrf

            <!--商品画像-->
            <div class="sell-form__group">
                <label class="sell-form__label" for="item-image">商品画像</label>
                <div class="sell-form__uploader">
                    <input type="file" name="img_url" id="image" class="sell-form__file-input" accept="image/*">
                    <label class="sell-form__upload-btn" for="image">
                        画像を選択する
                    </label>
                </div>
            </div>

            <!--商品の詳細-->
            <div class="sell-form__section">
                <h2 class="sell-form__title">商品の詳細</h2>
                <!--カテゴリー-->
                <div class="sell-form__group">
                    <label class="sell-form__label">カテゴリー</label>
                    <div class="sell-form__tag-list">
                        @foreach($categories as $category)
                            <div class="sell-form__tag-item">
                                <input class="sell-form__checkbox" type="checkbox" name="categories[]"
                                    id="category-{{ $category->id }}" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <label class="sell-form__tag-btn"
                                    for="category-{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!--商品の状態-->
                <div class="sell-form__group">
                    <label class="sell-form__label" for="condition_id">商品の状態</label>
                    <select name="condition_id" id="condition_id" class="sell-form__select">
                        <option value="">選択してください</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!--商品名と説明-->
            <div class="sell-form__section">
                <h2 class="sell-form__title">商品名と説明</h2>
                <!--商品名-->
                <div class="sell-form__group">
                    <label class="sell-form__label" for="name">商品名</label>
                    <input class="sell-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                </div>
                <!--ブランド名-->
                <div class="sell-form__group">
                    <label class="sell-form__label" for="brand">ブランド名</label>
                    <input class="sell-form__input" type="text" name="brand" id="brand" value="{{ old('brand') }}">
                </div>
                <!--商品の説明-->
                <div class="sell-form__group">
                    <label class="sell-form__label" for="description">商品の説明</label>
                    <textarea class="sell-form__textarea" name="description"
                        id="description">{{ old('description') }}</textarea>
                </div>
                <!--販売価格-->
                <div class="sell-form__group">
                    <label class="sell-form__label" for="price">販売価格</label>
                    <input class="sell-form__input" type="number" name="price" id="price" value="{{ old('price') }}"
                        placeholder="¥">
                </div>
            </div>

            <button class="sell-form__btn btn" type="submit">出品する</button>
        </form>
    </div>
@endsection