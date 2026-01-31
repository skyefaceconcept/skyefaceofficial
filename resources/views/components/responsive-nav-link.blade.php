@props(['href' => '#', 'active' => false])

@php
    $classes = $active ? 'block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:bg-indigo-50 focus:border-indigo-700' : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:bg-gray-50 focus:border-gray-300';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
