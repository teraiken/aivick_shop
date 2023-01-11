@props(['value'])

<div {{ $attributes->merge(['class' => 'w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out']) }}>
    {{ $value ?? $slot }}
</div>
