<x-admin-layout>
    <x-slot name="header">
        エリア一覧
    </x-slot>

    <div class="text-right">
        <a href="{{ route('admin.areas.create') }}" class="text-blue-500">新規登録</a>
    </div>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>エリア名</x-th>
                    <x-th>適用中の送料</x-th>
                    <x-th>適用開始日</x-th>
                    <x-th>適用終了日</x-th>
                    <x-th></x-th>
                </tr>
            </thead>

            <tbody>
                @foreach ($areas as $area)
                <tr>
                    <x-td>{{ $area->name }}</x-td>
                    @if (is_null($area->currentShippingFee))
                    <x-td colspan="3">{{ __('shippingFee.current_none') }}</x-td>
                    @else
                    <x-td>¥{{ number_format($area->currentShippingFee->fee) }}</x-td>
                    <x-td>{{ $area->currentShippingFee->start_date->format('Y/m/d') }}</x-td>
                    <x-td>
                        @php
                        $end_date = $area->currentShippingFee->end_date;
                        @endphp
                        @if (!is_null($end_date))
                        {{ $end_date->format('Y/m/d') }}
                        @endif
                    </x-td>
                    @endif
                    <x-td>
                        <x-primary-button
                            onclick="location.href='{{ route('admin.areas.show', ['area' => $area->id]) }}'">
                            詳細
                        </x-primary-button>
                    </x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $areas->links() }}
    </div>
</x-admin-layout>