@if (session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3" role="alert">
        <p class="font-bold">{{ session('status') }}</p>
    </div>
@endif
