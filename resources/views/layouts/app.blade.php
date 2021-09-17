<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href ="favicon.ico" type="image/x-icon" /> <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

    <title>{{ __('ctvero_title') }} - @yield('title')</title>
    <!--

    Template 2103 Central

    http://www.tooplate.com/view/2103-central

    -->
    <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="/static/font-awesome-4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="/static/slick/slick-theme.css" />
    <!-- tooplate style -->
    <link rel="stylesheet" type="text/css" href="/static/css/tooplate-style.css">
    <!-- custom style overrides -->
    <link rel="stylesheet" type="text/css" href="/static/css/custom.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async type="text/javascript" src="https://www.googletagmanager.com/gtag/js?id=G-HP42P30DVN"></script>
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

    <script type="text/javascript" src="http://www.w3schools.com/lib/w3data.js"></script>

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

            <nav class="navbar narbar-light">
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
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('submissionForm') }}">Hlášení</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tm-text-gray" href="{{ route('results') }}">Výsledky</a>
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

            <div class="navbar navbar-default navbar-fixed-top">
                <a href="/" class="navbar-brand"></a>
                <botton class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mydropdown">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </botton>

            </div>

            <div class="collapse navbar-collapse" id="mydropdown">

                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('submissionForm') }}" target="">Hlášení</a>
                    </li>
                    <li>
                        <a href="{{ route('results') }}">Výsledky</a>
                    </li>
                    <li>
                        <a href="{{ route('index') . '#contact-message' }}">Kontakt</a>
                    </li>
                    <li>
                    @foreach (config('ctvero.locales') as $lang)
                        {{ $loop->index > 0 ? '|' : '' }} <a href="{{ route('lang', [ 'lang' => $lang ]) }}">{{ strtoupper($lang) }}</a>
                    @endforeach
                    </li>
                </ul>
            </div>
        </section>

        <section class="row" id="tm-section-1">
            <div class="col-lg-12 tm-slider-col">
                <div class="tm-img-slider">
                    <div class="tm-img-slider-item" href="/static/img/gallery-img-1.jpg">
                        <p class="tm-slider-caption">Vysílání nás baví !</p>
                        <img src="/static/img/gallery-img-1.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="/static/img/gallery-img-2.jpg">
                        <p class="tm-slider-caption">Výzva na kanále ...</p>
                        <img src="/static/img/gallery-img-2.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="/static/img/gallery-img-3.jpg">
                        <p class="tm-slider-caption">Stanice ... na příjmu</p>
                        <img src="/static/img/gallery-img-3.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="/static/img/gallery-img-4.jpg">
                        <p class="tm-slider-caption">27 MHz - pásmo 11m</p>
                        <img src="/static/img/gallery-img-4.jpg" alt="Image" class="tm-slider-img">
                    </div>
                </div>
            </div>
        </section>

        @yield('sections')

        <footer class="mt-5">
            <p class="text-center small">Copyright © 2021 Radek Lichnov. Design: <a href="https://www.tooplate.com/">Tooplate</a>
            | Tento <a href="{{ config('ctvero.repositoryUrl') }}">projekt</a> je open-source. Engine: <a href="https://lumen.laravel.com/">Lumen Framework</a>
            | Překlad do němčiny: KML - Kamillo</p>
        </footer>
    </div>

    <!-- load JS files -->
    <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/static/js/popper.min.js"></script>
    <!-- https://popper.js.org/ -->
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script type="text/javascript" src="/static/slick/slick.min.js"></script>
    <!-- Slick Carousel -->
    <script>w3IncludeHTML();</script>
    <!-- Include MENU -->

    <script>
        function setCarousel() {
            var slider = $('.tm-img-slider');

            if (slider.hasClass('slick-initialized')) {
                slider.slick('destroy');
            }

            if ($(window).width() > 991) {
                // Slick carousel
                slider.slick({
                    autoplay: true,
                    fade: true,
                    speed: 800,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                });
            } else {
                slider.slick({
                    autoplay: true,
                    fade: true,
                    speed: 800,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                });
            }
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
        });
    </script>

</body>

</html>
