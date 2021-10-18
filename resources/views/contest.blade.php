@extends('layouts.app')

@section('title', $title)

@section('sections')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2 class="mb-4">{{ $title }}</h2>

                @if (! empty($diaries))
                <div id="map" style="height: 480px;" class="w-100"></div>
                <script>
                    var zoom = 5
                    if (window.matchMedia('(min-width: 992px)').matches) {
                        zoom = 6.5;
                    } else if (window.matchMedia('(min-width: 768px)').matches) {
                        zoom = 6;
                    } else if (window.matchMedia('(min-width: 576px)').matches) {
                        zoom = 5.5;
                    }

                    var map = L.map('map', {zoomSnap: 0.5, zoomDelta: 0.5, wheelPxPerZoomLevel: 120, tap: false}).setView([49.06685705, 17.8955521], zoom);
                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={{ config("ctvero.mapboxAccessToken") }}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | Imagery &copy; <a href="https://www.mapbox.com">Mapbox</a>',
                        id: 'mapbox/outdoors-v11',
                        tileSize: 512,
                        zoomOffset: -1,
                    }).addTo(map);

                    var locatorDiaries = @json($diaries);
                    for (const [locator, diaries] of Object.entries(locatorDiaries)) {
                        var marker = null;
                        var popups = [];

                        if (diaries.length > 1) {
                            marker = L.divIcon({
                                html: `<i class="fa fa-flag fa-2x" style="color: grey;"></i>`,
                                className: 'no-class',
                            });
                        }
                        diaries.forEach(function(diary) {
                            marker = marker !== null ? marker : L.divIcon({
                                html: `<i class="fa fa-flag fa-2x" style="color: ${diary['categoryMapMarkerColor']};"></i>`,
                                className: 'no-class',
                            });
                            var diaryLink = diary['diaryUrl'] !== null ? `<a href="${diary['diaryUrl']}"><i class="fa fa-book"></i> Deník</a>` : '';
                            popups.push(`<b>${diary['callSign']}</b><br>
                                         ${diary['qthName']} (${diary['qthLocator']})<br>
                                         Kategorie: ${diary['categoryName']}<br>
                                         Počet spojení: ${diary['qsoCount']}<br>` + diaryLink);
                        });

                        /* Note: In case of multi-diaries popup for a single marker, QTH locator lon/lat
                        should always be the same. Thus, it's OK to take the first diary's QTH locator lon/lat. */
                        L.marker([diaries[0]['qthLocatorLat'], diaries[0]['qthLocatorLon']], {icon: marker}).addTo(map).bindPopup(popups.join('<hr>'));
                    }
                </script>
                @endif

                <h3 class="mt-5 mb-4">Hlavní údaje</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Začátek soutěže: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_start) }}</span></li>
                    <li class="list-group-item">Konec soutěže: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_end) }}</span></li>
                    <li class="list-group-item">Termín odeslání hlášení do: <span class="text-nowrap">{{ Utilities::normalDateTime($contest->submission_end) }}</span></li>
                    <li class="list-group-item"><a href="{{ route('calendar', [ 'contest' => $contest->name ]) }}"><i class="fa fa-calendar"></i> Stáhnout kalendář se všemi daty</a></li>
                    @if ($contest->diaries->isNotEmpty())
                    <li class="list-group-item"><a href="{{ route('results') . '#' . Str::replace(' ', '-', $contest->name) }}"><i class="fa fa-list"></i> Výsledková listina{!! Utilities::contestInProgress($contest->name) !!}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

@endsection
