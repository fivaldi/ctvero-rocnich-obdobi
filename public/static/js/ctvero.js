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
            scrollTop: $(decodeURIComponent(location.hash)).offset().top
        });
    }
});
