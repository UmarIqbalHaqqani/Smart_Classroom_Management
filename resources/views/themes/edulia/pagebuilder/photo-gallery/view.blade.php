<section class="section_padding gallery">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('photo_gallery_sub_title') }}</span>
                    <h2>{{ pagesetting('photo_gallery_title') }}</h2>
                </div>
            </div>
        </div>
        <x-photo-gallery :column="pagesetting('photo_gallery_column')" :count="pagesetting('photo_gallery_count')"></x-photo-gallery>
    </div>
</section>
