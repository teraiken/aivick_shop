<x-app-layout>
    <x-slot name="header">
        商品一覧
    </x-slot>

    <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">
            @if (session('successMessage'))
            <x-success-message class="mb-8">
                {{ session('successMessage') }}
            </x-success-message>
            @endif

            @if (session('errorMessage'))
            <x-error-message class="mb-8">
                {{ session('errorMessage') }}
            </x-error-message>
            @endif

            <form class="mb-8 text-right" method="get" action="{{ route('products.index') }}">
                <x-search-form search="{{ $search }}">
                    <select class="ml-4" name="sortType">
                        <option value="popular" {{ $sortType==="popular" ? 'selected' : '' }}>人気順</option>
                        <option value="price" {{ $sortType==="price" ? 'selected' : '' }}>価格順</option>
                        <option value="new" {{ $sortType==="new" ? 'selected' : '' }}>新着順</option>
                    </select>
                </x-search-form>
            </form>

            <div class="flex flex-wrap -m-4">
                @foreach($products as $product)
                <div class="p-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden relative pb-10">
                        <img class="lg:h-48 md:h-36 w-full object-cover object-center"
                            src="{{ asset('storage/image/' . $product->image) }}" alt="blog">
                        <div class="p-6">
                            <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $product->name }}</h1>
                            <p class="leading-relaxed mb-3">{{ $product->introduction }}</p>
                            <p class="leading-relaxed mb-3">¥{{ number_format($product->price) }} (税込¥{{
                                number_format(App\Helpers\Tax::add($product->price)) }})</p>
                        </div>
                        <div class="absolute bottom-0 p-6">
                            <?php
                            $cartQuantity = array_key_exists($product->id, session('cart', [])) ? session('cart')[$product->id]['quantity'] : 0;
                            $availableQuantity = $product->stock - $cartQuantity;
                            ?>

                            @if ($availableQuantity !== 0)
                            <form method="post" action="{{ route('cart.add')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <select name="quantity" required>
                                    <option value="">個数</option>
                                    @for($quantity = 1; $quantity <= $availableQuantity; $quantity++): <option
                                        value="{{ $quantity }}">{{ $quantity }}</option>
                                        @endfor;
                                </select>
                                <x-primary-button class="ml-4">カートに入れる</x-primary-button>
                            </form>
                            @else
                            <p class="leading-relaxed">SOLD OUT</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>