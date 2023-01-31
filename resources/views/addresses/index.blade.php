<x-app-layout>
    <x-slot name="header">
        配送先一覧
    </x-slot>

    <div class="text-right">
        <a href="{{ route('addresses.create') }}" class="text-blue-500">新規登録</a>
    </div>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>住所</x-th>
                    <x-th>宛名</x-th>
                    <x-th>電話番号</x-th>
                    <x-th></x-th>
                </tr>
            </thead>

            <tbody>
                @foreach ($addresses as $address)
                <tr>
                    <x-td>〒{{ substr_replace($address->postal_code, '-', 3, 0) }}<br>
                        {{ config('pref')[$address->pref_id] }}{{ $address->address1 }}…
                    </x-td>
                    <x-td>{{ $address->name }}</x-td>
                    <x-td>{{ $address->phone_number }}</x-td>
                    <x-td class="md:flex">
                        <form method="get" action="{{ route('addresses.edit', ['address' => $address->id]) }}">
                            <x-primary-button>
                                編集
                            </x-primary-button>
                        </form>

                        <form class="md:ml-4 md:mt-0 mt-2" method="post"
                            action="{{ route('addresses.destroy', ['address' => $address->id]) }}"
                            onsubmit="return confirm('本当に削除しますか？')">
                            @csrf
                            @method('delete')

                            <x-danger-button>
                                削除
                            </x-danger-button>
                        </form>
                    </x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $addresses->links() }}
    </div>
</x-app-layout>