<x-app-layout>
    <x-slot name="header">
        @if ($card)
        配送先入力
        @endif
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <div class="container px-5 mx-auto">
            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                @if ($card)
                <form method="post" action="{{ route('orders.confirm')}}">
                    @csrf
                    <div class="flex flex-wrap -m-2 pb-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label>配送先</x-input-label><br>
                                <input type="radio" id="registeredAddress" name="selectedAddress"
                                    value="registeredAddress" {{ old('address')=="registeredAddress" ? 'checked' : '' }}
                                    checked>登録済住所から選択
                                <input type="radio" id="newAddress" name="selectedAddress" value="newAddress" {{
                                    old('address')=="newAddress" ? 'checked' : '' }} required>新しい配送先
                            </div>
                        </div>

                        <div class="p-2 w-full" id="addressSelectBox">
                            <div class="relative">
                                <select id="addressId" name="addressId" required>
                                    <option value="">選択してください</option>
                                    @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" {{ old('addressId')==$address->id ? 'selected' :
                                        '' }}>〒{{ substr_replace($address->postal_code, '-', 3, 0) }}<br>
                                        {{ $address->pref->name }}{{ $address->address1 }}…<br>
                                        {{ $address->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap -m-2 pb-2" id="addressForm" style="display: none">
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
                                <x-text-input type="text" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code') }}" placeholder="1000001"
                                    onKeyUp="AjaxZip3.zip2addr(this,'','pref_id','address1','address2');" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="pref_id">都道府県</x-input-label><br>
                                <select id="pref_id" name="pref_id">
                                    <option value="">選択してください</option>
                                    @foreach($prefs as $pref)
                                    <option value="{{ $pref->id }}" {{ old('pref_id')==$pref->id ? 'selected' : '' }}>
                                        {{ $pref->name }}</option>
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
                                <x-text-input type="text" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" placeholder="08000000000" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full text-center">
                            <x-primary-button>
                                確認画面へ進む
                            </x-primary-button>
                        </div>
                    </div>
                </form>
                @else
                <div class="text-red-500">
                    {{ __('card.none') }}<br>
                    <a href="{{ route('card.show') }}" class="text-blue-500 hover:underline">こちら</a>からカードを登録してください。
                </div>
                @endif
            </div>
        </div>
    </section>
    @push('script')
    @vite(['resources/js/address.js'])
    @endpush
</x-app-layout>