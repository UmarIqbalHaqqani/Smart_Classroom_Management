<section class="section_padding noticeboard pt-0 mt-5">
    <div class="container">
        <div class="row m-0">
            <div class="offset-lg-1 col-lg-10 px-0">
                <div class="noticeboard_inner">
                    <div class="noticeboard_inner_head">
                        <h5>{{ pagesetting('notice_heading') }}</h5>
                    </div>
                    <div class='noticeboard_inner_wrapper'>
                        <x-notice :count="pagesetting('notice_count')" :btn="pagesetting('view_detail_btn')"> </x-notice>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
