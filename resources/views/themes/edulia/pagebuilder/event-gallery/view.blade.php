<section class="section_padding events">
    <div class="container">
        <div class="row">
            <x-event-gallery 
                    :column="pagesetting('event_gallery_area_column')" 
                    :sorting="pagesetting('event_gallery_sorting')" 
                    :count="pagesetting('event_gallery_count')" 
                    :button="pagesetting('event_gallery_read_more_btn')" 
                    :dateshow="pagesetting('event_gallery_show_date')" 
                    :enevtlocation="pagesetting('event_gallery_show_location')"
            ></x-event-gallery>
        </div>
    </div>
</section>