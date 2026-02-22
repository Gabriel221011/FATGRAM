jQuery(document).ready(function ($) {
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $(".back-to-top a").fadeIn();
    } else {
      $(".back-to-top a").fadeOut();
    }
  });

  $(".back-to-top a").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 800);
    return false;
  });

  //slider swiper
  var healthy_food_blogger_Slider = new Swiper(".healthy-food-blogger-slider", {
    breakpoints: {
      0: {
        slidesPerView: 1,
        centeredSlides: false,
      },
      1000: {
        slidesPerView: 2,
        centeredSlides: false,
      },
      1200: {
        slidesPerView: 3,
        centeredSlides: true,
      }
    },
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".healthy-food-blogger-pagination",
      clickable: true,
    }
  });

  //projects-swiper
  var healthy_food_blogger_project_Slider = new Swiper(".projects-slider", {
    breakpoints: {
      0: {
        slidesPerView: 1,
        centeredSlides: false,
      },
      1000: {
        slidesPerView: 2,
        centeredSlides: false,
      },
      1200: {
        slidesPerView: 3,
        centeredSlides: true,
      }
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    spaceBetween: 30,
    loop: true,
    navigation: {
      nextEl: ".projects-swiper-button-next",
      prevEl: ".projects-swiper-button-prev",
    },
  });
  
});
