@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
    <style>
        .gap-10 {
            gap: 10px;
        }
    </style>
    {{ headerContent() }}
    <section class="bradcrumb_area" style="background-image:url('../img/client/common-banner1.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.courses') }} <span><a href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.courses') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_padding blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="course_filtering_head course_list_filter_btns">
                                <ul class="nav nav-pills mb-3 gap-10" id="pills-tab" role="tablist">
                                    @foreach ($courseCategories as $key => $courseCategory)
                                        <li class="nav-item" role="presentation">
                                            <button class="filter_type {{ $key == 0 ? 'active' : '' }}"
                                                data-bs-toggle="pill"
                                                data-bs-target="#category_{{ $courseCategory->id }}" type="button"
                                                role="tab"
                                                aria-selected="true">{{ $courseCategory->category_name }}</button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($courseCategories as $key => $courseCategory)
                            <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                id="category_{{ $courseCategory->id }}" role="tabpanel">
                                <div class="row">
                                    @foreach ($courseCategory->courses as $course)
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <a href='{{ route('frontend.single-course-details', $course->id) }}'
                                                class="course course_item">
                                                <div class="course_item_img">
                                                    <div class="course_item_img_inner">
                                                        <img src="{{ asset($course->image) }}" alt="">
                                                    </div>
                                                    <span
                                                        class="course_item_img_status category blue">{{ $courseCategory->category_name }}</span>
                                                </div>
                                                <div class="course_item_inner">
                                                    <h4>{{ $course->title }}</h4>
                                                    <p class="course_brief">{{ $course->overview }}</p>
                                                    <a href="{{ route('frontend.single-course-details', $course->id) }}"
                                                        class="read_more_link">Learn More</a>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="load_more section_padding_top">
                                <a href="#" class="site_btn">Load More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ footerContent() }}
@endsection
