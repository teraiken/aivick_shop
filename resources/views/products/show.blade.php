<x-app-layout>
    <x-slot name="header">
        詳細画面
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <div class="container px-5 mx-auto">
            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="flex flex-wrap -m-2">
                    <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="name">商品名</x-input-label>
                            <x-text-show>{{ $product->name }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label>画像</x-input-label><br>
                            <img src="{{ asset('storage/image/' . $product->image) }}">
                        </div>
                    </div>

                    <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="introduction">説明文</x-input-label>
                            <x-text-show>{{ $product->introduction }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="price">価格</x-input-label>
                            <x-text-show>¥{{ number_format($product->price) }} (税込¥{{
                                number_format(App\Helpers\Tax::add($product->price)) }})</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-full text-center">
                        @php
                        $cartQuantity = array_key_exists($product->id, session('cart', [])) ?
                        session('cart')[$product->id]['quantity'] : 0;
                        $availableQuantity = $product->stock - $cartQuantity;
                        @endphp

                        @if ($availableQuantity !== 0)
                        <form method="post" action="{{ route('cart.add')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <select name="quantity" required>
                                @for($quantity = 1; $quantity <= $availableQuantity; $quantity++): <option
                                    value="{{ $quantity }}">{{ $quantity }}</option>
                                    @endfor;
                            </select>
                            <x-primary-button class="ml-4">カートに入れる</x-primary-button>
                        </form>
                        @else
                        <p>SOLD OUT</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>