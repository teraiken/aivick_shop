<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section class="text-gray-600 body-font">
                    <div class="container px-5 mx-auto">
                        <form class="mb-8" method="get" action="{{ route('products.index') }}">
                            <x-search-form></x-search-form>
                        </form>
                        
                        <div class="flex flex-wrap -m-4">
                        @foreach($products as $product)
                        <div class="p-4 md:w-1/2 lg:w-1/3">
                            <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden relative pb-10">
                                <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="{{ asset('storage/image/' . $product->image) }}" alt="blog">
                                <div class="p-6">
                                    <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $product->name }}</h1>
                                    <p class="leading-relaxed mb-3">{{ $product->introduction }}</p>
                                    <p class="leading-relaxed mb-3">¥{{ number_format($product->price) }} (税込¥{{ number_format(App\Helpers\Tax::add($product->price)) }})</p>
                                </div>
                                <div class="absolute bottom-0 p-6">
                                @if ($product->stock !== 0)
                                    <form method="post" action="{{ route('cart.add')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <select name="quantity" required>
                                            <option value="">個数</option>
                                            @for($quantity = 1; $quantity <= $product->stock; $quantity++):
                                                <option value="{{ $quantity }}">{{ $quantity }}</option>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
