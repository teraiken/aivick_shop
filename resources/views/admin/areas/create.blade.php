<x-admin-layout>
    <x-slot name="header">
        新規作成
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <form method="POST" action="{{ route('admin.areas.store') }}">
            @csrf
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="name">エリア名</x-input-label>
                                <x-text-input id="name" type="text" name="name" :value="old('name')" required
                                    autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="fee">送料</x-input-label>
                                <x-text-input id="fee" type="number" name="fee" :value="old('fee')" required />
                                <x-input-error :messages="$errors->get('fee')" class="mt-2" />
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