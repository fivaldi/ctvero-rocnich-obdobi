@extends('layouts.app')

@section('title', 'Home')

@section('sections')

@include('recaptcha', [ 'formId' => 'contact-form' ])

@php
$locale = app('translator')->getLocale();
@endphp

    <section class="tm-section-2 my-5 py-4">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <header>
                <h2>{{ __('news_and_announcements') }}</h2>
                </header>
                {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/news_' . $locale . '.md')) }}
            </div>
        </div>
    </section>

    <section class="tm-section-3 tm-section-mb" id="tm-section-3">
        <div class="row">
            <div class="col-md-6 tm-mb-sm-4 tm-2col-l">
                <div class="h-100 tm-box-3">
                    <header>
                    <h3>{{ __('dates') }}</h3>
                    </header>
                    <p class="small">{{ __('dates_usual_times') }}</p>
                    @foreach ($lastYearContests as $contest)
                        <dl class="mt-4 contests">
                            <dt>{{ App\Http\Utilities::contestL10n($contest->name) }}</dt>
                            <dd>{{ date('j.n.Y H:i', strtotime($contest->contest_start)) }} — {{ date('j.n.Y H:i', strtotime($contest->contest_end)) }}<a class="ml-2" href="{{ route('calendar', [ 'soutez' => $contest->name ]) }}"><i class="fa fa-calendar"></i></a></dd>
                        </dl>
                    @endforeach

                    <header>
                    <h3 class="mt-5">{{ __('connections') }}</h3>
                    </header>
                    {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/connections_' . $locale . '.md')) }}

                    <header>
                    <h3 class="mt-5">{{ __('logbook') }}</h3>
                    </header>
                    {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/logbook_' . $locale . '.md')) }}
                </div>
            </div>
            <div class="col-md-6 tm-2col-r">
                <div class="h-100 tm-box-3">
                    <header>
                    <h3>{{ __('categories') }}</h3>
                    </header>
                    {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/categories_' . $locale . '.md')) }}

                    <header>
                    <h3 class="mt-5">{{ __('equipment') }}</h3>
                    </header>
                    {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/equipment_' . $locale . '.md')) }}

                    <header>
                    <h3 class="mt-5">{{ __('scoring') }}</h3>
                    </header>
                    {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/scoring_' . $locale . '.md')) }}

                    <p class="mt-5"><b>{{ __('wish_you_great_conditions') }}</b></p>
                </div>
            </div>
        </div>
    </section>

    <section class="tm-section-12 tm-section-mb">
            <div class="col-lg-12 col-md-12 col-sm-12 pl-lg-0">
                <header>
                <h2 class="mb-4">Nejlepší trojka</h2>
                </header>
                @foreach ($categories as $category)
                <div class="media tm-media">
                    <img src="{{ $category['image_src'] }}" class="img-responsive tm-media-img">
                    <div class="media-body tm-box-5">
                        <h3>{{ __($category['name']) }}</h3>

                        <table class="table-striped small" style="width: 100%">
                            <tr style="background-color: silver">
                                <th class="col-1 d-none d-md-table-cell">Datum</th>
                                <th class="col-3">Volačka</th>
                                <th class="col-3">QTH</th>
                                <th class="col-1 d-none d-md-table-cell">Lokátor</th>
                                <th class="col-1">Deník</th>
                                <th class="col-2">Počet QSO</th>
                                @if ($useScorePoints)
                                <th class="col-1">Body</th>
                                @endif
                            </tr>
                            @foreach (array_slice($lastContestDiaries[$category['id']] ?? [], 0, 3) as $diary)
                                <tr><td class="col-1 d-none d-md-table-cell">{{ date('j.n.Y', strtotime($diary['created_at'])) }}</td>
                                    <td class="col-3">{{ $diary['call_sign'] }}</td>
                                    <td class="col-3">{{ $diary['qth_name'] }}</td>
                                    <td class="col-1 d-none d-md-table-cell">{{ $diary['qth_locator'] }}</td>
                                    <td class="col-1"><a href="{{ $diary['diary_url'] }}" target="_blank"><i class="fa fa-book fa-lg"></i></a></td>
                                    <td class="col-2">{{ $diary['qso_count'] }}</td>
                                    @if ($useScorePoints)
                                    <td class="col-1">{{ $diary['score_points'] }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
                @endforeach
            </div>
    </section>

    <section class="tm-section-4 tm-section-mb my-3" id="tm-section-contact">
        <div class="row">
        </div>
    </section>

    <section class="tm-section-6 mt-3" id="tm-section-6">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-xs-12">
                <div class="contact_message" id="contact-message">
                    <h2 class="mb-4">Kontaktní formulář</h2>
                    @if ($messageSuccess = request()->session()->pull('messageSuccess'))
                        <div class="alert alert-success">
                            {{ $messageSuccess }}
                        </div>
                        <script>location.hash = '#contact-message';</script>
                    @elseif ($messageErrors = request()->session()->pull('messageErrors'))
                        <div class="alert alert-danger">
                            @if (count($messageErrors) == 1)
                                {{ $messageErrors[0] }}
                            @else
                                <ul>
                                @foreach ($messageErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                        <script>location.hash = '#contact-message';</script>
                    @endif
                    <form action="message" method="post" class="contact-form" id='contact-form'>
                        <div class="form-group row align-items-center col-12">
                            <label for="email" class="col-2">E-mail</label>
                            <input name="email" type="email" class="form-control col-10" id="email" placeholder="name@example.com">
                        </div>
                        <div class="form-group row align-items-center col-12">
                            <label for="subject" class="col-2">Předmět</label>
                            <input name="subject" type="text" class="form-control col-10" id="subject" placeholder="Předmět zprávy">
                        </div>
                        <div class="form-group row col-12">
                            <label for="message">Zpráva</label>
                            <textarea name="message" class="form-control" id="message" placeholder="Text zprávy" rows="6"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary g-recaptcha" data-sitekey="{{ config('ctvero.recaptchaSiteKey') }}" data-callback="onSubmit" data-action="submit">Odeslat</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5 col-md-5 col-xs-12 tm-contact-right">
                <div class="tm-address-box">
                    <h2 class="mb-4">Kontakt</h2>
                    <div class="mb-4">
                        <p>V případě dotazů, návrhů či připomínek mne neváhejte kontaktovat.</p>
                        <p class="text-right pr-5">73 Radek Lichnov</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-0">Diskuze k souteži na stránce</p>
                        <a href="https://forum.svysilackou.cz/showthread.php?tid=112"><img alt="forum.svysilackou.cz" src="https://forum.svysilackou.cz/images/simplicity/logo_n.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
