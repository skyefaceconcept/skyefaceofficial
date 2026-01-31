<!-- Confirms Password Modal -->
<div x-data="{ open: @entangle($attributes->wire('model')).defer }" @keydown.escape.window="open = false">
    <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" style="display: none;">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>

        <div class="relative bg-white rounded-lg shadow-xl sm:w-full sm:mx-auto sm:max-w-md">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $title ?? __('Confirm Password') }}
                </h3>

                <div class="mt-4 text-sm text-gray-600">
                    {{ $content ?? __('For your security, please confirm your password to continue.') }}
                </div>

                <div class="mt-4">
                    <input type="password" class="form-control" placeholder="{{ __('Password') }}" />
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="button" class="btn btn-secondary" @click="open = false">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
