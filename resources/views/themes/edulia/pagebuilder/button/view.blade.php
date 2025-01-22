<div class="row">
    <div class="col-md-12 text-{{ pagesetting('button_alignment') }}">
        <div class="events_loadmore">
            @if (!empty(pagesetting('button_items')))
                @foreach (pagesetting('button_items') as $item)
                    <a id="{{ $item['button_id'] }}" href="{{ $item['button_link'] }}"
                        target="{{ $item['button_link_option'] }}"
                        class="site_btn {{ $item['button_type'] }}"
                        style="padding: {{ $item['button_size'] }}; font-size: {{ $item['button_text_size'] }};">
                        {{ $item['button_text'] }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
