   <!-- blog area start -->
   <section class="section_padding blog index-blog">
    <div class="container">
        @if(pagesetting('news_area_sub_heading') || pagesetting('news_area_heading') || pagesetting('news_area__description'))
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section_title">
                    @empty(!pagesetting('news_area_sub_heading'))
                        <span class="section_title_meta">{{ pagesetting('news_area_sub_heading') }}</span>
                    @endempty
                    @empty(!pagesetting('news_area_heading'))
                        <h2>{{ pagesetting('news_area_heading') }}</h2>
                    @endempty
                    @empty(!pagesetting('news_area__description'))
                        <p>{!! pagesetting('news_area__description') !!}</p>
                    @endempty
                    
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <x-news :colum="pagesetting('news_area_column')" :count="pagesetting('news_count')" :readmore="pagesetting('read_more_btn')" :sorting="pagesetting('news_sorting')"></x-news>
        </div>
    </div>
</section>
<!-- blog area end -->