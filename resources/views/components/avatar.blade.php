@if (Auth::user())
    @php ($small = $small ?? true)
    @if ($small)
        <img class="mr-2" style="height: 32px; width: 32px; border-radius: 4px;" alt="Avatar" src="{{ Auth::user()->providers()->first()->avatar_url ?? 'data:,' }}">
    @else
        <img class="mr-2" style="height: 48px; width: 48px; border-radius: 6px;" alt="Avatar" src="{{ Auth::user()->providers()->first()->avatar_url ?? 'data:,' }}">
    @endif
@endif
