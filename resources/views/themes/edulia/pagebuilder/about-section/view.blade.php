<!-- about area start -->
<section class="section_padding about_us">
    <div class="container">
        <div class="row align-items-center {{ pagesetting('alignment_left_right') }}">
            <div class="col-xxl-6 col-md-6">
                <div class="about_us_img">
                    <div class="about_us_img_flex">
                        <div class="about_us_img_flex_left">
                            @if (!empty(pagesetting('about_area_img_medium')))
                                <div class="about_us_img_item">
                                    <div class="about_area_img_medium">
                                        <img src="{{ pagesetting('about_area_img_medium')[0]['thumbnail'] }}" alt="">
                                    </div>
                                </div>
                            @endif
                            
                            @if (!empty(pagesetting('about_area_img_small')))
                                <div class="about_us_img_item">
                                    <div class="about_us_img_item_img small-img">
                                        <img src="{{ pagesetting('about_area_img_small')[0]['thumbnail'] }}" alt="">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="about_us_img_flex_right">
                            @if (!empty(pagesetting('about_area_img_large')))
                                <div class="about_us_img_item">
                                    <div class="about_us_img_item_img large-img">
                                        <img src="{{ pagesetting('about_area_img_large')[0]['thumbnail'] }}" alt="">
                                    </div>
                                </div>
                            @endif
                        </div>




                        {{-- @if (!empty(pagesetting('about_area_img_1')))
                            <div class="about_us_img_item">
                                <div class="about_us_img_item_img large-img">
                                    <img src="{{ pagesetting('about_area_img_1')[0]['thumbnail'] }}"
                                        alt="{{ __('edulia.Image') }}">
                                </div>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
            <div class="col-xxl-5 offset-xxl-1 col-md-6">
                <div class="about_us_inner">
                    <h3>{{ pagesetting('about_area_heading') }}</h3>
                    <p>{!! pagesetting('about_area_description') !!}</p>
                    @if (!empty(pagesetting('about_area_list_items')))
                        <div class="about_us_inner_list">
                            @foreach (pagesetting('about_area_list_items') as $item)
                                <div class="about_us_inner_list_item">
                                    @if (!empty($item['item_image']))
                                        <div class="about_us_inner_list_item_left">
                                            <div class="about_us_inner_list_item_icon">
                                                <img src="{{ $item['item_image'][0]['thumbnail'] }}" alt="">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="about_us_inner_list_item_right">
                                        <div class="about_us_inner_list_item_inner">
                                            <h4>{{ $item['item_heading'] }}</h4>
                                            <p>{{ $item['item_description'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- about area end -->
