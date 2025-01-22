@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.css') }}">
@endpushonce

<x-home-page-slider :count="pagesetting('home_slider_count')"> </x-home-page-slider>

@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.js') }}"></script>
    <script>
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
    </script>
@endpushonce
