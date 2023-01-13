<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新規作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">
                <form method="post" action="{{ route('addresses.store')}}">
                    @csrf
                <div class="container px-5 mx-auto">
                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="name">宛名</x-input-label>
                            <x-text-input type="text" id="name" name="name" value="{{ old('name') }}" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="postal_code">郵便番号</x-input-label>
                            <x-text-input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="1000001" onKeyUp="AjaxZip3.zip2addr(this,'','pref_id','address1','address2');" />
                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                        </div>
                        </div>

                        <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label for="pref_id">都道府県</x-input-label><br>
                            <select name="pref_id">
                                <option value="">選択してください</option>
                                @foreach(config('pref') as $pref_id => $name)
                                    <option value="{{ $pref_id }}" {{ old('pref_id') == $pref_id ? 'selected' : ''}}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pref_id')" class="mt-2" />
                        </div>
                        </div>
                        
                        <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label for="address1">市区町村</x-input-label>
                            <x-text-input type="text" id="address1" name="address1" value="{{ old('address1') }}" />
                            <x-input-error :messages="$errors->get('address1')" class="mt-2" />
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="address2">以降の住所</x-input-label>
                            <x-text-input type="text" id="address2" name="address2" value="{{ old('address2') }}" />
                            <x-input-error :messages="$errors->get('address2')" class="mt-2" />
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <x-input-label for="phone_number">電話番号</x-input-label>
                            <x-text-input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="08000000000" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                        </div>

                        <div class="p-2 w-full">
                            <x-blue-button class="flex mx-auto">
                                新規登録する
                            </x-blue-button>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
                </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
