@props(['on'])

<span {{ $attributes->merge(['class' => 'text-sm text-green-600']) }}>
    {{ $slot }}
</span>
