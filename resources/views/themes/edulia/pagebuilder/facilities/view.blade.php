<section class="section_padding facilities">
    <div class="container">
        @if(pagesetting('facilities_image_align') == 'right')
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-6">
                    <div class="facilities_inner_text">
                        <h3>{{ pagesetting('facilities_heading') }}</h3>
                        {!! pagesetting('facilities_description') !!}
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 col-md-6">
                    @if(pagesetting('facilities_image_upload'))
                        <div class="facilities_img">
                            <img src="{{ pagesetting('facilities_image_upload')[0]['thumbnail'] }}" alt="">
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="row align-items-center mobile-column-reverse">
                <div class="col-md-6">
                    @if(pagesetting('facilities_image_upload'))
                        <div class="facilities_img">
                            <img src="{{ pagesetting('facilities_image_upload')[0]['thumbnail'] }}" alt="">
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="facilities_inner_text facilities_inner_right">
                        <h3>{{ pagesetting('facilities_heading') }}</h3>
                        {!! pagesetting('facilities_description') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>