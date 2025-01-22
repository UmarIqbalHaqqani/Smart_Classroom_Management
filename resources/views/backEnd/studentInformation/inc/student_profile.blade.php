<div class="white-box">
    <!-- Start Student Meta Information -->
@if (!isset($title))
<div class="main-title">
    <h3 class="mb-15">@lang('student.student_details')</h3>
</div>
@endif

<div class="student-meta-box">
<div class="student-meta-top"></div>
@if (is_show('photo'))
    <img class="student-meta-img img-100"
        src="{{ file_exists(@$student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
        alt="">
@endif

<div class="white-box radius-t-y-0">
    <div class="single-meta mt-50">
        <div class="d-flex justify-content-between">
            <div class="name">
                @lang('student.student_name')
            </div>
            <div class="value">
                {{ @$student_detail->first_name . ' ' . @$student_detail->last_name }}
            </div>
        </div>
    </div>
    @if (is_show('admission_number'))
        <div class="single-meta">
            <div class="d-flex justify-content-between">
                <div class="name">
                    @lang('student.admission_number')
                </div>
                <div class="value">
                    {{ @$student_detail->admission_no }}
                </div>
            </div>
        </div>
    @endif
    @if (is_show('roll_number'))
        @isset($setting)
            @if (generalSetting()->multiple_roll == 0)
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('student.roll_number')
                        </div>
                        <div class="value">
                            {{ @$student_detail->roll_no ? $student_detail->roll_no : '' }}
                        </div>
                    </div>
                </div>
            @endif
        @endisset
    @endif
    <div class="single-meta">
        <div class="d-flex justify-content-between">
            <div class="name">
                @lang('student.class')

            </div>
            <div class="value">
                @if ($student_detail->defaultClass != '')
                    {{ @$student_detail->defaultClass->class->class_name }}
                @elseif ($student_detail->studentRecord != '')
                    {{ @$student_detail->studentRecord->class->class_name }}
                @endif
            </div>
        </div>
    </div>
    
    <div class="single-meta">
        <div class="d-flex justify-content-between">
            <div class="name">

                @lang('student.section')

            </div>
            <div class="value">
                @if ($student_detail->defaultClass != '')
                    {{ @$student_detail->defaultClass->section->section_name }}
                @elseif ($student_detail->studentRecord != '')
                    {{ @$student_detail->studentRecord->section->section_name }}
                @endif
            </div>
        </div>
    </div>

    @if (is_show('gender'))
        <div class="single-meta">
            <div class="d-flex justify-content-between">
                <div class="name">
                    @lang('common.gender')
                </div>
                <div class="value">
                    {{ @$student_detail->gender != '' ? $student_detail->gender->base_setup_name : '' }}
                </div>
            </div>
        </div>
    @endif
    @if (moduleStatusCheck('BehaviourRecords'))
        <div class="single-meta">
            <div class="d-flex justify-content-between">
                <div class="name">
                    @lang('behaviourRecords.behaviour_records_point')
                </div>
                <div class="value">
                    @php
                        $totalBehaviourPoints = 0;
                        if (@$studentBehaviourRecords) {
                            foreach ($studentBehaviourRecords as $studentBehaviourRecord) {
                                $totalBehaviourPoints += $studentBehaviourRecord->point;
                            }
                        }
                    @endphp
                    {{ $totalBehaviourPoints }}
                </div>
            </div>
        </div>
        
        @if(moduleStatusCheck('QRCodeAttendance') && file_exists(public_path('qr_codes/student-'.$student_detail->id.'-qrcode.png')))
            <div class="single-meta">
                <div class="d-flex justify-content-between">
                    <div class="name">
                        @lang('qrcodeattendance::qr_code_attendance.qr_code')
                    </div>
                    <div class="value">
                        <img src="{{ asset('public/qr_codes/student-'.$student_detail->id.'-qrcode.png') }}" height="100" width="100" alt="">
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
</div>
<!-- End Student Meta Information -->
@isset($siblings)

@if (count($siblings) > 0)
    <!-- Start Siblings Meta Information -->
    <div class="main-title mt-40">
        <h3 class="mb-15">@lang('student.sibling_information') </h3>
    </div>
    @foreach ($siblings as $sibling)
        <div class="student-meta-box mb-20">
            <div class="student-meta-top siblings-meta-top"></div>
            <img class="student-meta-img img-100"
                src="{{ file_exists(@$sibling->student_photo) ? asset(@$sibling->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                alt="">
            <div class="white-box radius-t-y-0">
                <div class="single-meta mt-50">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('student.sibling_name')
                        </div>
                        <div class="value">
                            {{ isset($sibling->full_name) ? $sibling->full_name : '' }}
                        </div>
                    </div>
                </div>
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('student.admission_number')
                        </div>
                        <div class="value">
                            {{ @$sibling->admission_no }}
                        </div>
                    </div>
                </div>
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('student.roll_number')
                        </div>
                        <div class="value">
                            {{ @$sibling->roll_no }}
                        </div>
                    </div>
                </div>
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">

                            @lang('student.class')

                        </div>
                        <div class="value">
                            {{-- {{ @$sibling->class->class_name }} --}}
                            @if ($sibling->defaultClass != '')
                                {{ @$sibling->defaultClass->class->class_name }}
                            @elseif ($sibling->studentRecord != '')
                                {{ @$sibling->studentRecord->class->class_name }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">

                            @lang('student.section')

                        </div>
                        <div class="value">

                            @if ($sibling->defaultClass != '')
                                {{ @$sibling->defaultClass->section->section_name }}
                            @elseif ($sibling->studentRecord != '')
                                {{ @$sibling->studentRecord->section->section_name }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('student.gender')
                        </div>
                        <div class="value">
                            {{ $sibling->gender != '' ? $sibling->gender->base_setup_name : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- End Siblings Meta Information -->
@endif
@endisset

</div>