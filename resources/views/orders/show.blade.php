<?php

use App\Helpers\Calculator;

?>

<x-app-layout>
    <x-slot name="header">
        詳細画面
    </x-slot>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap mb-6">
            <tr>
                <x-th>注文日</x-th>
                <x-td class="border-b-2">{{ $order->created_at->format('Y/m/d') }}</x-td>
            </tr>
        </table>

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
                @foreach($orderDetails as $orderDetail)
                <tr>
                    <x-td>
                        <section class="text-gray-600 body-font overflow-hidden">
                            <div class="container mx-auto">
                                <div class="-my-8 divide-y-2 divide-gray-100">
                                    <div class="py-8 md:flex">
                                        <div class="w-16 flex-shrink-0 flex flex-col">
                                            <img src="{{ asset('storage/image/' . $orderDetail->product->image) }}">
                                        </div>
                                        <div class="md:flex-grow md:ml-2 leading-10">
                                            {{ $orderDetail->product->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </x-td>
                    <x-td>¥{{ number_format($price = $orderDetail->price * ($orderDetail->tax_rate + 100) / 100) }}
                    </x-td>
                    <x-td>{{ $orderDetail->quantity }}</x-td>
                    <x-td>¥{{ number_format($price * $orderDetail->quantity) }}</x-td>
                </tr>
                @endforeach
                <tr>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td>¥{{ number_format(Calculator::orderSum($order) - $order->shipping_fee) }}</x-td>
                </tr>

                <tr>
                    <td class="px-4 py-3"></td>
                    <x-th>送料</x-th>
                    <x-td></x-td>
                    <x-td>¥{{ number_format($order->shipping_fee) }}</x-td>
                </tr>

                <tr>
                    <td class="px-4 py-3"></td>
                    <x-th>合計</x-th>
                    <x-td class="border-b-2"></x-td>
                    <x-td class="border-b-2">¥{{ number_format(Calculator::orderSum($order)) }}</x-td>
                </tr>
            </tbody>
        </table>

        <table class="table-auto w-full text-left whitespace-no-wrap">
            <caption class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                配送先
            </caption>
            <tr>
                <x-th>郵便番号</x-th>
                <x-td>〒{{ substr_replace($order->postal_code, '-', 3, 0) }}</x-td>
            </tr>
            <tr>
                <x-th>住所</x-th>
                <x-td>
                    {{ config('pref')[$order->pref_id] }}{{ $order->address1 }}<br>
                    {{ $order->address2 }}
                </x-td>
            </tr>
            <tr>
                <x-th>宛名</x-th>
                <x-td>{{ $order->name }}</x-td>
            </tr>
            <tr>
                <x-th>電話番号</x-th>
                <x-td class="border-b-2">{{ $order->phone_number }}</x-td>
            </tr>
        </table>
    </div>
</x-app-layout>