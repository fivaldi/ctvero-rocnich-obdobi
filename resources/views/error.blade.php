@extends('layouts.app')

@section('title', 'Home')

@section('sections')

    <section class="tm-section-2 my-5 py-4">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <header>
                <h2>{{ $msg }}</h2>
                <h3 class="mt-3">Pokud se skutečně jedná o problém, <a href="{{ config('ctvero.issuesReportUrl') }}">tady ho lze nahlásit.</a></h3>
                </header>
            </div>
        </div>
    </section>

@endsection
