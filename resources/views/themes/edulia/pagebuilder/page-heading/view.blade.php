<div class="container">
    <div class="row mt-5">
        <div class="col-md-10 offset-md-1 col-sm-12 text-center">
            <div class="facilities_title">
                <h2>{{ !empty(pagesetting('page_heading_title')) ? pagesetting('page_heading_title') : '' }}</h2>
                @if (!empty(pagesetting('page_heading_sub_title')))
                    <p>{!! pagesetting('page_heading_sub_title') !!}</p>
                @endif
            </div>
        </div>
    </div>    
</div>