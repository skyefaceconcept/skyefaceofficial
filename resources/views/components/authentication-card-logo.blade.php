@php
    $logo = null;
    try { $logo = \App\Helpers\CompanyHelper::logo(); } catch (\Throwable $e) { $logo = null; }
    $src = $logo ? asset($logo) : asset('buzbox/img/logo-s.png');
@endphp

<img src="{{ $src }}" class="w-20 h-20" alt="{{ config('app.name') }}">
