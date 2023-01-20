@props(['value'])

<div {{ $attributes->merge(['class' => 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative', 'role' => 'alert']) }}>
    {{ $value ?? $slot }}
</div>