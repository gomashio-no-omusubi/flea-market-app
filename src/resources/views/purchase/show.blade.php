@extends('layouts.app')

@section('title', '商品購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/show.css') }}">
@endsection

@section('content')
    <div class="l-container">
        <div class="purchase-show">
            <div class="purchase-show__container">
                <form class="purchase-show__form" action="{{route('purchase.store', ['item_id' => $item->id])}}"
                    method="POST">
                    @csrf

                    {{-- 【左側メインエリア】 --}}
                    <div class="purchase-main">
                        <div class="purchase-show__image-box">
                            <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
                        </div>

                        {{-- 商品名・価格 --}}
                        <div class="purchase-show__content">
                            <h1 class="purchase-show__name">{{ $item->name }}</h1>
                            <p class="purchase-show__price">
                                <span class="currency-symbol">¥</span>{{ number_format($item->price) }}
                            </p>
                        </div>

                        {{-- 支払方法 --}}
                        <div class="purchase-section">
                            <h2 class="purchase-section__title">支払い方法</h2>
                            <select name="payment_method" id="payment-select" class="purchase-section__select"
                                onchange="this.form.submit()">
                                <option value="" {{ (old('payment_method') ?? $paymentMethod) == '' ? 'selected' : '' }}>
                                    選択してください</option>
                                <option value="コンビニ支払い" {{ (old('payment_method') ?? $paymentMethod) == 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                                <option value="カード支払い" {{ (old('payment_method') ?? $paymentMethod) == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                            </select>
                            </select>

                            <input type="hidden" name="submit_action" value="refresh">
                            <p class="error-message">
                                @error('payment_method')
                                    {{ $message }}
                                @enderror
                            </p>
                        </div>

                        {{-- 配送先 --}}
                        <div class="purchase-section">
                            <div class="purchase-section__header">
                                <h2 class="purchase-section__title">配送先</h2>
                                <a href="{{ route('purchase.address.edit', ['item_id' => $item->id])}}"
                                    class="purchase-section__link">変更する</a>
                            </div>

                            @if($address && $address->postcode && $address->address)
                                <input type="hidden" name="delivery_destination" value="registered">
                            @else
                                <input type="hidden" name="delivery_destination" value="">
                            @endif
                            <p class="error-message">
                                @error('delivery_destination')
                                    {{ $message }}
                                @enderror
                            </p>

                            <div class="purchase-address">
                                <p class="purchase-address__postcode">〒{{ $address?->postcode }}</p>
                                <p class="purchase-address__address">{{ $address?->address }}</p>
                                <p class="purchase-address__building">{{ $address?->building }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 【右側サイドナビエリア】 --}}
                    <div class="purchase-sidebar">
                        <table class="summary-table">
                            <tr class="summary-table__total">
                                <th>商品代金</th>
                                <td>¥{{ number_format($item->price) }}</td>
                            </tr>
                            <tr class="summary-payment-display">
                                <th>支払い方法</th>
                                <td id="summary-payment-display">
                                    {{ (old('payment_method') ?? $paymentMethod) ?: '選択してください' }}
                                </td>
                            </tr>
                        </table>

                        <button class="purchase-show__btn btn" type="submit" name="submit_action" value="buy">購入する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection