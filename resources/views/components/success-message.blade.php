@props(['value'])

<div {{ $attributes->merge(['class' => 'bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md', 'role' => 'alert']) }}>
    {{ $value ?? $slot }}
</div>