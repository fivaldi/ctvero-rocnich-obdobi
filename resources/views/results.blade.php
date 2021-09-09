@extends('layouts.app')

@section('title', 'Výsledky')

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2>Výsledkové listiny</h2>
                @foreach (array_keys($allContestsDiaries) as $contest_name)
                    @if (! empty($allContestsDiaries[$contest_name]))
                    <a href="#{{ $contest_name }}">{{ $contest_name }}</a>
                    @if (! $loop->last)
                    <span>|</span>
                    @endif
                    @endif
                @endforeach

                @foreach (array_keys($allContestsDiaries) as $contest_name)
                    @if (! empty($allContestsDiaries[$contest_name]))
                    @php
                    $useScorePoints = ($allContests->where('name', $contest_name)->first()->options['criterion'] ?? NULL == 'score_points') or false;
                    $inProgress = $contestsInProgress->where('name', $contest_name)->first() ? '<i> (' . 'průběžné výsledky' . ')</i>' : '';
                    @endphp
                    <hr class="mt-5">
                    <h3 id="{{ $contest_name }}" class="mt-4 text-center">{{ $contest_name }}{!! $inProgress !!}</h3>

                    @foreach ($categories as $category)
                        @isset ($allContestsDiaries[$contest_name][$category['id']])
                        <h4 class="mt-3">{{ $category['name'] }}</h4>

                        <table class="small text-left" style="width: 100%">
                        <tr style="background-color: silver">
                            <th class="col-1">Pořadí</th>
                            <th class="col-1 d-none d-md-table-cell">Datum</th>
                            <th class="col-3">Volačka</th>
                            <th class="col-3">QTH</th>
                            <th class="col-1 d-none d-md-table-cell">Lokátor</th>
                            <th class="col-1">Deník</th>
                            <th class="col-1">QSO</th>
                            @if ($useScorePoints)
                            <th class="col-1">Body</th>
                            @endif
                        </tr>
                        @foreach ($allContestsDiaries[$contest_name][$category['id']] as $diary)
                            <tr style="font-weight: {{ $loop->index < 3 ? 'bold' : 'normal' }}">
                                <td class="col-1">{{ $loop->iteration }}.</td>
                                <td class="col-1 d-none d-md-table-cell">{{ date('j.n.Y', strtotime($diary['created_at'])) }}</td>
                                <td class="col-3">{{ $diary['call_sign'] }}</td>
                                <td class="col-3">{{ $diary['qth_name'] }}</td>
                                <td class="col-1 d-none d-md-table-cell">{{ $diary['qth_locator'] }}</td>
                                <td class="col-1"><a href="{{ $diary['diary_url'] }}" target="_blank"><i class="fa fa-book fa-lg"></i></a></td>
                                <td class="col-1">{{ $diary['qso_count'] }}</td>
                                @if ($useScorePoints)
                                <td class="col-1">{{ $diary['score_points'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </table>

                        @endisset
                    @endforeach

                    @endif
                @endforeach

            </div>
        </div>
    </section>

@endsection
