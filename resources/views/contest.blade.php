@extends('layouts.app')

@section('title', $title)

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2 class="mb-4">{{ $title }}</h2>

                @if (! empty($diaries))
                <div id="map" style="height: 480px;" class="w-100"></div>
                @endif

                <h3 class="mt-5 mb-4">{{ __('Hlavní údaje') }}</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">{{ __('Začátek soutěže') }}: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_start) }}</span></li>
                    <li class="list-group-item">{{ __('Konec soutěže') }}: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_end) }}</span></li>
                    <li class="list-group-item">
                        {{ __('Termín odeslání hlášení do') }}: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->submission_end) }}</span>
                        @if ($contest->isActiveSubmission)
                        <br><a href="{{ route('submissionForm') }}"><i class="fa fa-file-text"></i> {{ __('Odeslat hlášení') }}</a>
                        @endif
                    </li>
                    <li class="list-group-item"><a href="{{ route('calendar', [ 'contest' => $contest->name ]) }}"><i class="fa fa-calendar"></i> {{ __('Stáhnout kalendář se všemi daty') }}</a></li>
                    @if ($contest->diaries->isNotEmpty())
                    <li class="list-group-item"><a href="{{ route('results') . '#' . Str::replace(' ', '-', $contest->name) }}"><i class="fa fa-list"></i> {{ __('Výsledková listina') }}{!! Utilities::contestInProgress($contest->name) !!}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

@endsection

@section('scripts')

    @if (! empty($diaries))
    <x-leaflet-map/>

    <script>
        var locatorDiaries = @json($diaries);
        for (const [locator, diaries] of Object.entries(locatorDiaries)) {
            var markerColor = '#836953';
            var popups = [];

            diaries.forEach(function(diary) {
                markerColor = diaries.length > 1 ? markerColor : diary['categoryMapMarkerColor'];
                var diaryLink = diary['diaryUrl'] !== null ? `<a href="${diary['diaryUrl']}"><i class="fa fa-book"></i> {{ __('Deník') }}</a>` : '';
                popups.push(`<div class="pl-2 text-large" style="position: relative; border-left: 4px solid ${diary['categoryMapMarkerColor']};">
                                 <div class="diary-popup-dot" style="background-color: ${diary['categoryMapMarkerColor']};"></div>
                                 <b>${diary['callSign']}</b><br>
                                 ${diary['qthName']} (${diary['qthLocator']})<br>
                                 {{ trans_choice('Kategorie', 1) }}: ${diary['categoryName']}<br>
                                 {{ __('Počet spojení') }}: ${diary['qsoCount']}<br>
                                 ${diaryLink}
                             </div>`);
            });
            var marker = L.divIcon({
                html: `<i class="fa fa-flag fa-2x" style="color: ${markerColor}"></i>`,
                className: 'no-class',
            });

            /* Note: In case of multi-diaries popup for a single marker, QTH locator lon/lat
            should always be the same. Thus, it's OK to take the first diary's QTH locator lon/lat. */
            L.marker([diaries[0]['qthLocatorLat'], diaries[0]['qthLocatorLon']], {icon: marker}).addTo(map).bindPopup(popups.join('<hr>'));
        }
    </script>
    @endif

@endsection
