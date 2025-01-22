@pushonce(config('pagebuilder.site_style_var'))
    <style>
        iframe {
            width: 100% !important;
            height: 100% !important;
        }
        .google_map{
            height: 200px;
        }
    </style>
@endpushonce
<div class="contacts_info mt-5">
    <p>{!! pagesetting('google_map_editor') !!}</p>
    <div class="google_map w-100">
        {!! pagesetting('google_map_key') !!}
    </div>
</div>
