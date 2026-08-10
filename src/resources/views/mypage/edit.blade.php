@extends('layouts.app')

@section('title', $profile ? 'プロフィール編集画面' : 'プロフィール設定画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')
    <div class="edit-form">
        <h1 class="edit-form__heading">
            {{ ($profile) ? 'プロフィール編集' : 'プロフィール設定' }}
        </h1>
        <div class="edit-form__inner">
            <form class="edit-form__form"
                action="{{ $profile ? route('mypage.profile.update') : route('mypage.profile.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                @if ($profile)
                    @method('PUT')
                @endif

                {{-- プロフィール画像 --}}
                <div class="edit-form__group">
                    <div class="profile-image">
                        <div class="profile-image__preview {{ $profile && $profile->img_url ? '' : 'is-empty' }}">
                            @if ($profile && $profile->img_url)
                                <img id="preview" src="{{ asset('storage/' . $profile->img_url) }}" alt="">
                            @else
                                <img id="preview" src="" alt="" style="display: none;">
                            @endif
                        </div>

                        <div class="profile-image__upload">
                            <label class="profile-image__label" for="image">画像を選択する</label>
                            <input class="profile-image__input" type="file" name="img_url" id="image" accept="image/*"
                                onchange="previewImage(this);">
                        </div>
                    </div>
                </div>

                {{-- ユーザー名 --}}
                <div class="edit-form__group">
                    <label class="edit-form__label" for="name">ユーザー名</label>
                    <input class="edit-form__input" type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 郵便番号 --}}
                <div class="edit-form__group">
                    <label class="edit-form__label" for="postcode">郵便番号</label>
                    <input class="edit-form__input" type="text" name="postcode" id="postcode"
                        value="{{ old('postcode', $user->profile->postcode ?? '') }}">
                    @error('postcode')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 住所 --}}
                <div class="edit-form__group">
                    <label class="edit-form__label" for="address">住所</label>
                    <input class="edit-form__input" type="text" name="address" id="address"
                        value="{{ old('address', $user->profile->address ?? '') }}">
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 建物名 --}}
                <div class="edit-form__group">
                    <label class="edit-form__label" for="building">建物名</label>
                    <input class="edit-form__input" type="text" name="building" id="building"
                        value="{{ old('building', $user->profile->building ?? '') }}">
                </div>

                <button class="edit-form__btn btn" type="submit">更新する</button>
            </form>
        </div>
    </div>
@endsection