<x-app-layout>
    <x-slot name="header">
        編集画面
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <form method="post" action="{{ route('addresses.update', ['address' => $address->id]) }}">
            @csrf
            @method('patch')
            <div class="container px-5 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="name">宛名</x-input-label>
                                <x-text-input type="text" id="name" name="name" value="{{ $address->name }}" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="postal_code">郵便番号</x-input-label>
                                <x-text-input type="text" id="postal_code" name="postal_code"
                                    value="{{ $address->postal_code }}" required placeholder="1000001"
                                    onKeyUp="AjaxZip3.zip2addr(this,'','pref_id','address1','address2');" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="pref_id">都道府県</x-input-label><br>
                                <select name="pref_id" required>
                                    <option value="">選択してください</option>
                                    @foreach($prefs as $pref)
                                    <option value="{{ $pref->id }}" {{ $address->pref_id == $pref->id ?
                                        'selected' : ''}}>{{ $pref->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pref_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <x-input-label for="address1">市区町村</x-input-label>
                                <x-text-input type="text" id="address1" name="address1" value="{{ $address->address1 }}"
                                    required />
                                <x-input-error :messages="$errors->get('address1')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="address2">以降の住所</x-input-label>
                                <x-text-input type="text" id="address2" name="address2" value="{{ $address->address2 }}"
                                    required />
                                <x-input-error :messages="$errors->get('address2')" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <x-input-label for="phone_number">電話番号</x-input-label>
                                <x-text-input type="text" id="phone_number" name="phone_number"
                                    value="{{ $address->phone_number }}" required placeholder="08000000000" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
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
</x-app-layout>