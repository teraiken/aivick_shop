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
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="fee">送料</x-input-label>
                                <x-text-input id="fee" type="text" name="fee" :value="$shippingFee->fee" required />
                                <x-input-error :messages="$errors->get('fee')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="start_date">適用開始日</x-input-label>
                                <x-text-input id="start_date" type="date" name="start_date"
                                    :value="$shippingFee->start_date->format('Y-m-d')" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="end_date">適用終了日</x-input-label>
                                <x-text-input id="end_date" type="date" name="end_date"
                                    :value="$shippingFee->end_date ? $shippingFee->end_date->format('Y-m-d') : ''" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
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