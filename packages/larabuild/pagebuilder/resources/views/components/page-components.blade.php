@if(!empty($page->settings['grids']))

    @foreach ($page->settings['grids'] as $grid)
        @php
            $columns = getColumnInfo($grid['grid']);
            setGridId($grid['grid_id']);
            $css = getCss();
            if (!empty(getBgOverlay())) {
                $css = 'position:relative;' . $css;
            }

            $x_components = ['header-breadcumb', 'home-slider', 'counter', 'event', 'news-area', 'event-gallery', 'app-banner', 'news-section'];
            $non_container = ['opening-hour','contact-form','google-map','speech','cta','faqs'];

            $isNonContainer = false;
            
            if (isset($grid['data'])) {
                foreach ($grid['data'] as $dataColumn) {
                    foreach ($dataColumn as $component) {
                        if (in_array($component['section_id'], $non_container)) {
                            $isNonContainer = true;
                            break 2;
                        }
                    }
                }
            }
        @endphp

        <section class="pb-themesection {{ getClasses() }}" {!! getCustomAttributes() !!} {!! !empty($css) ? 'style="' . $css . '"' : '' !!}>
            {!! getBgOverlay() !!}
            
            @if ($isNonContainer)
                <div {!! getContainerStyles() !!}>
            @endif

            <div class="{{ $isNonContainer ? 'full-width' : 'container-fluid' }}">
                <div class="row droppable-container" data-id="{{ $grid['grid_id'] }}">
                    @foreach ($columns as $columnIndex => $columnClass)
                        <div class="{{ $columnClass }} p-0">
                            @foreach ($grid['data'][$columnIndex] ?? [] as $component)
                                @php
                                    setSectionId($component['id']);
                                    $isXComponent = in_array($component['section_id'], $x_components);
                                @endphp

                                <div class="{{ $isXComponent ? 'full-width' : '' }}" >
                                    @if (view()->exists('themes.' . activeTheme() . '.pagebuilder.' . $component['section_id'] . '.view'))
                                        {!! view('themes.' . activeTheme() . '.pagebuilder.' . $component['section_id'] . '.view')->render() !!}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($isNonContainer)
                </div>
            @endif
        </section>
    @endforeach
@endif