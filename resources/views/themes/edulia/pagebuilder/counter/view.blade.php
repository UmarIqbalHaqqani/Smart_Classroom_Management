<!-- cta area start -->
<section class="section_padding_off cta_area"
    @if (!empty(pagesetting('counter_image'))) style="background-image: url('{{ pagesetting('counter_image')[0]['thumbnail'] }}')" @endif>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section_title">
                    <h2>{{ pagesetting('counter_heading') }}</h2>
                    <p>{!! pagesetting('counter_description') !!}</p>
                </div>
                <div class="cta_area_inner">
                    <a href="{{ pagesetting('view_course_button_link')}}" class="boxed_btn"><i
                            class="fa fa-plus-circle"></i>{{ pagesetting('view_course_button') }}</a>
                    <a href="{{ pagesetting('contact_us_button_link')}}" class="boxed_btn"><i
                            class="fa fa-user-plus"></i>{{ pagesetting('contact_us_button') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- cta area end -->

<!-- funfact area start -->
<section class="section_padding_off funfact">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="row text-center" id='counters'>
                    @if (!empty(pagesetting('counter_list_items')))
                        @foreach (pagesetting('counter_list_items') as $item)
                            <div class="col-md-3">
                                <div class="funfact_item">
                                    <h3><span class="counter" data-TargetNum="{{ $item['item_number'] }}"
                                            data-Speed="2000">{{ $item['item_number'] }}</span>
                                    </h3>
                                    <p>{{ $item['item_heading'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/animate-number/multi-animated-counter.min.js') }}"></script>
@endpushonce
