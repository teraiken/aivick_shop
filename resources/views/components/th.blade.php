@props(['value'])

<th {{ $attributes->merge(['class' => 'px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100']) }}>
    {{ $value ?? $slot }}
</th>
