@extends('layouts.app')

@section('title', $title)

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <h2 class="mb-4">{{ $title }}</h2>
                <ul class="mb-5 list-group list-group-horizontal-md d-flex flex-wrap">
                @foreach ($contests as $contest)
                    <li class="list-group-item text-large"><a href="{{ route('contest', [ 'name' => Str::replace(' ', '-', $contest->name) ]) . '#scroll' }}">{{ Utilities::contestL10n($contest->name) }}</a></li>
                @endforeach
                </ul>
            </div>
        </div>
    </section>

@endsection
