@extends('layouts.app')

@section('title', 'Hlášení')

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2 class="mb-4">Odeslat hlášení</h2>
                @if ($submissionSuccess = request()->session()->get('submissionSuccess'))
                    <div class="alert alert-success">
                        {{ $submissionSuccess }}
                    </div>
                @elseif ($submissionErrors = request()->session()->get('submissionErrors'))
                    <div class="alert alert-danger">
                        @if (count($submissionErrors) == 1)
                            {{ $submissionErrors[0] }}
                        @else
                            <ul>
                            @foreach ($submissionErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
                @php
                $step = intval(request()->input('krok'));
                if ($step < 1 or $step > 2) {
                    $step = 1;
                }
                $diarySources = implode(', ', $data->diarySources);
                @endphp

                @if (empty($data->contests->all()))
                <h3>V tuto chvíli se nesbírají hlášení do žádné soutěže.</h3>

                @elseif ($step == 1)
                <h3>Krok 1: Výběr deníku</h3>
                <form action="submission" method="post" class="sumbission-form">
                    <input type="hidden" name="step" value="1">
                    <div class="form-group">
                        <label for="diaryUrl">Odkaz na deník ({{ $diarySources }})...</label>
                        <input name="diaryUrl" type="text" class="form-control" id="diaryUrl" placeholder="http://">
                    </div>
                    <button type="submit" class="btn btn-primary">Načíst údaje z deníku</button>
                </form>
                <div class="row col-12 align-items-center mt-2">
                    <p>Nebo <p><a href="?krok=2" class="btn btn-secondary ml-4" role="button">Vyplnit hlášení ručně</a>
                </div>

                @elseif ($step == 2)
                <h3>Krok 2: {{ request()->session()->get('diary') ? 'Kontrola a doplnění' : 'Vyplnění' }} hlášení</h3>
                <form action="submission" method="post" class="sumbission-form">
                    <input type="hidden" name="step" value="2">
                    <div class="form-group">
                        <label for="contest">Soutěž</label>
                        <select name="contest" class="form-control form-control-lg p-0 px-3" id="contest">
                            @if (count($data->contests) > 1)
                            <option disabled selected value>Vyber možnost...</option>
                            @endif
                            @foreach ($data->contests as $contest)
                                <option {{ request()->session()->get('diary.contest') == $contest->name ? 'selected' : '' }}>{{ $contest->name }}</li>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategorie</label>
                        <select name="category" class="form-control form-control-lg p-0 px-3" id="category">
                            @if (count($data->categories) > 1)
                            <option disabled selected value>Vyber možnost...</option>
                            @endif
                            @foreach ($data->categories as $category)
                                <option {{ request()->session()->get('diary.category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</li>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="diaryUrl">URL deníku</label>
                        <input name="diaryUrl" class="form-control" type="text" placeholder="URL adresa deníku určená ke sdílení" value="{{ request()->session()->get('diary.Url') }}">
                    </div>
                    <div class="form-group">
                        <label for="callSign">Volačka</label>
                        <input name="callSign" class="form-control" type="text" placeholder="Tvoje nebo expediční volačka" value="{{ request()->session()->get('diary.callSign') }}">
                    </div>
                    <div class="form-group">
                        <label for="qthName">Místo vysílání (QTH)</label>
                        <input name="qthName" class="form-control" type="text" placeholder="Název místa vysílání" value="{{ request()->session()->get('diary.qthName') }}">
                    </div>
                    <div class="form-group">
                        <label for="qthLocator">Lokátor</label>
                        <input name="qthLocator" class="form-control" type="text" placeholder="Lokátor místa vysílání" value="{{ request()->session()->get('diary.qthLocator') }}">
                    </div>
                    <div class="form-group">
                        <label for="qsoCount">Počet spojení (QSO)</label>
                        <input name="qsoCount" class="form-control" type="text" placeholder="Počet úspěšných spojení" value="{{ request()->session()->get('diary.qsoCount') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail <small>(pro případnou kontrolu nesrovnalostí v deníku)</small></label>
                        <input name="email" class="form-control" type="email" placeholder="name@example.com" value="{{ request()->session()->get('diary.email') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Odeslat</button>
                </form>
                <div class="row col-12 align-items-center mt-2">
                    <p>Nebo <p><a href="?krok=1" class="btn btn-secondary ml-4" role="button">Zpět na výběr deníku</a>
                </div>
                @endif
            </div>
        </div>
    </section>

@endsection
