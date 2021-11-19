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

function errorTooltip(element, data) {
    $(element).data('toggle', 'tooltip');
    $(element).data('placement', 'top');
    $(element).data('html', true);
    $(element).attr('title', data.responseJSON['errors'].join('<br>'));
    $(element).tooltip('show');
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
    // and register special handlers for links which open modal dialogs
    $('.nav-link').each(function () {
        var clicked = this;
        $(clicked).click(function(e) {
            if (!$(clicked).hasClass('to-modal')) {
                $('#mainNav').removeClass('show');
                return;
            }
            e.preventDefault();
            $(clicked).tooltip('dispose');
            if (location.href.replace(location.hash, '') !== $(clicked).data('url')) {
                $.get($(clicked).data('url'), function(data) {
                    $('#mainNav').removeClass('show');
                    $('#' + $(clicked).data('modal-id') + '-modal .modal-body').html(data);
                    $('#' + $(clicked).data('modal-id') + '-modal').modal('show');
                }).fail(function(data) {
                    errorTooltip(clicked, data);
                });
            } else {
                location.hash = 'scroll';
            }
        });
    });

    // Fix anchor scrolling and Facebook's ugly security hack, see
    // https://stackoverflow.com/questions/7131909/facebook-callback-appends-to-return-url/41917323#41917323
    if (location.hash == '#_=_') {
        history.replaceState(null, null, ' ');
    } else if (location.hash !== '') {
        $('html, body').animate({
            scrollTop: $(decodeURIComponent(location.hash)).offset().top
        });
    }
});
