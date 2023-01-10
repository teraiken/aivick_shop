@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['type' => 'text', 'name' => 'search', 'placeholder' => '検索']) !!}>
<x-blue-button>検索する</x-blue-button>