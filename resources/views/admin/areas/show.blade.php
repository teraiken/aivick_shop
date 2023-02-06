<x-admin-layout>
    <x-slot name="header">
        詳細画面
    </x-slot>

    <section class="text-gray-600 body-font relative mb-6">
        <form method="POST" action="{{ route('admin.areas.update', ['area' => $area->id]) }}">
            @csrf
            @method('patch')
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="name">エリア名</x-input-label>
                                <x-text-input id="name" type="text" name="name" :value="$area->name" required
                                    autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full text-center">
                            <x-primary-button>編集</x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form class="text-center mt-2" method="post" action="{{ route('admin.areas.destroy', ['area' => $area->id]) }}"
            onsubmit="return confirm('本当に削除しますか？')">
            @csrf
            @method('delete')

            <x-danger-button>削除</x-danger-button>
        </form>
    </section>

    <div class="lg:w-1/2 md:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>送料</x-th>
                    <x-th>適用開始日</x-th>
                    <x-th>適用終了日</x-th>
                </tr>
            </thead>

            <tbody>
                @foreach($shippingFees as $shippingFee)
                <tr>
                    <x-td>¥{{ number_format($shippingFee->fee) }}</x-td>
                    <x-td>{{ $shippingFee->start_date->format('Y/m/d') }}</x-td>
                    <x-td>
                        @php
                        $end_date = $shippingFee->end_date;
                        @endphp
                        @if (!is_Null($end_date))
                        {{ $end_date->format('Y/m/d') }}
                        @else
                        <form method="get"
                            action="{{ route('admin.shippingFees.edit', ['shippingFee' => $shippingFee->id]) }}">
                            <x-primary-button>編集</x-primary-button>
                        </form>
                        @endif
                    </x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>