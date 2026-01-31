@props(['align' => 'left', 'width' => '48'])

<div {{ $attributes->merge(['class' => 'relative']) }} x-data="{ open: false }">
    <div @click="open = ! open">
        {{ $trigger ?? '' }}
    </div>

    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-{{ $width }} rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            {{ $content ?? '' }}
        </div>
    </div>
</div>
