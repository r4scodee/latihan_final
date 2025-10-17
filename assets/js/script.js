(function($) {
    "use strict";

    //Preloader js{ 
    $('.preloader_wrap').delay(400).fadeOut('slow');
    $('.preloader').delay(350).fadeOut('slow');

    // Active Slick Nav 
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 100) {
            $('#header-area').addClass('sticky');
        } else {
            $('#header-area').removeClass('sticky');
        }
    });


    jQuery(document).ready(function($) {

        //Mobile Menu Js Start //
        $(".main-menu").meanmenu({
            meanMenuContainer: ".mobile-menu",
            meanScreenWidth: "1199",
            meanExpand: ['<i class="far fa-plus"></i>'],
        });

        // Sidebar Toggle Js Start //
        $(".offcanvas__close,.offcanvas__overlay").on("click", function() {
            $(".offcanvas__info").removeClass("info-open");
            $(".offcanvas__overlay").removeClass("overlay-open");
        });
        $(".sidebar__toggle").on("click", function() {
            $(".offcanvas__info").addClass("info-open");
            $(".offcanvas__overlay").addClass("overlay-open");
        });

        // Body Overlay Js Start //
        $(".body-overlay").on("click", function() {
            $(".offcanvas__area").removeClass("offcanvas-opened");
            $(".df-search-area").removeClass("opened");
            $(".body-overlay").removeClass("opened");
        });


    });


    /*START Mini Cart JS*/

    $('.mcart_icon').on('click', function() {
        var menu = $(this).attr('data-menu');

        $(menu).toggleClass('min_cart_active');


    });

    $('.min_cart_wrapper').on('click', function(event) {
        if ($(event.target).hasClass('min_cart_wrapper')) {
            $('.min_cart_active').removeClass('min_cart_active');
        }
    });

    $('.cart_close').on('click', function(event) {
        $('.min_cart_active').removeClass('min_cart_active');
    });


    /*END Mini Cart JS*/

    // Hero Slider
    const hero_slider = new Swiper('.hero_slider', {
        // Optional parameters
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        // Navigation arrows
        navigation: {
            nextEl: '.hs_next_arrow',
            prevEl: '.hs_prev_arrow',
        },
        pagination: {
            el: ".hero_pagination",
            clickable: true,
        },
        effect: "slide",
        breakpoints: {
            1299: {
                slidesPerView: 1,
            },
            1199: {
                slidesPerView: 1,
            },
            1024: {
                slidesPerView: 1,
            },
            991: {
                slidesPerView: 1,
            },

            767: {
                slidesPerView: 1,
            },
            575: {
                slidesPerView: 1,
            },
            0: {
                slidesPerView: 1,
            },
        },
    });


    //Category
    $('#category-slider').owlCarousel({
        loop: true,
        item: 6,
        margin: 25,
        navText: ["<i class='ph ph-arrow-up-left'></i>", "<i class='ph ph-arrow-up-right'></i>"],
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            440: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 4
            },
            1199: {
                items: 6
            }
        }
    });


    // Popular Products
    $(document).ready(function() {
        var el = $('.pproduct_slider');

        var carousel;
        var carouselOptions = {
            margin: 25,
            nav: true,
            dots: true,
            loop: true,
            navText: ["<i class='ph ph-arrow-up-left'></i>", "<i class='ph ph-arrow-up-right'></i>"],
            slideBy: 'page',
            responsive: {
                0: {
                    items: 1,
                    rows: 1 //custom option not used by Owl Carousel, but used by the algorithm below
                },
                768: {
                    items: 1,
                    rows: 1 //custom option not used by Owl Carousel, but used by the algorithm below
                },
                991: {
                    items: 1,
                    rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
                },
                1199: {
                    items: 2,
                    rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
                }
            }
        };

        //Taken from Owl Carousel so we calculate width the same way
        var viewport = function() {
            var width;
            if (carouselOptions.responsiveBaseElement && carouselOptions.responsiveBaseElement !== window) {
                width = $(carouselOptions.responsiveBaseElement).width();
            } else if (window.innerWidth) {
                width = window.innerWidth;
            } else if (document.documentElement && document.documentElement.clientWidth) {
                width = document.documentElement.clientWidth;
            } else {
                console.warn('Can not detect viewport width.');
            }
            return width;
        };

        var severalRows = false;
        var orderedBreakpoints = [];
        for (var breakpoint in carouselOptions.responsive) {
            if (carouselOptions.responsive[breakpoint].rows > 1) {
                severalRows = true;
            }
            orderedBreakpoints.push(parseInt(breakpoint));
        }

        //Custom logic is active if carousel is set up to have more than one row for some given window width
        if (severalRows) {
            orderedBreakpoints.sort(function(a, b) {
                return b - a;
            });
            var slides = el.find('[data-slide-index]');
            var slidesNb = slides.length;
            if (slidesNb > 0) {
                var rowsNb;
                var previousRowsNb = undefined;
                var colsNb;
                var previousColsNb = undefined;

                //Calculates number of rows and cols based on current window width
                var updateRowsColsNb = function() {
                    var width = viewport();
                    for (var i = 0; i < orderedBreakpoints.length; i++) {
                        var breakpoint = orderedBreakpoints[i];
                        if (width >= breakpoint || i == (orderedBreakpoints.length - 1)) {
                            var breakpointSettings = carouselOptions.responsive['' + breakpoint];
                            rowsNb = breakpointSettings.rows;
                            colsNb = breakpointSettings.items;
                            break;
                        }
                    }
                };

                var updateCarousel = function() {
                    updateRowsColsNb();

                    //Carousel is recalculated if and only if a change in number of columns/rows is requested
                    if (rowsNb != previousRowsNb || colsNb != previousColsNb) {
                        var reInit = false;
                        if (carousel) {
                            //Destroy existing carousel if any, and set html markup back to its initial state
                            carousel.trigger('destroy.owl.carousel');
                            carousel = undefined;
                            slides = el.find('[data-slide-index]').detach().appendTo(el);
                            el.find('.extra-col-wrapper').remove();
                            reInit = true;
                        }


                        //This is the only real 'smart' part of the algorithm

                        //First calculate the number of needed columns for the whole carousel
                        var perPage = rowsNb * colsNb;
                        var pageIndex = Math.floor(slidesNb / perPage);
                        var fakeColsNb = pageIndex * colsNb + (slidesNb >= (pageIndex * perPage + colsNb) ? colsNb : (slidesNb % colsNb));

                        //Then populate with needed html markup
                        var count = 0;
                        for (var i = 0; i < fakeColsNb; i++) {
                            //For each column, create a new wrapper div
                            var fakeCol = $('<div class="extra-col-wrapper"></div>').appendTo(el);
                            for (var j = 0; j < rowsNb; j++) {
                                //For each row in said column, calculate which slide should be present
                                var index = Math.floor(count / perPage) * perPage + (i % colsNb) + j * colsNb;
                                if (index < slidesNb) {
                                    //If said slide exists, move it under wrapper div
                                    slides.filter('[data-slide-index=' + index + ']').detach().appendTo(fakeCol);
                                }
                                count++;
                            }
                        }
                        //end of 'smart' part

                        previousRowsNb = rowsNb;
                        previousColsNb = colsNb;

                        if (reInit) {
                            //re-init carousel with new markup
                            carousel = el.owlCarousel(carouselOptions);
                        }
                    }
                };

                //Trigger possible update when window size changes
                $(window).on('resize', updateCarousel);

                //We need to execute the algorithm once before first init in any case
                updateCarousel();
            }
        }

        //init
        carousel = el.owlCarousel(carouselOptions);

    });

    // Testimonials
    var galleryThumbs = new Swiper('.gallery-thumbs', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: '2',
        // coverflowEffect: {
        //   rotate: 50,
        //   stretch: 0,
        //   depth: 100,
        //   modifier: 1,
        //   slideShadows : true,
        // },

        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 50,
            modifier: 6,
            slideShadows: false,
        },

    });

    // Testimonials
    var swiper = new Swiper(".test_thumbnail", {
        slidesPerView: 8,
        freeMode: true,
        centeredSlides: true,
        spaceBetween: 0,
        watchSlidesProgress: true,
    });

    var swiper2 = new Swiper(".test_bottom", {
        spaceBetween: 5,
        loop: true,
        navigation: {
            nextEl: ".test-arrow-next",
            prevEl: ".test-arrow-prev",
        },

        pagination: {
            el: ".test-pagination",
            clickable: true,
        },

        thumbs: {
            swiper: swiper,
        },
    });

    // Partner
    $('#partner-slider').owlCarousel({
        loop: true,
        item: 6,
        margin: 25,
        navText: ["<i class='ph ph-arrow-up-left'></i>", "<i class='ph ph-arrow-up-right'></i>"],
        nav: true,
        dots: false,
        responsive: {
            440: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 5
            },
            1199: {
                items: 6
            }
        }
    });

    /*Start MouseMove*/
    document.addEventListener("mousemove", parallax);

    function parallax(event) {
        this.querySelectorAll(".eitem").forEach((shift) => {
            const position = shift.getAttribute("alt");
            const x = (window.innerWidth - event.pageX * position) / 90;
            const y = (window.innerHeight - event.pageY * position) / 90;

            shift.style.transform = `translateX(${x}px) translateY(${y}px)`;
        });
    }
    /*End MouseMove*/

    //Header Category
    jQuery('.category_select').niceSelect();


    //------------- DETAIL ADD - MINUS COUNT ORDER -------------//
    $(".btn-number").on("click", function() {

        var $button = $(this);
        var oldValue = $button.closest('.quantity_option').find("input.quntity-input").val();

        if ($button.text() == "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }

        $button.closest('.quantity_option').find("input.quntity-input").val(newVal);

    });

    /* WOW */
    new WOW().init();

}(jQuery));