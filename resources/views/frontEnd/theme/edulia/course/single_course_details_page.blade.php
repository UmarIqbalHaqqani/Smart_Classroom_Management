@extends(config('pagebuilder.site_layout'),['edit' => false ])
@section(config('pagebuilder.site_section'))
<style>
    .list-unstyled li {
        font-size: 1.1rem;
        color: #6c757d;
    }
    .list-unstyled li i {
        color: #28a745;
        margin-right: 10px;
    }
    .list-unstyled li.mb-2 {
        margin-bottom: 1rem;
    }
</style>

{{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{__('edulia.course_details')}}<span><a href="{{url('/')}}">{{__('edulia.home')}}</a> / {{__('edulia.courses_details')}}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding course course_details_page mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="course_sidebar">
                        <div class="course_sidebar_thumbnail">
                            <img src="{{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}" alt="{{$course->title}}">
                        </div>
                        @if ($course->courseCategory->category_name)
                            <div class="course_sidebar_content">
                                <h5>{{ __('edulia.category').' '.':'}}{{$course->courseCategory->category_name}}</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-7 col-lg-8 col-md-12">
                    <div class="course_details">
                        <div class="course_details_mentor">
                            <div class="course_details_mentor_head">
                                <div class="course_details_mentor_title">
                                    <h5>{{$course->title}}</h5>
                                </div>
                            </div>
                            <div class="course_details_mentor_wrapper">
                                {!! $course->overview !!}
                            </div>
                        </div>
                        <div class="course_details_preview_img">
                            <img src="{{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}" alt="{{$course->title}}">
                        </div>
                        <nav class="course_details_menu">
                            <ul>
                                @if ($course->outline)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link active about-filter' data-name='overview'>
                                            {{ __('edulia.overview') }}
                                        </a>
                                    </li>
                                @endif
                                @if ($course->prerequisites)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='curriculum'>
                                            {{ __('edulia.curriculum') }} 
                                        </a>
                                    </li>
                                @endif
                                @if ($course->resources)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='instructors'>
                                            {{ __('edulia.instructors') }}
                                        </a>
                                    </li>
                                @endif
                                @if ($course->stats)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='reviews'>
                                            {{ __('edulia.reviews') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <div class="course_details_abouts">
                            <div class="course_details_abouts_item overview">
                                {!! $course->outline !!}
                            </div>
                            <div class="course_details_abouts_item curriculum">  
                                {!! $course->prerequisites !!}
                            </div>
                            <div class="course_details_abouts_item instructors">
                                {!! $course->resources !!}
                            </div>
                            <div class="course_details_abouts_item reviews">
                                {!! $course->stats !!}
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
        const filterBtns = document.querySelectorAll('.about-filter');
        const aboutItems = document.querySelectorAll('.course_details_abouts_item');

        filterBtns.forEach((btn) => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                filterBtns.forEach((btn) => btn.classList.remove('active'));

                this.classList.add('active');

                const value = this.dataset.name;

                aboutItems.forEach((item) => {
                    if (item.classList.contains(value)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        document.querySelector('.course_details_abouts_item.overview').style.display = 'block';
        document.querySelector('.about-filter[data-name="overview"]').classList.add('active');
    </script>
@endpushonce
