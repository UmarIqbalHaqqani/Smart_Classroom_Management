@extends(config('pagebuilder.site_layout'), ['edit' => false])
@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/photoswipe/photoswipe.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/photoswipe/default-skin.min.css') }}">
@endpushonce
@section(config('pagebuilder.site_section'))
    {{ headerContent() }}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.gallery_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.gallery_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding gallery">
        <div class="container">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="gallery_details">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section_title mt-5">
                                <h2>{{ $gallery_feature->name }}</h2>
                                <p>{!! $gallery_feature->description !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" data-pswp>
                        <div class="col-md-12">
                            <a href='{{ asset($gallery_feature->feature_image) }}'
                                class="gallery_details_item gallery_details_img_preview"><img
                                    src="{{ asset($gallery_feature->feature_image) }}"
                                    alt=""></a>
                        </div>
                        @foreach ($galleries as $gallery)
                            <div class="col-md-6">
                                <a href='{{ asset($gallery->gallery_image) }}' class="gallery_details_item"><img
                                        src="{{ asset($gallery->gallery_image) }}" alt=""></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ footerContent() }}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/photoswipe/photoswipe.min.js') }}"></script>
    <script src="{{ asset('public/theme/edulia/packages/photoswipe/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ asset('public/theme/edulia/packages/photoswipe/photoswipe-simplify.min.js') }}"></script>
    <script>
        photoswipeSimplify.init({
            history: false,
            focus: false,
        });
    </script>
@endpushonce
