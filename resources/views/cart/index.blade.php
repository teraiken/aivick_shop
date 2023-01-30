<?php

use App\Models\Product;
use App\Helpers\Tax;
use App\Helpers\Calculator;

?>

<x-app-layout>
    <x-slot name="header">
        カート
    </x-slot>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        @if (session('errorMessage'))
        <x-error-message class="mb-8">
            {{ session('errorMessage') }}
        </x-error-message>
        @endif

        @if (empty(session('cart')))
        <div class="text-red-500">
            {{ __('cart.none') }}
        </div>
        @else
        <div class="text-right">
            <form method="post" name="destroyForm" action="{{ route('cart.destroy')}}">
                @csrf
                <a href="javascript:destroyForm.submit()" class="text-red-500"
                    onclick="return confirm('本当に削除しますか？')">カートを空にする</a>
            </form>
        </div>

        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>商品名</x-th>
                    <x-th>価格(税込)</x-th>
                    <x-th>個数</x-th>
                    <x-th>小計</x-th>
                    <x-th></x-th>
                </tr>
            </thead>

            <tbody>
                @foreach(session('cart') as $product)
                <tr>
                    <x-td>
                        <a href="{{ route('products.show', ['product' => $product['id']]) }}">
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
                        </a>
                    </x-td>
                    <x-td>
                        ¥{{ number_format($price = Tax::add($product['price'])) }}
                    </x-td>
                    <x-td>
                        <form method="post" action="{{ route('cart.update')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                            <select name="quantity" required onchange="submit(this.form)">
                                @for($quantity = 1; $quantity <= Product::find($product['id'])->stock; $quantity++):
                                    <option value="{{ $quantity }}" {{ $product['quantity']==$quantity ? 'selected' : ''
                                        }}>{{ $quantity }}</option>
                                    @endfor;
                            </select>
                        </form>
                    </x-td>
                    <x-td>
                        ¥{{ number_format($price * $product['quantity']) }}
                    </x-td>
                    <x-td>
                        <form method="post" action="{{ route('cart.remove')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                            <x-small-danger-button>削除</x-small-danger-button>
                        </form>
                    </x-td>
                </tr>
                @endforeach
                <tr>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td></x-td>
                    <x-td>
                        ¥{{ number_format(Tax::add(Calculator::arraySum(session('cart')))) }}
                    </x-td>
                    <x-td></x-td>
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <x-primary-button onclick="location.href='{{ route('orders.create') }}'">ご注文手続きへ</x-primary-button>
        </div>
        @endif
    </div>
</x-app-layout>