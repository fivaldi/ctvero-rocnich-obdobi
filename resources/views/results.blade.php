@extends('layouts.app')

@section('title', 'Výsledky')

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2>Kompletní výsledkové listiny</h2>
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
                        <tr><th width="50" bgcolor="silver">Pořadí</th>
                            <th width="50" bgcolor="silver">Datum</th>
                            <th width="100" bgcolor="silver">Volačka</th>
                            <th width="100" bgcolor="silver">QTH</th>
                            <th width="50" bgcolor="silver">Lokátor</th>
                            <th width="50" bgcolor="silver">Deník</th>
                            <th width="50" bgcolor="silver">QSO</th>
                            @if ($useScorePoints)
                            <th width="50" bgcolor="silver">Body</th>
                            @endif
                        </tr>
                        @foreach ($allContestsDiaries[$contest_name][$category['id']] as $diary)
                            @if ($loop->index < 3)
                            <tr style="font-weight: bold;">
                            @else
                            <tr style="font-weight: normal;">
                            @endif
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ date('j.n.Y', strtotime($diary['created_at'])) }}</td>
                                <td class="font-weight: lighter;">{{ $diary['call_sign'] }}</td>
                                <td>{{ $diary['qth_name'] }}</td>
                                <td>{{ $diary['qth_locator'] }}</td>
                                <td><a href="{{ $diary['diary_url'] }}" target="_blank">deník</a></td>
                                <td>{{ $diary['qso_count'] }}</td>
                                @if ($useScorePoints)
                                <td>{{ $diary['score_points'] }}</td>
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
