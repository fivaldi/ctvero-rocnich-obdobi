@extends('layouts.app')

@section('title', $title)

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2 class="mb-4">{{ $title }}</h2>
                <ul class="mb-5 list-group list-group-horizontal-md d-flex flex-wrap">
                @foreach ($allContestsDiaries as $contestName => $diaries)
                    <li class="list-group-item text-large"><a href="#{{ Str::replace(' ', '-', $contestName) }}">{{ Utilities::contestL10n($contestName) }}</a></li>
                @endforeach
                </ul>

                @foreach ($allContestsDiaries as $contestName => $diaries)
                    @php ($useScorePoints = $allContests->where('name', $contestName)->first()->options['criterion'] ?? NULL == 'score_points')
                    <h3 id="{{ Str::replace(' ', '-', $contestName) }}" class="mt-5 text-center">{{ Utilities::contestL10n($contestName) }}{!! Utilities::contestInProgress($contestName) !!}</h3>

                    @foreach ($categories as $category)
                        @isset ($diaries[$category['id']])
                        <h4 class="mt-3">{{ __($category['name']) }}</h4>

                        <x-results-table :useScorePoints="$useScorePoints" :diaries="$diaries[$category['id']]"/>

                        @endisset
                    @endforeach
                @endforeach

            </div>
        </div>
    </section>

@endsection
