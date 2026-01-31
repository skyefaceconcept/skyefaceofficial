@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
