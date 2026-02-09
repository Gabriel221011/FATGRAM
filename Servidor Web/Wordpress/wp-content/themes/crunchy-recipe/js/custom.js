jQuery(document).ready(function($){

    var slider_auto, slider_loop, rtl;

    if (yummy_bites_data.auto == '1') {
        slider_auto = true;
    } else {
        slider_auto = false;
    }

    if (yummy_bites_data.loop == '1') {
        slider_loop = true;
    } else {
        slider_loop = false;
    }

    if (yummy_bites_data.rtl == '1') {
        rtl = true;
    } else {
        rtl = false;
    }

    //slider five
    $('.banner-slider.style-five .item-wrapper').owlCarousel({
        items: 4,
        autoplay: slider_auto,
        loop: slider_loop,
        nav: true,
        dots: false,
        margin: 0,
        autoplaySpeed: 800,
        rtl: rtl,
        autoplayTimeout: yummy_bites_data.speed,
        animateOut: yummy_bites_data.animation,
        responsive: {
            0: {
                items: 1,
            },

            767: {
                items: 2,
            },
            992: {
                items: 4,
                padding: 0,
            },
        }
    });
});
