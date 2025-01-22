<section class="section_padding_off feature">
    <div class="container">
        <div class="row">

            @if (!empty(pagesetting('feature_area_items')))
                @php
                    $counts = count(pagesetting('feature_area_items'));
                    $column = 12 / $counts;
                @endphp
                @foreach (pagesetting('feature_area_items') as $item)
                    <div class="col-md-{{ $column }}">
                        <div class="feature_item">
                            <div class="feature_item_left">
                                @if (!empty(gv($item, 'item_image')[0]['thumbnail']))
                                    <div class="feature_item_icon">
                                        <img style="height: 60px; width:70px"
                                            src="{{ gv($item, 'item_image')[0]['thumbnail'] }}"
                                            alt="{{ __('edulia.Image') }}">
                                    </div>
                                @endif
                            </div>
                            <div class="feature_item_right">
                                <div class="feature_item_inner">
                                    <h4>{{ $item['item_heading'] }}</h4>
                                    <p>{!! $item['item_description'] !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
