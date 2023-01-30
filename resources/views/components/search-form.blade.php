@props(['disabled' => false, 'search'])

<div>
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['type' => 'text', 'name' => 'search', 'placeholder'
    => '検索', 'value' => (isset($search)) ? $search : '']) !!}>

    <x-primary-button>検索</x-primary-button>
</div>