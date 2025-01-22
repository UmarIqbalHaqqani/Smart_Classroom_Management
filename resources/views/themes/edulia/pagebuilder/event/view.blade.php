@pushonce(config('pagebuilder.site_style_var'))
    <style>
        a.event_title h4 {
            max-width: 15ch;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpushonce
<section class="section_padding events index-events">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5">
                @if (!empty(pagesetting('event_img')))
                    <div class="events_preview_img">
                        <img src="{{ pagesetting('event_img')[0]['thumbnail'] }}" alt="{{ __('edulia.Image') }}">
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('event_sub_heading') }}</span>
                    <h2>{{ pagesetting('event_heading') }}</h2>
                    <p>{!! pagesetting('event_description') !!}</p>
                </div>
                <x-event :count="pagesetting('event_count')"> </x-event>
            </div>
        </div>
    </div>
</section>
