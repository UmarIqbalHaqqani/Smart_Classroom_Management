@php
    $count =  pagesetting('course_count');
    $column = pagesetting('course_area_column');
    $sorting = pagesetting('course_sorting');
@endphp

@if ($courses->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/course-list') }}">@lang('edulia.add_course')</a></p>
@else
    @foreach ($courses as $key => $course)
        @php
            $color = '';
            if ($key % 4 == 1) {
                $color = 'sunset-orange';
            } elseif ($key % 4 == 2) {
                $color = 'green';
            } elseif ($key % 4 == 3) {
                $color = 'blue';
            } else {
                $color = 'orange';
            }
        @endphp
        <div class="col-lg-{{ $column }}
            @if ($column == '12' )
                    col-md-12
                    @elseif ($column == '6')
                    col-md-12
                    @elseif ($column == '4')
                    col-md-6 col-sm-12
                    @elseif ($column == '3')
                    col-md-4 col-sm-6
                    @elseif ($column == '2')
                    col-md-3 col-sm-4 col-6
                    @elseif ($column == '1')
                    col-md-2 col-sm-3 col-6
                @endif
        ">
            <a href='{{ route('frontend.course-details', $course->id) }}' class="course_item">
                <div class="course_item_img">
                    <div class="course_item_img_inner">
                        <img src="{{ asset($course->image) }}" alt="{{ $course->courseCategory->category_name }}">
                    </div>
                    <span
                        class="course_item_img_status {{ $color }}">{{ $course->courseCategory->category_name ?? 'InfixEdu' }}
                    </span>
                </div>
                <div class="course_item_inner">
                    <h4>{{ $course->title }}</h4>
                </div>
            </a>
        </div>
    @endforeach

    <div id="dynamicLoadMoreData">

    </div>

    @if ((Request::is('/') || Request::is('home')) || Request::segment(1) == 'pages')

    @else
        @if (Request::is('course'))
            @if ($courseCount > $count)
                <div class="row text-center">
                    <div class="col-md-12">
                        <div class="load_more section_padding_top">
                            <a href="#" class="site_btn load_more_course_btn" data-skip="{{$count}}">{{ __('edulia.load_more') }}</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
@endif

@pushonce(config('pagebuilder.site_script_var'))
    <script>

        $(document).on('click', '.load_more_course_btn', function (e) {
            e.preventDefault();
            var skip = $(this).data('skip');
            var take = {{ $count }};
            var row_each_column = {{ $column }};
            var sorting = "{{ $sorting }}";

            $.ajax({
                url: "{{ route('frontend.load-more-course-list') }}",
                method: "POST",
                data: {
                    skip: skip,
                    row_each_column : row_each_column,
                    take : take,
                    sorting : sorting,
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        $('#dynamicLoadMoreData').append(response.html);

                        $('.load_more_course_btn').data('skip', skip + take);

                        if (!response.has_more) {
                            $('.load_more_course_btn').hide();
                        }
                    } else {
                        console.error('Failed to load more photos.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        });

    </script>
@endpushonce
