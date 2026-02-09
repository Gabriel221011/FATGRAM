(function ($) {

  /**
   * Nav widgets JS
   */
  var WidgetBlogNewsNavMenu = function( $scope, $ ){ 
    const wId = $scope.data("id");
    const wrapper = document.querySelector(`.elementor-element-${wId}`);
    const outerWrapper = wrapper.querySelector(".blognews-outer-wrapper");

    var acc = Array.from(wrapper .getElementsByClassName("arrow-sb"));
    acc.forEach(function (item) {
       item.addEventListener("click", function () {
          this.classList.toggle("active");
          var panel = this.nextElementSibling;
          if (panel.style.display === "block") {
             panel.style.display = "none";
          } else {
             panel.style.display = "block";
          }
       });
    });

    if ($scope.find('.blogfoel-menus-wrapper').length) {
      wrapper.querySelector('.blogfoel-menu-hamburger-icon').onclick = function (e) {
        var nav = wrapper.querySelector('.blogfoel-menu-hamburger-icon'); 
        var main_nav = wrapper.querySelector('.blogfoel-main-menu'); 
        nav.classList.toggle('on'); 
        main_nav.classList.toggle('show'); 
        e.preventDefault();
        $("#blogfoel-main-nav-menu").css("transition", "all 0.8s"); 
        $scope.find('.blogfoel-menu-hamburger-icon').find('.fa-bars').toggleClass('fa-times');
      } 
    }

    wrapper.querySelectorAll('.blogfoel-main-sb-menu > .has-children').forEach(function (menuItem) {
      menuItem.addEventListener('mouseenter', function () {
          const submenu = menuItem.querySelector('.blogfoel-main-sb-menu');
          const rect = submenu.getBoundingClientRect();
          console.log([rect.right, window.innerWidth - 70]);
          if ((rect.right > (window.innerWidth - 70) && submenu.style.right === '') || (rect.left < 0 && submenu.style.left === '')) {
            // Toggle direction only if required
            if (rect.right > (window.innerWidth - 70)) {
                submenu.style.left = 'auto';
                submenu.style.right = '100%';
                submenu.parentElement.classList.add('sb-left');
            } else {
                submenu.style.left = '100%';
                submenu.style.right = 'auto';
            }
          }
      });
    });

    function updateWidth() {
      var deviceWidth = window.innerWidth;
      var elements = wrapper.querySelectorAll(".blognews-mobile-menu-full-width-yes .blogfoel-main-menu");
      
      elements.forEach(function(element) {
        if (deviceWidth <= 991) {
          // Set width with calc
          element.style.width = `calc(${document.documentElement.clientWidth}px - 40px)`;
  
          const rect = element.closest('.elementor-widget').getBoundingClientRect();
          var leftextra = rect.left - 20;
          // console.log(leftextra);
          // Handle right overflow
          element.style.left = '-'+ leftextra + 'px';  // Clear left property
          element.style.right = 'unset';     // Set right property
        } else {
          // Reset styles when window becomes larger again
          elements.forEach(function(element) {
            element.style.width = '';
            element.style.left = '';
            element.style.right = '';
          });
        }
      });
    }
    
    window.addEventListener('resize', updateWidth);
    updateWidth();

  }

  jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/blognews-nav-menu.default', WidgetBlogNewsNavMenu);
  });
  
  var WidgetBlogNewsTime = function ($scope) {
    var myVar = setInterval(function () {
        myTimer();
    }, 100);

    function myTimer() {
        var timeElement = $scope.find("#time")[0]; // Using $scope to limit selection
        if (timeElement) {
            var d = new Date();
            timeElement.innerHTML = d.toLocaleTimeString();
        }
    }
  }
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/date-time-widget.default", WidgetBlogNewsTime);
  });

  var newsticker = function( $scope, $ ){ 
  
    var ticker = $scope.find( ".blogfoel-latest-news" );
    var mainDiv = $scope.find('.blogfoel-latest-news-slider');
    var PauseVal =  (mainDiv.attr('tickerHover') == 'yes' ) ? true : false;
    var tickerSlide = mainDiv.marquee({
      speed: mainDiv.attr('tickerSpeed'),
      direction: mainDiv.attr('tickerDirection'), 
      delayBeforeStart: 0,
      duplicated: true,
      pauseOnHover: PauseVal,
      startVisible: true
    });
    ticker.on( "click", ".blogfoel-latest-play span", function() {
      $(this).find( "i" ).toggleClass( "fa-pause fa-play" )
      tickerSlide.marquee( "toggle" );
    })
  }
  jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/blognews-post-ticker.default', newsticker);
  });
/* =================================
  ===         SCROLL TOP       ====
  =================================== */
  var blogfoelScrollToTop = function( $scope, $ ){ 
    const wId = $scope.data("id");
	  const wrapper = document.querySelector(`.elementor-element-${wId}`);

    var scrollToTopBtn = wrapper.querySelector(".blogfoel-upscr");
    var rootElement = document.documentElement;
    
    function handleScroll() {
      // Do something on scroll
      var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
      if (rootElement.scrollTop / scrollTotal > 0.05) {
        // Show button
        scrollToTopBtn.classList.add("showBtn");
      } else {
        // Hide button
        scrollToTopBtn.classList.remove("showBtn");
      }
    }
    
    function scrollToTop(event) {
      event.preventDefault();
      // Scroll to top logic
      rootElement.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }
    if (scrollToTopBtn) {
      scrollToTopBtn.addEventListener("click", scrollToTop);
      document.addEventListener("scroll", handleScroll);
    }
  
  }
  jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/blognews-scroll-to-top.default', blogfoelScrollToTop);
  });
  const blogfoelLightDarkToggle = ($scope, $) => {
    const wId = $scope.data("id");
    const el = document.querySelector(`.elementor-element-${wId}`);

    const toggle = el.querySelector("#blogfoel-switch");
    const body = document.body;
    const darkModeClass = "blogfoel-dark-mode";
    const cookieName = toggle.dataset.cookie;

    // --- Cookie helpers ---
    function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
    }

    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = `${name}=${value || ""}${expires}; path=/`;
    }

    // --- Restore previous mode ---
    const savedMode = getCookie(cookieName);
    if (savedMode === "dark") {
      body.classList.add(darkModeClass);
      if (toggle) toggle.checked = true;
    } else {
      body.classList.remove(darkModeClass);
      if (toggle) toggle.checked = false;
    }

    // --- Handle switch ---
    if (toggle) {
      toggle.addEventListener("change", function () {
        if (this.checked) {
          body.classList.add(darkModeClass);
          setCookie(cookieName, "dark", 365);
        } else {
          body.classList.remove(darkModeClass);
          setCookie(cookieName, "light", 365);
        }
      });
    }
  };

  jQuery(window).on("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/blognews-light-dark-toggle.default",blogfoelLightDarkToggle );
  });

} )( jQuery );