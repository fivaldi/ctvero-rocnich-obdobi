<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href ="favicon.ico" type="image/x-icon" /> <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

    <title>Čtvero Ročních Období - @yield('title')</title>
    <!--

    Template 2103 Central

    http://www.tooplate.com/view/2103-central

    -->
    <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="font-awesome-4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <!-- tooplate style -->
    <link rel="stylesheet" href="css/tooplate-style.css">
    <!-- custom style overrides -->
    <link rel="stylesheet" href="css/custom.css">

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

    <script src="http://www.w3schools.com/lib/w3data.js"></script>

</head>

<body>

    <!-- Loader -->
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <div class="container">
        @if ($errors = request()->session()->get('errors'))
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
                <h1><a href="{{ route('index') }}">Čtvero ročních období</a></h1>
                <p>CB soutěž</p>
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
                            <a class="nav-link tm-text-gray" href="{{ route('index') . '#contact-form' }}">Kontakt</a>
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
                        <a href="{{ route('index') . '#contact-form' }}">Kontakt</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="row" id="tm-section-1">
            <div class="col-lg-12 tm-slider-col">
                <div class="tm-img-slider">
                    <div class="tm-img-slider-item" href="img/gallery-img-1.jpg">
                        <p class="tm-slider-caption">Vysílání nás baví !</p>
                        <img src="img/gallery-img-1.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="img/gallery-img-2.jpg">
                        <p class="tm-slider-caption">Výzva na kanále ...</p>
                        <img src="img/gallery-img-2.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="img/gallery-img-3.jpg">
                        <p class="tm-slider-caption">Stanice ... na příjmu</p>
                        <img src="img/gallery-img-3.jpg" alt="Image" class="tm-slider-img">
                    </div>
                    <div class="tm-img-slider-item" href="img/gallery-img-4.jpg">
                        <p class="tm-slider-caption">27 MHz - pásmo 11m</p>
                        <img src="img/gallery-img-4.jpg" alt="Image" class="tm-slider-img">
                    </div>
                </div>
            </div>
        </section>

        @yield('sections')

        <footer class="mt-5">
            <p class="text-center">Copyright © 2021 Radek Lichnov - Design: Tooplate</p>
        </footer>
    </div>

    <!-- load JS files -->
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script src="js/popper.min.js"></script>
    <!-- https://popper.js.org/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script type="text/javascript" src="slick/slick.min.js"></script>
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

            // https://css-tricks.com/snippets/jquery/smooth-scrolling/
            // Select all links with hashes
            $('a[href*="#"]')
                // Remove links that don't actually link to anything
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function (event) {
                    // On-page links
                    if (
                        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                        &&
                        location.hostname == this.hostname
                    ) {
                        // Figure out element to scroll to
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        // Does a scroll target exist?
                        if (target.length) {
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top + 1
                            }, 1000, function () {
                                // Callback after animation
                                // Must change focus!
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                } else {
                                    $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                                    $target.focus(); // Set focus again
                                };
                            });
                        }
                    }
                });
        });
    </script>

</body>

</html>
