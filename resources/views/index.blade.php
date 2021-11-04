@extends('layouts.app')

@section('title', $title)

@section('sections')

@include('recaptcha', [ 'formId' => 'contact-form' ])

    <section class="tm-section-2 my-5 py-4">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <header>
                <h2>{{ __('news_and_announcements') }}</h2>
                </header>
                {{ Utilities::getAppContent('news') }}
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
                    <ul class="list-group">
                    @foreach ($lastYearContests as $contest)
                        <li class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h4 class="mb-1"><a href="{{ route('contest', [ 'name' => Str::replace(' ', '-', $contest->name) ]) . '#scroll' }}">{{ Utilities::contestL10n($contest->name) }}</a></h4>
                                <p><a class="ml-2" href="{{ route('calendar', [ 'contest' => $contest->name ]) }}"><i class="fa fa-calendar"></i></a></p>
                            </div>
                            <p class="mb-1"><span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_start) }}</span>
                                          — <span class="text-nowrap">{{ Utilities::normalDateTime($contest->contest_end) }}</span></p>
                        </li>
                    @endforeach
                    </ul>

                    <header>
                    <h3 class="mt-5">{{ __('connections') }}</h3>
                    </header>
                    {{ Utilities::getAppContent('connections') }}

                    <header>
                    <h3 class="mt-5">{{ __('logbook') }}</h3>
                    </header>
                    {{ Utilities::getAppContent('logbook') }}
                </div>
            </div>
            <div class="col-md-6 tm-2col-r">
                <div class="h-100 tm-box-3">
                    <header>
                    <h3>{{ __('categories') }}</h3>
                    </header>
                    {{ Utilities::getAppContent('categories') }}

                    <header>
                    <h3 class="mt-5">{{ __('equipment') }}</h3>
                    </header>
                    {{ Utilities::getAppContent('equipment') }}

                    <header>
                    <h3 class="mt-5">{{ __('scoring') }}</h3>
                    </header>
                    {{ Utilities::getAppContent('scoring') }}

                    <p class="mt-5"><b>{{ __('wish_you_great_conditions') }}</b></p>
                </div>
            </div>
        </div>
    </section>

    <section class="tm-section-12 tm-section-mb">
            <div class="col-lg-12 col-md-12 col-sm-12 pl-lg-0">
                <header>
                <h2 class="mb-4">Nejlepší trojka{!! Utilities::contestInProgress($lastContest->name) !!}</h2>
                </header>
                @foreach ($categories as $category)
                <div class="media tm-media">
                    <img src="{{ $category['image_src'] }}" alt="Category" class="img-responsive tm-media-img">
                    <div class="media-body tm-box-5 w-100">
                        <h3>{{ __($category['name']) }}</h3>

                        <x-results-table :terse="true" :useScorePoints="$useScorePoints" :diaries="array_slice($lastContestDiaries[$category['id']] ?? [], 0, 3)"/>

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
                    <form action="message" method="post" class="contact-form col-12" id='contact-form'>
                        <div class="form-group align-items-center row">
                            <label for="email" class="col-12 col-md-3">E-mail</label>
                            <input name="email" type="email" class="form-control col-12 col-md-9" id="email" placeholder="name@example.com">
                        </div>
                        <div class="form-group align-items-center row">
                            <label for="subject" class="col-12 col-md-3">Předmět</label>
                            <input name="subject" type="text" class="form-control col-12 col-md-9" id="subject" placeholder="Předmět zprávy">
                        </div>
                        <div class="form-group row">
                            <label for="message" class="col-12">Zpráva</label>
                            <textarea name="message" class="form-control col-12" id="message" placeholder="Text zprávy" rows="6"></textarea>
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
