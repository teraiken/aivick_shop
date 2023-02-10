<x-admin-layout>
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
                            <x-input-label for="price">税抜き価格</x-input-label>
                            <x-text-show>{{ $product->price }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="stock">在庫数</x-input-label>
                            <x-text-show>{{ $product->stock }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label for="start_date">販売開始日</x-input-label>
                            <x-text-show>{{ $product->start_date->format('Y/m/d') }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label for="end_date">販売終了日</x-input-label>
                            <x-text-show>{{ $product->end_date ? $product->end_date->format('Y/m/d') : '　' }}
                            </x-text-show>
                        </div>
                    </div>

                    <div class="p-2 flex mx-auto">
                        <form method="get" action="{{ route('admin.products.edit', ['product' => $product->id]) }}">
                            <x-primary-button>
                                編集する
                            </x-primary-button>
                        </form>

                        <x-danger-button class="ml-4" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')">削除する
                        </x-danger-button>
                    </div>

                    <x-modal name="confirm-product-deletion">
                        <form method="post" action="{{ route('admin.products.destroy', ['product' => $product->id]) }}"
                            class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900">
                                本当に削除しますか？
                            </h2>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    キャンセル
                                </x-secondary-button>

                                <x-danger-button class="ml-3">
                                    削除する
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>