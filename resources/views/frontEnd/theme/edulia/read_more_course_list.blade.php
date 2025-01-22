<div class="row">
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
            @if ($column == '12' ) col-md-12
            @elseif ($column == '6') col-md-12
            @elseif ($column == '4') col-md-6 col-sm-12
            @elseif ($column == '3') col-md-4 col-sm-6
            @elseif ($column == '2') col-md-3 col-sm-4 col-6
            @elseif ($column == '1') col-md-2 col-sm-3 col-6
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
</div>