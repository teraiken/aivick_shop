<?php

use App\Helpers\Formatter;
use App\Helpers\Calculator;

?>

<x-app-layout>
    <x-slot name="header">
        注文一覧
    </x-slot>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>注文日</x-th>
                    <x-th>配送先</x-th>
                    <x-th>注文商品</x-th>
                    <x-th>支払金額</x-th>
                    <x-th></x-th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <x-td>
                        {{ $order->created_at->format('Y/m/d') }}
                    </x-td>
                    <x-td>
                        〒{{ substr_replace($order->postal_code, '-', 3, 0) }}<br>
                        {{ $order->pref->name }}{{ $order->address1 }}…<br>
                        {{ $order->name }}
                    </x-td>
                    <x-td>{{ Formatter::subject($order) }}</x-td>
                    <x-td>¥{{ number_format(Calculator::orderSum($order)) }}</x-td>
                    <x-td>
                        <x-primary-button onclick="location.href='{{ route('orders.show', ['order' => $order->id]) }}'">
                            詳細
                        </x-primary-button>
                    </x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</x-app-layout>