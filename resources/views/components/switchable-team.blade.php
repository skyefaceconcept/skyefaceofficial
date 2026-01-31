@props(['team', 'component' => null])

<form method="POST" action="{{ route('current-team.update') }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="team_id" value="{{ $team->id }}">

    @if($component === 'responsive-nav-link')
        <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
            {{ $team->name }}
        </x-responsive-nav-link>
    @else
        <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
            {{ $team->name }}
        </x-dropdown-link>
    @endif
</form>
