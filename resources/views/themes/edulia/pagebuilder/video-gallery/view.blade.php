@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/magnific/magnific-popup.min.css') }}">
@endpushonce
<div class="section_padding">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('video_sub_heading') }}</span>
                    <h2>{{ pagesetting('video_heading') }}</h2>
                </div>
            </div>
        </div>
        <x-video-gallery :column="pagesetting('video_gallery_column')" :count="pagesetting('video_gallery_count')"></x-video-gallery>
    </div>
</div>
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $(document).ready(function() {
            $('.gallery_item.video').magnificPopup({
                type: 'iframe',
            });
        });
    </script>
    <script src="{{ asset('public/theme/edulia/packages/magnific/jquery.magnific-popup.min.js') }}"></script>
@endpushonce
