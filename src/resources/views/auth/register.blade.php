@extends('layouts.app')

@section('title', '会員登録画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-form">
        <h1 class="register-form__heading">会員登録</h1>
        <div class="register-form__inner">
            <form class="register-form__form" action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <!--ユーザー名-->
                <div class="register-form__group">
                    <label class="register-form__label" for="name">ユーザー名</label>
                    <input class="register-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                    <p class="register-form__error-message">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <!--メールアドレス-->
                <div class="register-form__group">
                    <label class="register-form__label" for="email">メールアドレス</label>
                    <input class="register-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
                    <p class="register-form__error-message">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <!--パスワード-->
                <div class="register-form__group">
                    <label class="register-form__label" for="password">パスワード</label>
                    <input class="register-form__input" type="password" name="password" id="password">
                    <p class="register-form__error-message">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <!--確認用パスワード-->
                <div class="register-form__group">
                    <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
                    <input class="register-form__input" type="password" name="password_confirmation"
                        id="password_confirmation">
                    <p class="register-form__error-message">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <button class="register-form__btn btn" type="submit">登録する</button>
            </form>
            <div class="register-form__login-item">
                <a class="register-form__link" href="{{ route('login') }}">ログインはこちら</a>
            </div>
        </div>
    </div>
@endsection