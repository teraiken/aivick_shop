<x-admin-layout>
    <x-slot name="header">
        新規作成
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <form method="POST" action="{{ route('admin.shippingFees.store', ['area' => $area->id]) }}">
            @csrf
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="name">エリア名</x-input-label>
                                <x-text-show>{{ $area->name }}</x-text-show>
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="fee">送料</x-input-label>
                                <x-text-input id="fee" type="text" name="fee" :value="old('fee')" required />
                                <x-input-error :messages="$errors->get('fee')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="start_date">適用開始日</x-input-label>
                                <x-text-input id="start_date" type="date" name="start_date" :value="old('start_date')"
                                    required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="end_date">適用終了日</x-input-label>
                                <x-text-input id="end_date" type="date" name="end_date" :value="old('end_date')" />
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
</x-admin-layout>