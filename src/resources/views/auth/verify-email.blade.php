@extends('layouts.app')

@section('title', 'メール認証誘導画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">

@endsection

@section('content')
    <div class="verify-email">
        @if (session('status') == 'verification-link-sent')
            <div class="verify-email__alert verify-email__alert--success">
                新しい認証リンクをご登録のメールアドレスに送信しました。
            </div>
        @endif

        {{-- 案内文 --}}
        <div class="verify-email__text-group">
            <p class="verify-email__text">登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p class="verify-email__text">メール認証を完了してください。</p>
        </div>

        {{-- メール認証画面へ遷移 --}}
        <div class="verify-email__group">
            <a class="verify-email__btn verify-email__btn--primary" href="http://localhost:8025" target="_blank">
                認証はこちらから
            </a>
        </div>

        {{-- 認証メール再送信機能 --}}
        <form action="{{ route('verification.send') }}" class="verify-email__form" method="POST">
            @csrf
            <div class="verify-email__group">
                <button class="verify-email__link-btn" type="submit">
                    認証メールを再送する
                </button>
            </div>
        </form>
    </div>

@endsection