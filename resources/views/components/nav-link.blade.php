@props(['href' => '#', 'active' => false])

@php
    $classes = $active ? 'text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium' : 'text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
