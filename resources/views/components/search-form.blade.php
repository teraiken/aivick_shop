@props(['disabled' => false, 'search'])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['type' => 'text', 'name' => 'search', 'placeholder' =>
'検索', 'value' => (isset($search)) ? $search : '']) !!}>

{{ $slot }}

<x-primary-button>検索</x-primary-button>