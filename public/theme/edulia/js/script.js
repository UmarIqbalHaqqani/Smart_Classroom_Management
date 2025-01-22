(function() {
    'use strict';

    // ABOUT FILTER BTN
    let filterBtn = document.querySelectorAll('.about-filter');
    let aboutInfo = document.querySelectorAll('.course_details_abouts_item');
    filterBtn.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let value = e.target.dataset.name;
            aboutInfo.forEach(function(item) {
                if (item.classList.contains(value)) {
                    item.style.display = 'block'
                } else {
                    item.style.display = 'none'
                }
            })
        })
    })

    // PRELOADER JS
    $(window).on('load', function() {
        $('body').css('overflow', 'hidden');
        setTimeout(function() {
            $(".preloader").fadeOut(500);
            $('body').css('overflow', 'visible');
        }, 1200);
    });

    // back to top js
    $('body').append('<!--back to top btn--><a href="#" class="backtop"><i class="far fa-long-arrow-alt-up"</i>')
    $(window).on('scroll', function() {
        var x = $(window).scrollTop();
        if (x > 700) {
            $('.backtop').addClass('show')
        } else {
            $('.backtop').removeClass('show')

        }
    })


    jQuery(document).ready(function() {

        // edit checkout info
        $(document).on('click', '.edit_address', function(e) {
            e.stopPropagation();
            $(this).parent().parent().find('.checkouts_inner_item_card_item_input').toggle();
            $(this).parent().parent().find('.checkouts_inner_item_card_item_info').toggle();
            let i = $(this).find('i');

            if (i.hasClass('fa-pencil-alt')) {
                i.removeClass('fa-pencil-alt').addClass('fa-check')
            } else {
                i.removeClass('fa-check').addClass('fa-pencil-alt')
            }
        })

        // add info
        $(document).on('click', '.checkouts_inner_item_head .site_btn_border', function(e) {
            e.preventDefault();
            $('.add_info').removeClass('fade');
        })
        $(document).on('click', '.add_info_inner_close, .add_info_shape', function(e) {
            $('.add_info').addClass('fade');
        })

        // HERO SLIDER
        $('.hero_area_slider').owlCarousel({
            nav: true,
            navText: ['<i class="far fa-angle-left"></i>', '<i class="far fa-angle-right"></i>'],
            dots: false,
            items: 1,
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,

        });

        // TESTIMONIAL SLIDER
        $('.tesimonials_slider').owlCarousel({
            nav: false,
            navText: ['<i class="fal fa-angle-left"></i>', '<i class="fal fa-angle-right"></i>'],
            dots: true,
            dotsData: true,
            items: 1,
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,

        });

        // HERO SLIDER
        $('.home_speech_section .owl-carousel').owlCarousel({
            nav: true,
            navText: ['<i class="far fa-angle-left"></i>', '<i class="far fa-angle-right"></i>'],
            dots: false,
            items: 3,
            loop: true,
            margin: 20,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive:{
                0: {
                    items: 1,
                    nav: false,
                },
                576:{
                    nav: true,
                    items: 1,
                },
                767:{
                    items: 2,
                },
                991:{
                    items: 3,
                },
            }
        });

        // ACTIVE MOBLE MENU JS
        // MOBILE MENU ACTIVE JS
        var zeynep = $('.zeynep').zeynep({})
        $('.heading_mobile_thum').on('click', function() {
            zeynep.open()
            $('.bg-shade').fadeIn();
        })
        $('.bg-shade').on('click', function() {
            zeynep.close()
            $('#mobile_languages').hide();
        })
        $(document).on('click', '[data-mobile-language]', function(e) {
            $('#mobile_languages').show();
        })
        $(document).on('click', '.bg-shade', function(e) {
            $(this).fadeOut();
        })

        // COUNTRY SLECET JS
        $('#options').flagStrap({
            countries: {
                "US": "en",
                "GB": "uk",
                "AU": "au",
            },
        });
        $('.dropdown-toggle').on('click', function(e) {
            e.stopPropagation();
            $('.dropdown-menu').toggleClass('show')
        })
        $(document).on('click', function(e) {
            if (!$(e.target).is('.dropdown-menu')) {
                $(".dropdown-menu").removeClass('show')
            }
        });

        // ADD BUTTON ARIA LABEL IN OWL CAROUSEL
        $('.owl-carousel').each(function() {
            $(this).find('.owl-dot').each(function(index) {
                $(this).attr('aria-label', index + 1);
            });
        });

        // SCROLL AFTER FIX MAIN HEADER
        $(window).on('scroll', function() {
            var x = $(window).scrollTop();
            if (x > 500) {
                $('.heading_main').addClass('fixed-nav');
            } else {
                $('.heading_main').removeClass('fixed-nav')

            }
        })

        // MOBILE SEARCH BOX ACTIVE JS
        $('[data-mobile-search]').on('click', function(e) {
            console.log(e);
            e.stopPropagation();
            $('.m_s').fadeToggle('fast')
        });
        $(document).on('click', function(e) {
            if (!$(e.target).is('.m_s *')) {
                $('.m_s').fadeOut('fast')
            }
        })

        // CUSTOM SELECT BOX
        $('select').niceSelect();

        // custom counter products
        $('input[type=number].quantity_input').niceNumber();

        // COUSTOM NUMBER COUNTER
        $('.products_inner input[type=number]').niceNumber();

        // ACTIVE MOBILE FILTER
        $('.course_filtering_head_filter_mobile').on('click', function(e) {
            $('.course_filter').addClass('course_filter_active')
        })
        $('.course_filter_close').on('click', function(e) {
            $('.course_filter').removeClass('course_filter_active')
        })

        // ACTIVE MOBILE FILTER
        $('.products_filtering_head_filter_mobile').on('click', function(e) {
            $('.products_filter').addClass('products_filter_active')
        })
        $('.products_filter_close').on('click', function(e) {
            $('.products_filter').removeClass('products_filter_active')
        })

        // ABOUT FILTER ACTIVE 
        $('.about-filter').on('click', function(e) {
            e.preventDefault();
            $('.about-filter').removeClass('active')
            $(this).addClass('active')
        })

        // MAGNIFIG POPUP
        $('.accordion-body-item').magnificPopup({
            type: 'video',
            gallery: { enabled: false },
            scroll: false,
        });
        // ACTIVE IMAGE GALLERY
        $(".events_details_gallery_img").magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        })

        // DATEPICKER
        $('#datepicker1').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy',
        });
        $('#datepicker2').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy'
        });
        $('#datepicker3').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy'
        });
        $('#datepicker4').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy'
        });

        // CHECK FORM STATUS 
        $('.reg_wrapper_head a').on('click', function(e) {
            e.preventDefault();
            $('.reg_wrapper_check, .bg-shade').addClass('show')
        })
        $('.reg_wrapper_check_btns_close, .bg-shade').on('click', function(e) {
            e.preventDefault();
            $('.reg_wrapper_check, .bg-shade').removeClass('show')
        })

        // SELECT COURSE
        $('#select2').select2();

        // time picker js
        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '10',
            maxTime: '6:00pm',
            defaultTime: '11',
            startTime: '10:00',
            dynamic: false,
            dropdown: true,
            scrollbar: false
        });

        // login page
        $('#signup').on('click', function(e) {
            e.preventDefault();
            $('.login_wrapper_signup_content').removeClass('d-none');
            $('.login_wrapper_login_content').addClass('d-none');
        })
        $('#signin').on('click', function(e) {
            e.preventDefault();
            $('.login_wrapper_signup_content').addClass('d-none');
            $('.login_wrapper_login_content').removeClass('d-none');
        })

        // price range slider
        $(".products_filters_item_slider").ionRangeSlider({
            type: "double",
            grid: false,
            min: 5,
            max: 300,
            from: 60,
            to: 190,
            prefix: '$',
        });

        // ZOOM LENS
        // Using custom configuration
        $('#img-1').ezPlus({
            zoomWindowFadeIn: 500,
            zoomLensFadeIn: 500,
            gallery: 'gallery-1',
            imageCrossfade: true,
            zoomWindowOffsetX: 10,
            scrollZoom: false,
            cursor: 'pointer'
        });

        // mini cart for mobile 
        $(document).on('click', '[data-mobile-cart]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.heading_mobile').find('.mini_cart').toggleClass('mini_cart_active');
        })
        $(document).on('click', function(e) {
            if (!$(e.target).is('.mini_cart *')) {
                $('.mini_cart').removeClass('mini_cart_active');
            }
        })

        // event register
        $('.events_details_sidebar_info').on('click', function() {
            $('.events_reg').addClass('show');
            $('body').css('overflow', 'hidden')
        })
        $('.events_reg_inner_close').on('click', function() {
            $('.events_reg').removeClass('show');
            $('body').css('overflow', 'visible')
        })


        // light box
        photoswipeSimplify.init({
            history: false,
            focus: false,
        });

        // carts notes
        $(document).on('click', '#add_cart_note', function(e) {
            e.preventDefault();
            $(this).hide();
            $(this).closest('div').find('form').show();
        })

        // CUPON CODE CHECK 
        $(document).on('click', '.checkouts_cupon button', function(e) {
            e.preventDefault();

            const error = $(this).closest('div').find('.checkouts_cupon_error');
            const success = $(this).closest('div').find('.checkouts_cupon_success');
            const form = $(this).closest('div').find('form');

            let code = $(this).parent().find('input').val();

            if (code == '' || code === ' ') {
                error.fadeIn();
            } else {
                error.hide();
                form.hide();
                success.fadeIn();
            }
        })
        $(document).on('click', '[data-cupon-remove]', function(e) {
            $('.checkouts_cupon_success').hide();
            $('.checkouts_cupon form').show();
        })

    });

    // notification area

    var isAnimating = false;

    $(function() {
        let tickerLength = $('.notification-container ul li').length;
        let tickerHeight = $('.notification-container ul li').outerHeight();
        $('.notification-container ul li:last-child').prependTo('.notification-container ul');
        $('.notification-container ul').css('marginTop', -tickerHeight);
        
        var timer;
        function moveTop() {
            if (!isAnimating) {
                isAnimating = true;
                $('.notification-container ul').animate({
                  top: -tickerHeight - 10
                }, 600, function() {
                  isAnimating = false;
                  $('.notification-container ul li:first-child').appendTo('.notification-container ul');
                  $('.notification-container ul').css('top', '');
                });
            }
        }
        
        // Check if the mouse is hovered over the notification container
        var isHovered = false;
        $('.notification-container').hover(function() {
          isHovered = true;
        }, function() {
          isHovered = false;
        });
        
        // Pause the animation when the mouse is hovered over the notification container
        timer = setInterval(moveTop, 5000);
        $('.notification-container').on('mouseenter', function() {
          clearInterval(timer);
        }).on('mouseleave', function() {
          timer = setInterval(moveTop, 5000);
        });
    });

    // form download table data table

    $(".common_data_table table").DataTable({
        responsive: true,
        stripeClasses:[],
        language: {
            searchPlaceholder: "Search ...",
            search: "<i class='far fa-search datatable-search'></i>",
        },
    });
    
    $('.common_data_table .dataTables_length label select').niceSelect('destroy');
    $(".common_data_table .dataTables_length label select").select2({
        minimumResultsForSearch: Infinity
    });

    // video gallery

    $(document).ready(function() {
        $('.gallery_item.video').magnificPopup({
            type:'iframe',
        });
      });

    //   Search show hide table
    $('.user_list_container.student_list').hide();
    $('#searchStudent').on('click', function(e){
        e.preventDefault();
        $('.user_list_container.student_list').show('slow')
    })

})();
