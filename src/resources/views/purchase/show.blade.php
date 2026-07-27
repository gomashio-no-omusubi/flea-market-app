@extends('layouts.app')

@section('title', '商品購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/show.css') }}">
@endsection

@section('content')
    <div class="purchase-show">
        <div class="purchase-show__container">
            <form class="purchase-show__form" action="{{route('purchase.store', ['item_id' => $item->id])}}" method="POST">
                @csrf

                <input type="hidden" name="address_id" value="{{ $address->id }}">

                <!-- 【左側メインエリア】 -->
                <div class="purchase-main">

                    <div class="purchase-show__image-box">
                        {{-- 画像がない場合の代替え画像や、storageからの取得を想定 --}}
                        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
                    </div>

                    <!--商品名・価格-->
                    <div class="purchase-show__content">
                        <h1 class="purchase-show__name">{{ $item->name }}</h1>
                        <p class="purchase-show__price">
                            <span class="currency-symbol">¥</span>{{ number_format($item->price) }}<span
                                class="tax-label">(税込)</span>
                        </p>
                    </div>

                    <!--支払方法-->
                    <div class="purchase-section">
                        <h2 class="purchase-section__title">支払い方法</h2>
                        <select name="payment_method" id="payment-select" class="purchase-section__select"
                            onchange="this.form.submit()">
                            <option value="" {{ old('payment_method') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="コンビニ支払い" {{ old('payment_method') == 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い
                            </option>
                            <option value="カード支払い" {{ old('payment_method') == 'カード支払い' ? 'selected' : '' }}>カード支払い
                            </option>
                        </select>

                        <input type="hidden" name="action" value="refresh">
                    </div>

                    <!--配送先-->
                    <div class="purchase-section">
                        <div class="purchase-section__header">
                            <h2 class="purchase-section__title">配送先</h2>
                            <a href="{{ route('purchase.address.edit', ['item_id' => $item->id])}}"
                                class="purchase-section__link">変更する</a>
                        </div>

                        <div class="purchase-address">
                            <p class="purchase-address__postcode">〒{{ $address?->postcode }}</p>
                            <p class="purchase-address__address">{{ $address?->address }}</p>
                            <p class="purchase-address__building">{{ $address?->building }}</p>
                        </div>
                    </div>
                </div>

                <!-- 【右側サイドナビエリア】（ここが縦潰れの原因） -->
                <div class="purchase-sidebar">
                    <table class="summary-table">
                        <tr>
                            <th>商品代金</th>
                            <td>¥{{ number_format($item->price) }}</td> <!-- コントローラーから渡ってきた変数 -->
                        </tr>
                        <tr>
                            <th>支払い方法</th>
                            <!-- ここにユーザーが選んだ値（またはold値）を出す -->
                            <td id="summary-payment-display">{{ old('payment_method') ?: '選択してください' }}</td>
                        </tr>
                    </table>

                    <button class="purchase-show__btn btn" type="submit" name="submit_action" value="buy">購入する</button>
                </div>
            </form>
        </div>
    </div>

@endsection