<style>
    .nice-select {
        margin-left: 0px !important;
    }
</style>
<input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
<form method="GET" action="">
    <div class="student_list_filters">
        <div class="row align-items-end row-gap-24">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="mb-2">@lang('edulia.academic_year')</div>
                <select id="academic_year_selector" class="w-100 p-3" name="academic_year">
                    <option data-display="@lang('edulia.select_academic_year') *" value="">@lang('edulia.select_academic_year') *</option>
                    @foreach ($academicYears as $academicYear)
                        <option @if (request('academic_year') && $academicYear->id == request('academic_year')) selected @endif value="{{ $academicYear->id }}">
                            {{ $academicYear->year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" id="class_selector_div">
                <div class="mb-2">@lang('edulia.class')</div>
                <select id="class_selector" class="w-100 p-3" name="class">
                    @if (isset($req_data['class']) && $req_data['class'])
                        <option selected value="{{ $req_data['class']->id }}">{{ $req_data['class']->class_name }}
                        </option>
                    @endif
                    <option data-display="@lang('edulia.select_class') *" value="">@lang('edulia.select_class')</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" id="section_selector_div">
                <div class="mb-2">@lang('edulia.section')</div>
                <select id="section_selector" class="w-100 p-3" name="section">
                    @if (isset($req_data['section']) && $req_data['section'])
                        <option selected value="{{ $req_data['section']->id }}">{{ $req_data['section']->section_name }}
                        </option>
                    @endif
                    <option data-display="@lang('edulia.select_section') *" value="">@lang('edulia.select_section')</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <button type="submit" class="boxed_btn search_btn"><i class="fa fa-search"></i>
                    @lang('edulia.search')</button>
            </div>
        </div>
    </div>
</form>

@if ($students->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger mt-4">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/student-admission') }}">@lang('edulia.student_list')</a></p>
@elseif(count($students) > 0)
    <div class="user_list_container student_list">
        <div class="tab-content">
            <div class="search_query">
                @lang('edulia.class'): {{ $req_data['class'] ? $req_data['class']->class_name : 'All' }},
                @lang('edulia.section'):
                {{ $req_data['section'] ? $req_data['section']->section_name : 'All' }}
            </div>

            <div class="list_view_toggler d-flex justify-content-end">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#list_view"
                            type="button" role="tab" aria-selected="true"><i class="fas fa-list"></i></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#grid_view" type="button" role="tab" aria-selected="false"><i
                                class="fas fa-th-large"></i></button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane fade show active" id="list_view" role="tabpanel">
                <div class="container px-3 px-sm-0">
                    <div class="common_data_table profile_list">
                        <table class="user_list_table table display" style="width:100%">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>@lang('edulia.sl')</th>
                                    <th>@lang('edulia.admission_id')</th>
                                    <th>@lang('edulia.roll_no')</th>
                                    <th>@lang('edulia.Image')</th>
                                    <th>@lang('edulia.name')</th>
                                    <th>@lang('edulia.class')(@lang('edulia.section'))</th>
                                    <th>@lang('edulia.guardian_name')</th>
                                    <th>@lang('edulia.blood_group')</th>
                                    <th>@lang('edulia.session')</th>
                                    <th>@lang('edulia.address')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $key => $student)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $student->admission_no }}</td>
                                        <td>{{ $student->roll_no }}</td>
                                        <td><img src="{{ file_exists($student->student_photo) ? asset($student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                                class="user_img" alt=""></td>
                                        <td><a href="{{ route('frontend.frontend-single-student-details', $student->id) }}"
                                                class="link_to_details" target="_blank">{{ $student->full_name }}</a></td>
                                        <td>{{ @$student->studentRecord->class->class_name }}({{ @$student->studentRecord->section->section_name }})
                                        </td>
                                        <td>{{ @$student->parents->guardians_name }}</td>
                                        <td class="blood_group">{{ @$student->bloodGroup->base_setup_name }}</td>
                                        <td>{{ @$student->academicYear->year }}</td>
                                        <td>{{ $student->current_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="grid_view" role="tabpanel">
                <div id="user_list" class="user_grid">
                    @foreach ($students as $student)
                        <div class="user_item">
                            <div class="d-flex single-student-info">
                                <div><a href="{{ route('frontend.frontend-single-student-details', $student->id) }}"
                                        class="link_to_details" target="_blank">
                                        <img src="{{ file_exists($student->student_photo) ? asset($student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                            class="user_photo" alt="student photo">
                                    </a>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="info">
                                        <p class="user_name">{{ $student->full_name }}</p>
                                        <div class="additional_info">
                                            <p class="user_roll"><b>@lang('edulia.roll_no'):</b> {{ $student->roll_no }}</p>
                                            <p class="user_id"><b>@lang('edulia.admission_id'):</b> {{ $student->admission_no }}
                                            </p>
                                            <p class="user_class"><b>@lang('edulia.class')(@lang('edulia.section')):</b>
                                                {{ @$student->studentRecord->class->class_name }}({{ @$student->studentRecord->section->section_name }})
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
