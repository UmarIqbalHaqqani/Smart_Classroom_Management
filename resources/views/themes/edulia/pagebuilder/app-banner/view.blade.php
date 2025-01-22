<div class="download_app_section">
    <div class="row">
        @if (!empty(pagesetting('app_banner_image')))
            <div class="col-xxl-4 col-4 col-sm-5 download_app_container">
                <img src="{{ pagesetting('app_banner_image')[0]['thumbnail'] }}" class="download_app_image"
                    alt="{{ __('edulia.Image') }}">
            </div>
        @endif
        <div class="col-xxl-8 col-8 col-sm-7 download_links_container">
            <div class="app_download_title">{{ pagesetting('app_banner_heading') }}</div>
            <div class="feature_list">
                @if (!empty(pagesetting('app_banner_items')))
                    <ul class="d-flex flex-wrap">
                        @foreach (pagesetting('app_banner_items') as $item)
                            <li>
                                @if (!empty($item['item_icon']))
                                    <img src="{{ $item['item_icon'][0]['thumbnail'] }}"
                                        alt="{{ __('edulia.Image') }}">
                                @endif
                                {{ $item['item_heading'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @if (!empty(pagesetting('app_download_items')))
                <div class="download_links d-flex flex-wrap row_gap">
                    @foreach (pagesetting('app_download_items') as $item)
                        <a href="{{ $item['item_link'] }}" target="_blank">
                            @if (!empty($item['item_image']))
                                <img src="{{ $item['item_image'][0]['thumbnail'] }}" alt="{{ __('edulia.Image') }}">
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
