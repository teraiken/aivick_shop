<x-admin-layout>
    <x-slot name="header">
        新規作成
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <form method="post" action="{{ route('admin.products.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="name">商品名</x-input-label>
                                <x-text-input type="text" id="name" name="name" value="{{ old('name') }}" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label>画像</x-input-label><br>
                                <input type="file" id="image" name="image" required>
                                <img id="productImage">
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="introduction">説明文</x-input-label>
                                <x-textarea id="introduction" name="introduction" required>{{ old('introduction') }}
                                </x-textarea>
                                <x-input-error :messages="$errors->get('introduction')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="price">税抜き価格</x-input-label>
                                <x-text-input type="text" id="price" name="price" value="{{ old('price') }}" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="stock">在庫数</x-input-label>
                                <x-text-input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                                    required />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="start_date">販売開始日</x-input-label>
                                <x-text-input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="end_date">販売終了日</x-input-label>
                                <x-text-input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full text-center">
                            <x-danger-button>
                                新規登録する
                            </x-danger-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    @push('script')
    @vite(['resources/js/product.js'])
    @endpush
</x-admin-layout>