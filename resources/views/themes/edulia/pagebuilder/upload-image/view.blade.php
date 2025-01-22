<div class="col-lg-12">
    <div class="d-flex {{ pagesetting('upload_image_alignment') ? (pagesetting('upload_image_alignment') == 'center' ? 'justify-content-center' : (pagesetting('upload_image_alignment') == 'left' ? 'justify-content-start' : 'justify-content-end')) : '' }}">
        @if (pagesetting('upload_image_file'))
            <a href="{{ pagesetting('upload_image_link') }}">
                <img src="{{ pagesetting('upload_image_file')[0]['thumbnail'] }}" alt="" width="{{pagesetting('upload_image_width_percent')}}%">
            </a>
        @endif
    </div>
</div>