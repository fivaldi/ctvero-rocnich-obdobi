@extends('layouts.app')

@section('title', rtrim($msg, '!.'))

@section('sections')

    <section class="tm-section-2 my-5 py-4">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <h2>{{ $msg }}</h2>
                <!-- h3 intentionally unused - too large -->
                <h4 class="mt-5">{{ __('Možnosti dalšího postupu') }}…</h4>
                <ul>
                    <li><h5 class="mt-3"><a href="{{ route('contact') }}">{{ __('Věc se týká soutěže nebo uživatelského přístupu a dat.') }}</a></h5></li>
                    <li><h5 class="mt-3"><a href="{{ config('ctvero.issuesReportUrl') }}">{{ __('Jedná se o přetrvávající chybu aplikace nebo jiný problém.') }}</a></h5></li>
                </ul>
            </div>
        </div>
    </section>

@endsection
