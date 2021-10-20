@php ($terse = $terse ?? false)
<table class="table-striped small w-100">
<tr style="background-color: silver">
    <th class="col-1{{ $terse ? ' d-none d-md-table-cell' : '' }}">{{ __('Pořadí') }}</th>
    <th class="col-1 d-none d-{{ $terse ? 'lg' : 'md' }}-table-cell">{{ __('Datum hlášení') }}</th>
    <th class="col-8">
        <div class="row">
            <div class="d-inline d-{{ $terse ? 'md' : 'sm' }}-table-cell col-12 col-{{ $terse ? 'md' : 'sm' }}-6">{{ __('Volačka') }}</div>
            <div class="d-inline d-{{ $terse ? 'md' : 'sm' }}-table-cell col-{{ $terse ? 'md' : 'sm' }}-6 qth-name-{{ $terse ? 'md' : 'sm' }}">{{ __('QTH') }}</div>
        </div>
    </th>
    <th class="col-1 d-none d-{{ $terse ? 'lg' : 'md' }}-table-cell">{{ __('Lokátor') }}</th>
    <th class="col-1">{{ __('Deník') }}</th>
    <th class="col-1">{{ __('QSO') }}</th>
    @if ($useScorePoints)
    <th class="col-1">{{ __('Body') }}</th>
    @endif
</tr>
@foreach ($diaries as $diary)
    <tr style="font-weight: {{ $loop->index < 3 ? 'bold' : 'normal' }}">
        <td class="col-1{{ $terse ? ' d-none d-md-table-cell' : '' }}">{{ $loop->iteration }}.</td>
        <td class="col-1 d-none d-{{ $terse ? 'lg' : 'md' }}-table-cell text-nowrap">{{ date('j. n. Y', strtotime($diary['created_at'])) }}</td>
        <td class="col-8">
            <div class="row align-items-center">
                <div class="d-inline d-{{ $terse ? 'md' : 'sm' }}-table-cell col-12 col-{{ $terse ? 'md' : 'sm' }}-6">{{ $diary['call_sign'] }}</div>
                <div class="d-inline d-{{ $terse ? 'md' : 'sm' }}-table-cell col-{{ $terse ? 'md' : 'sm' }}-6 qth-name-{{ $terse ? 'md' : 'sm' }}">{{ $diary['qth_name'] }}</div>
            </div>
        </td>
        <td class="col-1 d-none d-{{ $terse ? 'lg' : 'md' }}-table-cell">{{ $diary['qth_locator'] }}</td>
        <td class="col-1">
            @if ($diary['diary_url'])
            <a href="{{ $diary['diary_url'] }}" target="_blank"><i class="fa fa-book fa-lg"></i></a>
            @endif
        </td>
        <td class="col-1">{{ $diary['qso_count'] }}</td>
        @if ($useScorePoints)
        <td class="col-1">{{ $diary['score_points'] }}</td>
        @endif
    </tr>
@endforeach
</table>
