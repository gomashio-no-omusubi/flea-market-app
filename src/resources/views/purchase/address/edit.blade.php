@extends('layouts.app')

@section('title', '送付先住所変更画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/address/edit.css') }}">
@endsection

@section('content')
    <div class="address-form">
        <h1 class="address-form__heading">住所の変更</h1>
        <div class="address-form__inner">
            <form class="address-form__form" action="{{ route('purchase.address.store', ['item_id' => $item->id]) }}"
                method="POST">
                @csrf
                {{-- 郵便番号 --}}
                <div class="address-form__group">
                    <label class="address-form__label" for="postcode">郵便番号</label>
                    <input class="address-form__input" type="text" name="postcode" id="postcode"
                        value="{{ old('postcode') }}">
                    @error('postcode')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                {{-- 住所 --}}
                <div class="address-form__group">
                    <label class="address-form__label" for="address">住所</label>
                    <input class="address-form__input" type="text" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                {{-- 建物名 --}}
                <div class="address-form__group">
                    <label class="address-form__label" for="building">建物名</label>
                    <input class="address-form__input" type="text" name="building" id="building"
                        value="{{ old('building') }}">
                </div>

                <button class="address-form__btn btn" type="submit">更新する</button>
            </form>
        </div>
    </div>
@endsection