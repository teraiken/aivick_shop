<x-admin-layout>
    <x-slot name="header">
        商品一覧
    </x-slot>

    <div class="text-right">
        <a href="{{ route('admin.products.create') }}" class="text-blue-500">新規登録</a>
    </div>

    <section class="text-gray-600 body-font overflow-hidden">
        <div class="container px-5 mx-auto">
            <form class="mb-8 text-right" method="get" action="{{ route('admin.products.index') }}">
                <x-search-form search="{{ $search }}"></x-search-form>
            </form>

            <div class="-my-8 divide-y-2 divide-gray-100">
                @foreach($products as $product)
                <div class="py-8 md:flex">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <img src="{{ asset('storage/image/' . $product->image) }}">
                        <span class="mt-1 text-gray-500 text-sm">{{ $product->created_at->format('Y/m/d') }}</span>
                    </div>
                    <div class="md:flex-grow relative pb-10 md:ml-2">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">{{ $product->name }}</h2>
                        <p class="leading-relaxed">{{ $product->introduction }}</p>
                        <a href="{{ route('admin.products.show', ['product' => $product->id]) }}"
                            class="text-indigo-500 inline-flex items-center absolute bottom-0">詳細を見る
                            <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{ $products->links() }}
    </section>
</x-admin-layout>