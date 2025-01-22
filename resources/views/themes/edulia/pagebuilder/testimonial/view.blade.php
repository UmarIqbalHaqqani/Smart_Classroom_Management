@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.css') }}">
@endpushonce
<section class="section_padding tesimonials about">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('testimonial_sub_heading') }}</span>
                    <h2>{{ pagesetting('testimonial_heading') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                <x-testimonial :count="pagesetting('testionmonial_count')" :sorting="pagesetting('testionmonial_sorting')"> </x-testimonial>
            </div>
        </div>
    </div>
</section>
@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/carousel/owl.carousel.min.js') }}"></script>
    <script>
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
    </script>
@endpushonce
