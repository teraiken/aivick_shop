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

            <form class="mb-8 flex justify-between" method="get" action="{{ route('products.index') }}">
                <select name="sortType" onchange="submit(this.form)">
                    <option value="popular" {{ $sortType==="popular" ? 'selected' : '' }}>人気順</option>
                    <option value="price" {{ $sortType==="price" ? 'selected' : '' }}>価格順</option>
                    <option value="new" {{ $sortType==="new" ? 'selected' : '' }}>新着順</option>
                </select>

                <x-search-form search="{{ $search }}"></x-search-form>
            </form>

            <div class="flex flex-wrap -m-4">
                @foreach($products as $product)
                <div class="p-4 lg:w-1/3 md:w-1/2 w-full">
                    <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                        <img class="lg:h-48 md:h-36 w-full object-cover object-center"
                            src="{{ asset('storage/image/' . $product->image) }}" alt="blog">
                        <div class="p-6">
                            <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $product->name }}</h1>
                            <p class="leading-relaxed mb-3">¥{{ number_format($product->price) }} (税込¥{{
                                number_format(App\Helpers\Tax::add($product->price)) }})</p>
                            <a class="text-indigo-500 inline-flex items-center mb-3"
                                href="{{ route('products.show', ['product' => $product->id]) }}">詳細を見る
                                <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            @php
                            $cartQuantity = array_key_exists($product->id, session('cart', [])) ?
                            session('cart')[$product->id]['quantity'] : 0;
                            $availableQuantity = $product->stock - $cartQuantity;
                            @endphp

                            @if ($availableQuantity !== 0)
                            <form class="addCartForm" method="post" action="{{ route('cart.add')}}">
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
                            <p class="leading-relaxed">SOLD OUT</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @push('script')
    @vite(['resources/js/cart.js'])
    @endpush
</x-app-layout>