@extends('layouts.app')

@section('title', 'ログイン画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-form">
        <h1 class="login-form__heading">ログイン</h1>
        <div class="login-form__inner">
            <form class="login-form__form" action="{{ route('login') }}" method="POST" novalidate>
                @csrf
                {{-- メールアドレス --}}
                <div class="login-form__group">
                    <label class="login-form__label" for="email">メールアドレス</label>
                    <input class="login-form__input" type="text" name="email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                {{-- パスワード --}}
                <div class="login-form__group">
                    <label class="login-form__label" for="password">パスワード</label>
                    <input class="login-form__input" type="password" name="password" id="password">
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button class="login-form__btn btn" type="submit">ログインする</button>
            </form>
            <div class="login-form__login-item">
                <a class="login-form__link" href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </div>
    </div>
@endsection