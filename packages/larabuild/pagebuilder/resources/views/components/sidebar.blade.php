<!-- asidebar -->
<aside class="pb-aside larabuild-pbsettings">
    <div class="pb-aside_title">
        <img src="{{asset('public/backEnd/assets/img/aorabuilder-logo.png')}}" alt="logo">
        <h6>{{ __('pagebuilder::pagebuilder.product_name')
            }}<em>{{__('pagebuilder::pagebuilder.the_page_builder')}}</em></h6>
    </div>

    <div class="pb-tabs-wrapper">
        <nav>
            <div class="nav nav-tabs pb-tabs-btn">
                <button class="pb-tab-btn active" id="elements-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#blocks-tab" aria-selected="true"><i
                        class="icon-grid"></i>{{__('pagebuilder::pagebuilder.elements')}}</button>
                <button id="settings-btn" class="pb-tab-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#section-settings-tab" aria-selected="false"><i
                        class="icon-settings"></i>{{__('pagebuilder::pagebuilder.settings')}}</button>
                <button class="pb-tab-btn" id="advanced-btn" data-bs-toggle="tab" type="button"
                    data-bs-target="#advanced-settings-tab" aria-selected="false"><i
                        class="icon-sliders"></i>{{__('pagebuilder::pagebuilder.advanced')}}</button>
            </div>
        </nav>
        <div class="tab-content pb-tabs-holder">
            <div class="pb-blocks-tab tab-pane fade show active" id="blocks-tab">
                <div id="pb-advanceaccordion" class="pb-collapse-wrapper">
                    @foreach ($componentTabs as $tab => $elements)
                    <div class="accordion pb-advanceaccordion" id="pb-advanceaccordion">
                        <div class="op-accordion-item">
                            <div class="pb-advancedtitle" data-bs-toggle="collapse"
                                data-bs-target="#{{ \Illuminate\Support\Str::slug($tab) }}-accord" aria-expanded="false">
                                <h2>{{ $tab }}</h2>
                            </div>
                            <div id="{{ \Illuminate\Support\Str::slug($tab) }}-accord" class="accordion-collapse collapse"
                                data-bs-parent="#pb-advanceaccordion">
                                <ul class="pb-elementcontent-wrapper" id="draggable-{{ \Illuminate\Support\Str::slug($tab) }}">
                                    @if(!empty($elements))
                                    @foreach ($elements as $element)
                                    @php
                                    $item = $components[$element];
                                    @endphp
                                    <li class="draggable pb-placeholder-dragonly" data-section="{{$item['settings']['id']}}"
                                        id="{{$item['settings']['id']}}">
                                        <a href="javascript:void(0)" class="pb-elementcontent">
                                            {!!$item['settings']['icon'] ?? '<i class="icon-slash"></i>'!!}
                                            <span>{{$item['settings']['name'] ??
                                                __('pagebuilder::pagebuilder.no_name')}}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="pb-sec-settings-tab tab-pane fade " id="section-settings-tab">
                <div class="pb-collapse-wrapper">
                    <form method="post" id="current-section-form">
                        <input type="hidden" name="section_id" id="current-section-id" />
                        <div id="section-settings-wrapper">
                            <div>
                                <span
                                    class="at-empty-settings">{{__('pagebuilder::pagebuilder.select_any_element')}}</span>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="pb-adv-setting-tab tab-pane fade" id="advanced-settings-tab">
                <div class="pb-collapse-wrapper">
                    <form id="current-advanced-settings-form">
                        <input type="hidden" name="grid_id" id="current-grid-id" />
                        <div id="advanced-settings-wrapper">
                            <span class="at-empty-settings">{{__('pagebuilder::pagebuilder.select_any_element')}}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-btn-wrapper">
        <a href="{{ route('pagebuilder') }}" class="pb-gobackbtn"><i
                title="{{ __('pagebuilder::pagebuilder.go_back') }}" class="icon-arrow-left"></i></a>
        <a target="_blank" href="{{URL($page->slug??'/').'?preview=yes'}}"> <i class="icon-monitor"></i>
            {{__('pagebuilder::pagebuilder.preview')}}</a>
            @if(request()->segment(1) == 'header' || request()->segment(1) == 'footer')
                <a href="{{ route('pagebuilder.frontend.reset', $page->slug) }}">
                    <i class="icon-reset"></i>
                    {{ __('Reset') }}
                </a>
            @endif
        
        <button class="pb-btn savePageData">{{__('pagebuilder::pagebuilder.save')}}<i
                class="icon-loader"></i></button>
    </div>
</aside>
<!-- asidebar -->