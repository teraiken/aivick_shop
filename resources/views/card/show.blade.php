<x-app-layout>
    <x-slot name="header">
        カード情報
    </x-slot>

    <section class="text-gray-600 body-font relative">
        <div class="container px-5 mx-auto">
            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="text-red-500 mb-8">
                    @if ($card)
                    {{ __('card.exist') }}
                    @else
                    {{ __('card.none') }}
                    @endif
                </div>

                <div class="flex flex-wrap -m-2">
                    @if ($card)
                    <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label>カード番号</x-input-label>
                            <x-text-show>{{ $card['number'] }}</x-text-show>
                        </div>
                    </div>

                    <div class="p-2 w-1/2">
                        <div class="relative">
                            <x-input-label>有効期限</x-input-label>
                            <x-text-show>{{ $card['exp'] }}</x-text-show>
                        </div>
                    </div>
                    @endif

                    <div class="p-2 flex mx-auto">
                        @if ($card)
                        <form method="post" action="{{ route('card.update') }}">
                            @csrf
                            @method('patch')
                            @else
                            <form method="post" action="{{ route('card.store') }}">
                                @csrf
                                @endif
                                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="{{ env('STRIPE_PUBLIC_KEY') }}" data-name="支払い情報" data-label="カードを登録する"
                                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                    data-locale="ja" data-email="{{ Auth::user()->email }}" data-panel-label="カードを登録する">
                                </script>
                            </form>

                            @if ($card)
                            <form class="ml-4" method="post" action="{{ route('card.destroy') }}"
                                onsubmit="return confirm('本当に削除しますか？')">
                                @csrf
                                @method('delete')

                                <x-danger-button>削除する</x-danger-button>
                            </form>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>