<div class="col-lg-12 text-{{ pagesetting('icon_alignment') }} display-6">
    <nav>
        <ul>
            <li>
                @if (!empty(pagesetting('icon_items')))
                    @foreach (pagesetting('icon_items') as $item)
                        @php
                            $size = '';
                            if ($item['icon_size'] == 5) {
                                $size = '25px';
                            } elseif ($item['icon_size'] == 15) {
                                $size = '77px';
                            } elseif ($item['icon_size'] == 30) {
                                $size = '154px';
                            } elseif ($item['icon_size'] == 45) {
                                $size = '230px';
                            } else {
                                $size = '307px';
                            }
                        @endphp
                        @if (!empty($item['icon_class']))
                            <a href="{{ $item['icon_link'] }}"
                                target='{{ $item['icon_link_option'] }}'>
                                <i style="font-size: {{ $size }}" class="{{ $item['icon_class'] }}"></i>
                            </a>
                        @endif
                        @if (!empty($item['icon_svg']))
                            <a href="{{ $item['icon_link'] }}"
                                target='{{ $item['icon_link_option'] }}'>
                                <img style="max-width: {{ $item['icon_size'] }}%;"
                                    src="{{ $item['icon_svg'][0]['thumbnail'] }}"
                                    alt="{{ __('edulia.SVG') }}">
                            </a>
                        @endif
                    @endforeach
                @endif
            </li>
        </ul>
    </nav>
</div>
