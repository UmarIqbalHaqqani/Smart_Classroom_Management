@extends('backEnd.master')
@section('title')
    @lang('student.my_profile')
@endsection
@push('css')
    <style>
        table#table_id thead tr th:not(:first-child) {
            padding-left: 30px !important;
        }

        table#table_id tbody tr td:not(:first-child),
        table#table_id tbody tr td:nth-child(2) {
            padding-left: 30px !important;
        }

        .leave_table {
            overflow: hidden;
        }

        .table tbody tr:last-child td,
        .table tbody tr:last-child th {
            border-bottom: none;
        }

        .QA_section .QA_table .table.school-table-style-parent-fees thead th,
        .QA_section .QA_table .table.school-table-style-parent-fees thead td {
            padding: 16px 30px !important;
        }

        .fc th {
            padding: 0 !important;
        }
    </style>
@endpush
@section('mainContent')
    @php
        @$setting = generalSetting();
        if (!empty(@$setting->currency_symbol)) {
            @$currency = @$setting->currency_symbol;
        } else {
            @$currency = '$';
        }
    @endphp
    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row">
                <div class="col-lg-12">
                    <!-- Start Student Meta Information -->
                    <div class="main-title">
                        <h3 class="mb-15">@lang('student.welcome_to') <strong> {{ @$student_detail->full_name }}</strong> </h3>
                    </div>
                </div>
            </div>
            <div class="row row-gap-30">
                @if (userPermission('dashboard-subject'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_subject') }}" class="d-block">
                            <div class="white-box single-summery cyan">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('common.subject')</h3>
                                        <p class="mb-0">@lang('student.total_subject')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($totalSubjects))
                                            {{ count(@$totalSubjects) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-notice') && userPermission('student_noticeboard'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_noticeboard') }}" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.notice')</h3>
                                        <p class="mb-0">@lang('student.total_notice')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($totalNotices))
                                            {{ count(@$totalNotices) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-exam'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_exam_schedule') }}" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.exam')</h3>
                                        <p class="mb-0">@lang('student.total_exam')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($exams))
                                            {{ count(@$exams) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-online-exam'))
                    <div class="col-lg-3 col-md-6">
                        @if (moduleStatusCheck('OnlineExam'))
                            <a href="{{ route('om_student_online_exam') }}" class="d-block">
                            @else
                                <a href="{{ route('student_online_exam') }}" class="d-block">
                        @endif
                        <div class="white-box single-summery blue">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>@lang('student.online_exam')</h3>
                                    <p class="mb-0">@lang('student.total_online_exam')</p>
                                </div>
                                <h1 class="gradient-color2">
                                    @if (isset($online_exams))
                                        {{ count(@$online_exams) }}
                                    @endif
                                </h1>
                            </div>
                        </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-teachers'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_teacher') }}" class="d-block">
                            <div class="white-box single-summery fuchsia">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.teachers')</h3>
                                        <p class="mb-0">@lang('student.total_teachers')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($teachers))
                                            {{ count(@$teachers) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-issued-books'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_book_issue') }}" class="d-block">
                            <div class="white-box single-summery cyan">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.issued_book')</h3>
                                        <p class="mb-0">@lang('student.total_issued_book')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($issueBooks))
                                            {{ count(@$issueBooks) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-pending-homeworks'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_homework') }}" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.pending_home_work')</h3>
                                        <p class="mb-0">@lang('student.total_pending_home_work')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($homeworkLists))
                                            {{ count(@$homeworkLists) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (userPermission('dashboard-attendance-in-current-month'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_my_attendance') }}" class="d-block">
                            <div class="white-box single-summery blue">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.attendance_in_current_month')</h3>
                                        <p class="mb-0">@lang('student.total_attendance_in_current_month')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($attendances))
                                            {{ count(@$attendances) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @php
                    $feesDue = 0;
                    $totalPoint = 0;
                    $balance_fees = 0;
                    foreach ($student_detail->studentRecords as $record) {
                        foreach ($record->feesInvoice as $key => $studentInvoice) {
                            $amount = $studentInvoice->Tamount;
                            $weaver = $studentInvoice->Tweaver;
                            $fine = $studentInvoice->Tfine;
                            $paid_amount = $studentInvoice->Tpaidamount;
                            $sub_total = $studentInvoice->Tsubtotal;
                            $feesDue += $amount + $fine - ($paid_amount + $weaver);
                        }
                        foreach ($record->directFeesInstallments as $feesInstallment) {
                            $balance_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - $feesInstallment->paid_amount;
                        }
                        foreach ($record->incidents as $incident) {
                            $totalPoint += $incident->point;
                        }
                    }
                @endphp
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route(generalSetting()->fees_status == 0 ? 'student_fees' : 'fees.student-fees-list') }}" class="d-block">
                        <div class="white-box single-summery fuchsia">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>@lang('student.fees')</h3>
                                    <p class="mb-0">@lang('student.total_due_fees')</p>
                                </div>
                                <h1 class="gradient-color2">
                                    @if(!moduleStatusCheck('University'))
                                        @if (generalSetting()->fees_status == 0)
                                            @if (directFees())
                                                {{ $currency }}{{ $balance_fees }}
                                            @else
                                                {{ $currency }}{{ $old_fees }}
                                            @endif
                                        @elseif (isset($feesDue))
                                            {{ $currency }}{{ $feesDue }}
                                        @endif
                                    @else
                                        @if (generalSetting()->fees_status == 1)
                                            {{ $currency }}{{ $feesDue }}

                                        @else 
                                            @if (isset($due_amount))
                                                {{ $currency }}{{ $due_amount }}
                                            @endif
                                        @endif
                                    @endif
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                @if (moduleStatusCheck('BehaviourRecords'))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student-profile') }}" class="d-block">
                            <div class="white-box single-summery cyan">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.behaviour_point')</h3>
                                        <p class="mb-0">@lang('student.total_behaviour_point')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($totalPoint))
                                            {{ $totalPoint }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
            </div>
            <div class="row mt-40">
                @if (userPermission('student_class_routine'))
                    @include('backEnd.studentPanel._class_routine_content', [
                        'sm_weekends' => $sm_weekends,
                        'records' => $records,
                        'routineDashboard' => $routineDashboard,
                    ])
                @endif
                @if (userPermission('student_my_attendance'))
                    @include('backEnd.studentPanel.inc._attendance_statistics')
                    @include('backEnd.studentPanel.inc._dashboard_subject_attendance_tab')
                @endif
                <div class="col-md-12 mt-40">
                    <div class="white-box">
                        @if (userPermission('fees.student-fees-list'))
                            @include('backEnd.studentPanel.inc._fees_info', ['currency' => $currency])
                        @endif
                    </div>
                </div>

                <div class="col-md-12 mt-40">
                        @if (userPermission('student_exam_schedule'))
                    <div class="container-fluid p-0">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="main-title">
                                        <h3 class="mb-15">@lang('exam.exam_routine')</h3>
                                    </div>
                                </div>
                                <div class="col-lg-12 student-details up_admin_visitor">
                                    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                                        @php
                                            $exams = [];
                                        @endphp
                                        @foreach ($records as $record)
                                            @if ($record->Exam)
                                                @foreach ($record->Exam as $key => $exam)
                                                    @php
                                                        $s = $exam->exam_type_id . $exam->class_id . $exam->section_id;
                                                    @endphp
                                                    @if (!in_array($s, $exams))
                                                        @php
                                                            array_push($exams, $s);
                                                        @endphp
                                                        <li class="nav-item">
                                                            <a class="nav-link @if ($key == 0) active @endif"
                                                                href="#tabExam{{ $key }}" role="tab"
                                                                data-toggle="tab">{{ $exam->examType->title }}
                                                                - {{ moduleStatusCheck('University') ? $record->unSemesterLabel->name : $record->class->class_name }}
                                                                ({{ $record->section->section_name }})
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach ($records as $key => $record)
                                            @if ($record->Exam)
                                                @foreach ($record->Exam as $key => $exam)
                                                    @php
                                                        $exam_routines = App\SmExamSchedule::getAllExams($exam->class_id, $exam->section_id, $exam->exam_type_id);
                                                    @endphp
                                                    <div role="tabpanel"
                                                        class="tab-pane fade  @if ($key == 0) active show @endif"
                                                        id="tabExam{{ $key }}">
                                                        <div class="container-fluid p-0">
                                                            <div class="col-lg-12 p-0">
                                                                <x-table>
                                                                    <div class="table-responsive">
                                                                        <table id="default_table" class="table"
                                                                            cellspacing="0"
                                                                            width="100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width:10%;">
                                                                                        @lang('exam.date_&_day')
                                                                                    </th>
                                                                                    <th>@lang('exam.subject')</th>
                                                                                    <th>@lang('common.class_Sec')</th>
                                                                                    <th>@lang('exam.teacher')</th>
                                                                                    <th>@lang('exam.time')</th>
                                                                                    <th>@lang('exam.duration')</th>
                                                                                    <th>@lang('exam.room')</th>
    
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($exam_routines as $date => $exam_routine)
                                                                                    <tr
                                                                                        class="{{ Carbon::parse($exam_routine->date)->format('Y-m-d') == Carbon::now()->format('Y-m-d') ? 'main-border-color' : '' }}">
                                                                                        <td>{{ dateConvert($exam_routine->date) }}
                                                                                            <br>{{ Carbon::createFromFormat('Y-m-d', $exam_routine->date)->format('l') }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <strong>
                                                                                                {{ $exam_routine->subject ? $exam_routine->subject->subject_name : '' }}
                                                                                            </strong>
                                                                                            {{ $exam_routine->subject ? '(' . $exam_routine->subject->subject_code . ')' : '' }}
                                                                                        </td>
                                                                                        <td>{{ $exam_routine->class ? $exam_routine->class->class_name : '' }}
                                                                                            {{ $exam_routine->section ? '(' . $exam_routine->section->section_name . ')' : '' }}
                                                                                        </td>
                                                                                        <td>{{ $exam_routine->teacher ? $exam_routine->teacher->full_name : '' }}
                                                                                        </td>
    
                                                                                        <td> {{ date('h:i A', strtotime(@$exam_routine->start_time)) }}
                                                                                            -
                                                                                            {{ date('h:i A', strtotime(@$exam_routine->end_time)) }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @php
                                                                                                $duration = strtotime($exam_routine->end_time) - strtotime($exam_routine->start_time);
                                                                                            @endphp
    
                                                                                            {{ timeCalculation($duration) }}
                                                                                        </td>
    
                                                                                        <td>{{ $exam_routine->classRoom ? $exam_routine->classRoom->room_no : '' }}
                                                                                        </td>
    
                                                                                    </tr>
                                                                                @endforeach
    
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </x-table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
                @if (userPermission('student_teacher'))
                    <div class="container-fluid mt-40">
                        <div class="row">
                            <div class="col-lg-12">
                                @include('backEnd.studentPanel.inc._teacher_list')
                            </div>
                        </div>
                    </div>
                @endif
                @if (userPermission('leave'))
                    <div class="container-fluid mt-40">
                        <div class="row">
                            <div class="col-lg-12">
                                @include('backEnd.studentPanel.inc._leave_type')
                            </div>
                        </div>
                    </div>
                @endif
                <div class="container-fluid mt-40">
                    <div class="row">
                        <div class="col-lg-12">
                            @include('backEnd.studentPanel.inc._complaint_list_tab')
                        </div>
                    </div>
                </div>
            </div>
            <div class="white-box mt-40">
                @if (userPermission('dashboard-calendar'))
                <div class="row">
                    <div class="col-lg-12">
                        @include('backEnd.communicate.commonAcademicCalendar')
                    </div>
                </div>
            @endif
            </div>
        </div>
    </section>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span
                            class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="There are no image" id="image" height="150" width="auto">
                    <div id="modalBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $count_event = 0;
    @$calendar_events = [];
    foreach ($holidays as $k => $holiday) {
        @$calendar_events[$k]['title'] = $holiday->holiday_title;
    
        @$calendar_events[$k]['start'] = $holiday->from_date;
    
        @$calendar_events[$k]['end'] = Carbon::parse($holiday->to_date)
            ->addDays(1)
            ->format('Y-m-d');
    
        @$calendar_events[$k]['description'] = $holiday->details;
    
        @$calendar_events[$k]['url'] = $holiday->upload_image_file;
    
        $count_event = $k;
        $count_event++;
    }
    
    foreach ($events as $k => $event) {
        @$calendar_events[$count_event]['title'] = $event->event_title;
    
        @$calendar_events[$count_event]['start'] = $event->from_date;
    
        @$calendar_events[$count_event]['end'] = Carbon::parse($event->to_date)
            ->addDays(1)
            ->format('Y-m-d');
        @$calendar_events[$count_event]['description'] = $event->event_des;
        @$calendar_events[$count_event]['url'] = $event->uplad_image_file;
        $count_event++;
    }
    ?>

@endsection
@include('backEnd.communicate.academic_calendar_css_js')
