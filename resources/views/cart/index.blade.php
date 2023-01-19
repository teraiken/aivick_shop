<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                    @if (session('errorMessage'))
                    <x-error-message class="mb-8">
                        {{ session('errorMessage') }}
                    </x-error-message>
                    @endif

                    @if (!is_array(session('cart')))
                        <div class="text-red-500">
                            {{ __('cart.none') }}
                        </div>
                    @else
                        <div class="text-right">
                            <form method="post" name="destroyForm" action="{{ route('cart.destroy')}}">
                                @csrf
                                <a href="javascript:destroyForm.submit()" class="text-red-500" onclick="return confirm('本当に削除しますか？')">カートを空にする</a>
                            </form>
                        </div>

                        <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品名</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">価格(税込)</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">個数</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">小計</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach(session('cart') as $product)
                                <tr>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
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
                                    </td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
                                        ¥{{ number_format(App\Helpers\Tax::add($product['price'])) }}
                                    </td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
                                        <form method="post" action="{{ route('cart.update')}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <select name="quantity" required onchange="submit(this.form)">
                                            @for($quantity = 1; $quantity <= \App\Models\Product::find($product['id'])->stock; $quantity++):
                                                <option value="{{ $quantity }}" {{ $product['quantity'] == $quantity ? 'selected' : ''}}>{{ $quantity }}</option>
                                            @endfor;
                                            </select>
                                        </form>
                                    </td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
                                        ¥{{ number_format(App\Helpers\Tax::add($product['price'] * $product['quantity'])) }}
                                    </td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
                                        <form method="post" action="{{ route('cart.remove')}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <x-primary-button>削除</x-prima-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3">
                                        ¥{{ number_format(App\Helpers\Tax::add(App\Helpers\Sum::array(session('cart')))) }}
                                    </td>
                                    <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>