<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg']) }}>
    {{ $slot }}
</button>
