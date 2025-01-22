<div class="container-fluid">
    <div class="text-center">
        <a id="textForClipboard" target="_blank" class="text-break"
            href="{{ route('download-center.content-share-link', @$sharedLink->url) }}">
            {{ route('download-center.content-share-link', @$sharedLink->url) }}
        </a>
    </div>
    <div class="mt-40 d-flex justify-content-around">
        <button
            class="primary-btn fix-gr-bg copyToClipboard"
            data-clipboard-target="#textForClipboard">
            @lang('common.copy')
        </button>
    </div>
</div>
