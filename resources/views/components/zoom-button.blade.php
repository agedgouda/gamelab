<button {{ $attributes->merge(['type' => 'button', 'class' => 'items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
