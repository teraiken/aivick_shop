<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            配送先一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-right">
                        <a href="{{ route('addresses.create') }}" class="text-blue-500">新規登録</a>
                    </div>

                    <section class="text-gray-600 body-font overflow-hidden">
                    <div class="container px-5 py-24 mx-auto">
                        <div class="-my-8 divide-y-2 divide-gray-100">
                        @foreach($addresses as $address)
                        <div class="py-8">
                            <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">{{ $address->name }}</h2>
                            <p class="leading-relaxed">〒{{ substr_replace($address->postal_code, '-', 3, 0) }}</p>
                            <p class="leading-relaxed">{{ config('pref')[$address->pref_id] }}{{ $address->address1 }}{{ $address->address2 }}</p>
                            <p class="leading-relaxed">{{ $address->phone_number }}</p>
                            <div class="p-2 flex">
                                <form method="get" action="{{ route('addresses.edit', ['address' => $address->id]) }}">
                                    <x-blue-button>
                                        編集
                                    </x-blue-button>
                                </form>

                                <form class="ml-4" method="post" action="{{ route('addresses.destroy', ['address' => $address->id]) }}">
                                    @csrf
                                    @method('delete')
                                    
                                    <x-danger-button onclick="deleteAlert(event);return false;">
                                        削除
                                    </x-danger-button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                        </div>
                    </div>
                    {{ $addresses->links() }}
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
