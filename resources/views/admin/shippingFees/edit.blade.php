<x-admin-layout>
    <x-slot name="header">
        編集画面
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <form method="POST" action="{{ route('admin.shippingFees.update', ['shippingFee' => $shippingFee->id]) }}">
            @csrf
            @method('patch')
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="start_date">適用開始日</x-input-label>
                                <input id="start_date" type="hidden" name="start_date"
                                    value="{{ $shippingFee->start_date }}">
                                <x-text-show>{{ $shippingFee->start_date }}</x-text-show>
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="end_date">適用終了日</x-input-label>
                                <x-text-input id="end_date" type="date" name="end_date" :value="old('end_date')"
                                    required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="fee">新たな送料</x-input-label>
                                <x-text-input id="fee" type="text" name="fee" :value="old('fee')" required />
                                <x-input-error :messages="$errors->get('fee')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full text-center">
                            <x-danger-button>
                                更新する
                            </x-danger-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>