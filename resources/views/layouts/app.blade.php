<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">

    <title>{{ __('ctvero_title') }} - @yield('title')</title>
    <!--

    Template 2103 Central

    http://www.tooplate.com/view/2103-central

    -->
    <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="/static/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
        crossorigin="anonymous">
    <!-- Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="/static/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="/static/slick/slick-theme.css" />
    <!-- tooplate style -->
    <link rel="stylesheet" type="text/css" href="/static/css/tooplate-style.css">
    <!-- custom style overrides -->
    <link rel="stylesheet" type="text/css" href="/static/css/custom.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HP42P30DVN"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-HP42P30DVN');
    </script>

    <script>
        var renderPage = true;

        if (navigator.userAgent.indexOf('MSIE') !== -1
            || navigator.appVersion.indexOf('Trident/') > 0) {
            /* Microsoft Internet Explorer detected in. */
            alert("Please view this in a modern browser such as Chrome or Microsoft Edge.");
            renderPage = false;
        }

    </script>

</head>

<body>

    <!-- Loader -->
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <div class="container">
        @if ($errors = request()->session()->pull('errors'))
            <div class="alert alert-danger">
                @if (count($errors) == 1)
                    {{ $errors[0] }}
                @else
                    <ul>
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <section class="tm-section-head" id="top">
            <header id="header" class="text-center tm-text-gray">
                <h1><a href="{{ route('index') }}">{{ __('ctvero_title') }}</a></h1>
                <p>{{ __('ctvero_subtitle') }}</p>
            </header>

            <nav class="navbar navbar-light">
                <a class="navbar-brand tm-text-gray" href="#">
                    Menu
                </a>
                <button type="button" id="nav-toggle" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#mainNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fa fa-navicon tm-fa-toggler-icon"></i>
                    </span>
                </button>
                <div id="mainNav" class="collapse navbar-collapse tm-bg-white">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('index') }}">Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @php ($submissionDeadline = Utilities::submissionDeadline())
                        @if ($submissionDeadline)
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('submissionForm') }}">Odeslat hlášení<br>
                            <small class="pt-0 mt-0">{{ $submissionDeadline }}</small>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('contests') }}">Soutěžní kola</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('results') }}">Výsledkové listiny</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('index') . '#contact-message' }}">Kontakt</a>
                        </li>
                        <li class="nav-item">
                        @foreach (config('ctvero.locales') as $lang)
                            {{ $loop->index > 0 ? '|' : '' }} <a class="nav-link tm-text-gray d-inline-block" href="{{ route('lang', [ 'lang' => $lang ]) }}">{{ strtoupper($lang) }}</a>
                        @endforeach
                        </li>
                    </ul>
                </div>
            </nav>
        </section>

        <section class="row" id="tm-section-1">
            <div class="col-lg-12 tm-slider-col">
                <div class="tm-img-slider">
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Vysílání nás baví !</p>
                        <img data-lazy="/static/img/gallery-img-1.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Výzva na kanále ...</p>
                        <img data-lazy="/static/img/gallery-img-2.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Stanice ... na příjmu</p>
                        <img data-lazy="/static/img/gallery-img-3.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">27 MHz - pásmo 11m</p>
                        <img data-lazy="/static/img/gallery-img-4.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">73 a naslyš !</p>
                        <img data-lazy="/static/img/gallery-img-5.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Hodně pěkných spojení !</p>
                        <img data-lazy="/static/img/gallery-img-6.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Kategorie Pěšák</p>
                        <img data-lazy="/static/img/gallery-img-7.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Kategorie Mobil</p>
                        <img data-lazy="/static/img/gallery-img-8.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Kategorie Obrněnec</p>
                        <img data-lazy="/static/img/gallery-img-9.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item">
                        <p class="tm-slider-caption">Kategorie Sdílenky</p>
                        <img data-lazy="/static/img/gallery-img-11.jpg" alt="Slider" class="tm-slider-img">
                    </div>
                </div>
            </div>
        </section>

        <section id="scroll"></section>

        @yield('sections')

        <footer class="mt-5">
            <p class="text-center small">Copyright © 2021 Radek Lichnov. Design: <a href="https://www.tooplate.com/">Tooplate</a>
            | Tento <a href="{{ config('ctvero.repositoryUrl') }}">projekt</a> je open-source. Engine: <a href="https://lumen.laravel.com/">Lumen Framework</a>
            | Překlad do němčiny: KML - Kamillo</p>
        </footer>
    </div>

    <!-- load JS files -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>

    <!-- Slick Carousel -->
    <script src="/static/slick/slick.min.js"></script>

    <script>
        function setCarousel() {
            var slider = $('.tm-img-slider');

            if (slider.hasClass('slick-initialized')) {
                slider.slick('destroy');
            }

            slider.slick({
                lazyLoad: 'ondemand',
                autoplay: true,
                fade: true,
                speed: 800,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1
            });
        }

        $(document).ready(function () {
            if (renderPage) {
                $('body').addClass('loaded');
            }

            setCarousel();

            $(window).resize(function () {
                setCarousel();
            });

            // Close menu after link click
            $('.nav-link').click(function () {
                $('#mainNav').removeClass('show');
            });

            // Fix anchor scrolling
            if (location.hash !== '') {
                $('html, body').animate({
                    scrollTop: $(location.hash).offset().top
                });
            }
        });
    </script>

</body>

</html>
