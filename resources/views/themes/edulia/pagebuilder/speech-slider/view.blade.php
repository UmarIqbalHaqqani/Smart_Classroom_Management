@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.css') }}">
    <style>
        .home_speech_section .owl-carousel .owl-nav button, .home_speech_section .owl-carousel .owl-nav button {
            background: #ffffff;
        }
    </style>
@endpushonce

<section class="section_padding home_speech_section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('speech_slider_heading') }}</span>
                    <h2>{{ pagesetting('speech_slider_sub_heading') }}</h2>
                </div>
            </div>
        </div>
        <x-sm-speech-slider :count="pagesetting('speech_slider_count')"> </x-sm-speech-slider>
    </div>
</section>

@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.js') }}"></script>
    <script>
        $('.home_speech_section .owl-carousel').owlCarousel({
            nav: false,
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
    </script>
@endpushonce
