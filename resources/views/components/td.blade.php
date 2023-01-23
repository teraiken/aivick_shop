@props(['value'])

<td {{ $attributes->merge(['class' => 'border-t-2 border-gray-200 px-4 py-3']) }}>
    {{ $value ?? $slot }}
</td>
