@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-yellow-900']) }}>
    {{ $value ?? $slot }}
</label>
