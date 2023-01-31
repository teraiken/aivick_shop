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
                                <x-text-input type="text" id="name" name="name" value="{{ old('name') }}" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label>画像</x-input-label><br>
                                <input type="file" name="image">
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="introduction">説明文</x-input-label>
                                <x-textarea id="introduction" name="introduction">{{ old('introduction') }}</x-textarea>
                                <x-input-error :messages="$errors->get('introduction')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="price">税抜き価格</x-input-label>
                                <x-text-input type="number" id="price" name="price" value="{{ old('price') }}" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="stock">在庫数</x-input-label>
                                <x-text-input type="number" id="stock" name="stock" value="{{ old('stock') }}" />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label>ステータス</x-input-label><br>
                                @foreach($productStatuses as $productStatus)
                                <input type="radio" name="status" value="{{ $productStatus->value }}" {{
                                    old('status')==$productStatus->value ? 'checked' : '' }}>{{ $productStatus->label()
                                }}
                                @endforeach
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
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
</x-admin-layout>