<div class="row">
    @if ($staffs->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
        <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                href="{{ route('expert-teacher') }}">@lang('edulia.expert_staff')</a></p>
    @else
        @foreach ($staffs as $key=> $staff)
            <div class="col-lg-{{ $column }}">
                <a target="_blank" href='{{ route('frontend.staff-details', $staff->staff->id) }}' class="teacher_wrapper">
                    <div class="teacher_wrapper_img">
                            @if (config('app.app_sync'))
                            <img
                                src="{{asset('public/uploads/expert_teacher/teacher-'.($key+1).'.jpg') }}"
                            alt="">
                            @else 
                            <img
                                src="{{ @$staff->staff->staff_photo ? asset(@$staff->staff->staff_photo) : asset('public/uploads/expert_teacher/teacher-1.jpg') }}"
                                alt="">
                            
                            @endif
                        </div>
                    <h4>{{ @$staff->staff->full_name }}</h4>
                    <p>{{ @$staff->staff->designations->title }}</p>
                </a>
            </div>
        @endforeach
    @endif
</div>
