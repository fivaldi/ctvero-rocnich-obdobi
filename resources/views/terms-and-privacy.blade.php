@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('title', $title)

@section('sections')

    <section class="tm-section-2 tm-section-mb" id="tm-section-2">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">

                {{ Utilities::getAppContent('terms-and-privacy') }}

            </div>
        </div>
    </section>

@endsection
