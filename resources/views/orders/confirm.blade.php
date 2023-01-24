<?php

use App\Helpers\Tax;
use App\Helpers\Calculator;

?>

<x-app-layout>
    <x-slot name="header">
        確認画面
    </x-slot>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        @if (session('errorMessages'))
        <x-error-message class="mb-8">
            @foreach (session('errorMessages') as $errorMessage)
            {{ $errorMessage }}<br>
            @endforeach
        </x-error-message>
        @endif

        @if (empty(session('cart')))
        <div class="text-red-500">
            {{ __('cart.none') }}
        </div>
        @else
        <table class="table-auto w-full text-left whitespace-no-wrap mb-6">
            <thead>
                <tr>
                    <x-th>商品名</x-th>
                    <x-th>価格(税込)</x-th>
                    <x-th>個数</x-th>
                    <x-th>小計</x-th>
                </tr>
            </thead>

            <tbody>
                @foreach(session('cart') as $product)
                <tr>
                    <x-td>
                        <section class="text-gray-600 body-font overflow-hidden">
                            <div class="container mx-auto">
                                <div class="-my-8 divide-y-2 divide-gray-100">
                                    <div class="py-8 md:flex">
                                        <div class="w-16 flex-shrink-0 flex flex-col">
                                            <img src="{{ asset('storage/image/' . $product['image']) }}">
                                        </div>
                                        <div class="md:flex-grow md:ml-2 leading-10">
                                            {{ $product['name'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </x-td>
                    <x-td>¥{{ number_format($price = Tax::add($product['price'])) }}</x-td>
                    <x-td>{{ $product['quantity'] }}</x-td>
                    <x-td>¥{{ number_format($price * $product['quantity']) }}</x-td>
                </tr>
                @endforeach
                <tr>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td>¥{{ number_format($sum = Tax::add(Calculator::arraySum(session('cart')))) }}</x-td>
                </tr>

                <tr>
                    <td class="px-4 py-3"></td>
                    <x-th>送料</x-th>
                    <x-td></x-td>
                    <x-td>¥{{ number_format($shipping = config('shipping_fee')[session('address')['pref_id']]) }}</x-td>
                </tr>

                <tr>
                    <td class="px-4 py-3"></td>
                    <x-th>合計</x-th>
                    <x-td class="border-b-2"></x-td>
                    <x-td class="border-b-2">¥{{ number_format($sum + $shipping) }}</x-td>
                </tr>
            </tbody>
        </table>

        <table class="table-auto w-full text-left whitespace-no-wrap mb-6">
            <caption class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                配送先
            </caption>
            <tr>
                <x-th>郵便番号</x-th>
                <x-td>〒{{ substr_replace(session('address')['postal_code'], '-', 3, 0) }}</x-td>
            </tr>
            <tr>
                <x-th>住所</x-th>
                <x-td>
                    {{ config('pref')[session('address')['pref_id']] }}{{ session('address')['address1'] }}<br>
                    {{ session('address')['address2'] }}
                </x-td>
            </tr>
            <tr>
                <x-th>宛名</x-th>
                <x-td>{{ session('address')['name'] }}</x-td>
            </tr>
            <tr>
                <x-th>電話番号</x-th>
                <x-td class="border-b-2">{{ session('address')['phone_number'] }}</x-td>
            </tr>
        </table>

        <div class="text-center">
            <form method="post" action="{{ route('orders.store') }}">
                @csrf
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('STRIPE_PUBLIC_KEY') }}" data-amount="{{ $sum + $shipping }}"
                    data-name="Stripe Demo" data-label="注文を確定する" data-description="これはデモ決済です"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto"
                    data-currency="JPY">
                </script>
            </form>
        </div>
        @endif
    </div>
    @push('style')
    @vite(['resources/css/style.css'])
    @endpush
</x-app-layout>