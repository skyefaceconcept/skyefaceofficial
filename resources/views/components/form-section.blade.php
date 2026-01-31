@props(['submit' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="px-4 py-12 bg-white sm:px-6">
        <div class="max-w-xl">
            <div>
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $title ?? '' }}
                </h3>

                @if (isset($description))
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $description }}
                    </p>
                @endif
            </div>

            <form
                {{ $attributes->merge(['method' => 'POST']) }}
                @if($submit) wire:submit.prevent="{{ $submit }}" @endif
                class="mt-6"
            >
                @csrf
                @method($method ?? 'POST')

                <div class="grid grid-cols-6 gap-6">
                    {{ $form ?? $slot }}
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg mt-6">
                    {{ $actions ?? '' }}
                </div>
            </form>
        </div>
    </div>
</div>
