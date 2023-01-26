@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['type' => 'text', 'name' => 'search', 'placeholder' =>
'検索']) !!}>
<x-primary-button>検索する</x-primary-button>