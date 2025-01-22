<section class="section_padding_bottom index noticeboard container px-12">
    <div class="row">
        <div class="col-md-12">
            <div class="openning_hours_container">
                <div class="section_title">
                    <h2>{{ pagesetting('opening_hour_heading') }}</h2>
                    <p>{!! pagesetting('opening_hour_description') !!}</p>
                </div>
                <div class="noticeboard_inner">
                    @if (!empty(pagesetting('opening_hour_items')))
                        @foreach (pagesetting('opening_hour_items') as $item)
                            <ul class='noticeboard_inner_weekinfo'>
                                <li><span>{{ $item['opening_hour_item_day'] }}<label>:</label></span>
                                    <span>{{ $item['opening_hour_start'] }} {{gv($item, 'opening_hour_end') ? '- '.gv($item, 'opening_hour_end') : ''}}</span>
                                </li>
                            </ul>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
