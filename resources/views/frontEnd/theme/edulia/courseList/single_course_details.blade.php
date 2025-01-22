@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
    {{ headerContent() }}
    <section class="bradcrumb_area" style="background-image:url('../img/client/common-banner1.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.course_details') }} <span><a href="{{ url('/') }}">{{ __('edulia.home') }}</a>
                                / {{ __('edulia.course_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding blog">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="course_details">

                        <div class="course_details_preview_img mt-0">
                            <img src="{{ asset($singleCourseDetail->image) }}" alt="">
                        </div>
                        <nav class="course_details_menu">
                            <ul>
                                <li class='course_details_menu_list'><a href="#"
                                        class='course_details_menu_list_link active about-filter'
                                        data-name='overview'>@lang('edulia.overview')</a></li>
                                <li class='course_details_menu_list'><a href="#"
                                        class='course_details_menu_list_link about-filter'
                                        data-name='outline'>@lang('edulia.outline')</a></li>
                                <li class='course_details_menu_list'><a href="#"
                                        class='course_details_menu_list_link about-filter'
                                        data-name='prerequisites'>@lang('edulia.prerequisites')</a></li>
                                <li class='course_details_menu_list'><a href="#"
                                        class='course_details_menu_list_link about-filter'
                                        data-name='resources'>@lang('edulia.resources')</a></li>
                                <li class='course_details_menu_list'><a href="#"
                                        class='course_details_menu_list_link about-filter'
                                        data-name='stats'>@lang('edulia.stats')</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="course_details_abouts">
                            <div class="course_details_abouts_item overview">
                                {!! $singleCourseDetail->overview !!}
                            </div>
                            <div class="course_details_abouts_item outline">
                                {!! $singleCourseDetail->outline !!}
                            </div>
                            <div class="course_details_abouts_item prerequisites">
                                {!! $singleCourseDetail->prerequisites !!}
                            </div>
                            <div class="course_details_abouts_item resources">
                                {!! $singleCourseDetail->resources !!}
                            </div>
                            <div class="course_details_abouts_item stats">
                                {!! $singleCourseDetail->stats !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{ footerContent() }}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        let filterBtn = document.querySelectorAll('.about-filter');
        let aboutInfo = document.querySelectorAll('.course_details_abouts_item');
        filterBtn.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let value = e.target.dataset.name;
                aboutInfo.forEach(function(item) {
                    if (item.classList.contains(value)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                })
            })
        })
    </script>
    <script>
        $(document).on('click', '.about-filter', function() {
            $('.about-filter').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endpushonce
