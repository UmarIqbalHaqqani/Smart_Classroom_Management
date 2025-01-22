@pushonce(config('pagebuilder.site_style_var'))
    <style>
        @media print {

            header,
            footer,
            .bradcrumb_area,
            .mark_sheet_print_btn,
            .backtop {
                display: none !important;
            }

            @page {
                margin: 0 !important;
                size: A4 portrait;
            }

            .section_padding {
                padding: 30px 0;
            }

            table:not(.gpa-table) tr td,
            table:not(.gpa-table) tr th {
                padding: 0px 4px !important;
            }

            .result_info_qr_code {
                height: 100px !important;
                width: 100px !important;
            }
        }
    </style>
@endpushonce

@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
{{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.individual_result') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.individual_result') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container section_padding px-3 px-sm-0">

        <div class="mark_sheet_print_btn mb-4 d-flex justify-content-end">
            <a href="#" id="printMarksheet"><i class="fas fa-print"></i> @lang('edulia.print')</a>
        </div>

        <div class="marksheet_container" id="print_sheet">
            <div class="institute_info text-center mb-2">
                <h3 class="institute_name text-uppercase mb-0">
                    {{ isset(generalSetting()->school_name) ? generalSetting()->school_name : 'Infix School Management ERP' }}
                </h3>
                <p class="institute_address">
                    {{ isset(generalSetting()->address) ? generalSetting()->address : 'Infix School Address' }}</p>
                <p class="institute_address" style="font-size: 16px;">
                    @lang('common.email'): <span
                        class="text-lowercase">{{ isset(generalSetting()->email) ? generalSetting()->email : 'hello@aorasoft.com' }}</span>,
                    @lang('common.phone'):
                    {{ isset(generalSetting()->phone) ? generalSetting()->phone : '+96897002784' }}
                </p>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-4 order-3 order-md-1 text-center text-md-start">
                    <img src="{{ file_exists(@$studentDetails->studentDetail->student_photo) ? asset($studentDetails->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                        class="student_photo" alt="">
                </div>
                <div
                    class="col-sm-12 col-md-4 d-flex align-items-center justify-content-center flex-column marksheet_header order-1 order-md-2">
                    <img src="{{ asset(generalSetting()->logo) }}" alt="{{ generalSetting()->school_name }}"
                        class="institute_logo">
                    <h4 class="marksheet_title text-uppercase">
                        @lang('edulia.progress_report')
                    </h4>
                </div>
                <div class="col-sm-12 col-md-4 d-flex justify-content-end order-2">
                    @php
                        $generalsettingsResultType = generalSetting()->result_type;
                    @endphp
                    @if (@$grades)
                        <table class="table-bordered gpa-table mx-auto mx-md-0 my-4 my-md-0 order-md-3"
                            id="grade_table">
                            <thead>
                                <tr>
                                    <th>@lang('edulia.starting')
                                    </th>
                                    <th>@lang('edulia.ending')
                                    </th>
                                    @if (@$generalsettingsResultType != 'mark')
                                        <th>@lang('exam.gpa')
                                        </th>
                                        <th>@lang('exam.grade')
                                        </th>
                                    @endif
                                    <th>@lang('homework.evalution')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grades as $grade_d)
                                    <tr>
                                        <td>{{ $grade_d->percent_from }}
                                        </td>
                                        <td>{{ $grade_d->percent_upto }}
                                        </td>
                                        @if (@$generalsettingsResultType != 'mark')
                                            <td>{{ $grade_d->gpa }}
                                            </td>
                                            <td>{{ $grade_d->grade_name }}
                                            </td>
                                        @endif
                                        <td
                                            class="text-left">
                                            {{ $grade_d->description }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="row my-3 student_info_section">
                <div class="col-md-7 col-lg-8">
                    <ul>
                        <li><span class="student_info_type">@lang('edulia.students_name')</span><span class="info_separetor">:</span><b
                                class="text-uppercase">{{ $student_detail->studentDetail->full_name }}</b></li>
                        <li><span class="student_info_type">@lang('edulia.fathers_name')</span><span class="info_separetor">:</span><b
                                class="text-uppercase">{{ $student->parents->fathers_name }}</b></li>
                        <li><span class="student_info_type">@lang('edulia.mothers_name')</span><span class="info_separetor">:</span><b
                                class="text-uppercase">{{ $student->parents->mothers_name }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.students_id')</span><span
                                class="info_separetor">:</span><b>{{ @$student_detail->student->admission_no }}</b></li>
                        <li><span class="student_info_type">@lang('edulia.date_of_birth')</span><span
                                class="info_separetor">:</span><b>{{ $student_detail->studentDetail->date_of_birth != '' ? dateConvert($student_detail->studentDetail->date_of_birth) : '' }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.roll')</span><span
                                class="info_separetor">:</span><b>{{ $student_detail->studentDetail->roll_no }}</b>
                        </li>

                    </ul>
                </div>
                <div class="col-md-5 col-lg-4">
                    <ul>
                        <li><span class="student_info_type">@lang('edulia.class')</span><span
                                class="info_separetor">:</span><b>{{ $student_detail->class->class_name }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.section')</span><span
                                class="info_separetor">:</span><b>{{ $student_detail->section->section_name }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.group')</span><span class="info_separetor">:</span><b
                                class="text-uppercase">{{ $student->student_group_id ? $student->group->group : 'N/A' }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.examination')</span><span
                                class="info_separetor">:</span><b>{{ $exam_details->title }}</b></li>
                        <li><span class="student_info_type">@lang('edulia.year')</span><span
                                class="info_separetor">:</span><b>{{ @$student_detail->academic->year }}</b>
                        </li>
                        <li><span class="student_info_type">@lang('edulia.publish_date')</span><span
                                class="info_separetor">:</span><b>{{ dateConvert($exam_content->publish_date) }}</b></li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="w-100 table-bordered marksheet_table">
                    <thead>
                        <tr>
                            <th style="width:200px">@lang('edulia.subject')</th>
                            <th>@lang('edulia.full_marks')</th>
                            <th>@lang('edulia.highest_marks')</th>
                            <th>@lang('edulia.obtained_marks')</th>
                            @if (@$generalsettingsResultType != 'mark')
                                <th>@lang('edulia.grade_point')</th>
                                <th>@lang('edulia.letter_grade')</th>
                            @endif
                            <th>@lang('edulia.remarks')</th>
                            @if (@$generalsettingsResultType == 'mark')
                                <th>@lang('homework.evaluation')</th>
                                <th>@lang('exam.pass_fail')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_total_obtained_mark = 0;
                            $total_total_full_mark = 0;

                            $optional_countable_gpa = 0;
                            $main_subject_total_gpa = 0;
                            $Optional_subject_count = 0;
                            if ($optional_subject != '') {
                                $Optional_subject_count = $subjects->count() - 1;
                            } else {
                                $Optional_subject_count = $subjects->count();
                            }
                            $sum_gpa = 0;
                            $resultCount = 1;
                            $subject_count = 1;
                            $tota_grade_point = 0;
                            $this_student_failed = 0;
                            $count = 1;
                            $total_mark = 0;
                            $total_full_mark = 0;
                            $total_obtained_mark = 0;
                            $temp_grade = [];
                            $average_passing_mark = averagePassingMark($exam_type_id);
                        @endphp
                        @foreach ($mark_sheet as $data)
                            @php
                                $temp_grade[] = $data->total_gpa_grade;
                                if ($data->subject_id == $optional_subject) {
                                    continue;
                                }
                            @endphp
                            <tr>
                                <td>
                                    {{ $data->subject->subject_name }}
                                </td>
                                <td>
                                    @if (@$generalsettingsResultType == 'mark')
                                        {{ subject100PercentMark() }}
                                    @else

                                        {{ $total_full_mark = @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id) }}
                                         @php $total_total_full_mark += $total_full_mark @endphp
                                    @endif
                                </td>
                                <td>
                                    @if (@$generalsettingsResultType == 'mark')
                                        {{ subjectPercentageMark(@subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id), @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)) }}
                                    @else
                                        {{ @subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id) }}
                                    @endif
                                </td>
                                <td>
                                    @if (@$generalsettingsResultType == 'mark')
                                        {{ @singleSubjectMark($data->student_record_id, $data->subject_id, $data->exam_type_id)[0] }}
                                    @else
                                        {{ $total_obtained_mark = @$data->total_marks }}
                                        @php $total_total_obtained_mark += @$total_obtained_mark @endphp
                                    @endif
                                    @php
                                        if (@$generalsettingsResultType == 'mark') {
                                            $total_mark += subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                        } else {
                                            $total_mark += @$data->total_marks;
                                        }
                                    @endphp
                                </td>
                                @if (@$generalsettingsResultType != 'mark')
                                    @php
                                        $result = markGpa(@subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                        $main_subject_total_gpa += $result->gpa;
                                    @endphp
                                    <td>
                                        {{ @$result->gpa }}
                                    </td>
                                    <td>
                                        {{ @$data->total_gpa_grade }}
                                    </td>
                                @endif
                                <td>
                                    {{ @$data->teacher_remarks }}
                                </td>
                                @if (@$generalsettingsResultType == 'mark')
                                    <td>
                                        @php
                                            $evaluation = markGpa(subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                        @endphp
                                        {{ @$evaluation->description }}
                                    </td>
                                    <td>
                                        @php
                                            $totalMark = subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                            $passMark = $data->subject->pass_mark;
                                        @endphp
                                        @if ($passMark <= $totalMark)
                                            @lang('exam.pass')
                                        @else
                                            @lang('exam.fail')
                                        @endif
                                    </td>
                                @endif
                                @php
                                    $count++;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @php
                            $final_result = ($main_subject_total_gpa + $optional_countable_gpa) / $Optional_subject_count;
                            if ($final_result >= $maxGrade) {
                                $gpa = number_format($maxGrade, 2, '.', '');
                            } else {
                                $gpa = number_format($final_result, 2, '.', '');
                            }
                            if (in_array($failgpaname->grade_name, $temp_grade)) {
                                $failgpa = $failgpaname->grade_name;
                            } else {
                                if ($final_result >= $maxGrade) {
                                    $grade_details = App\SmResultStore::remarks($maxGrade);
                                } else {
                                    $grade_details = App\SmResultStore::remarks($final_result);
                                }
                            }
                        @endphp
                        <tr class="total">
                            <td>@lang('edulia.total')</td>
                            <td>{{ $total_total_full_mark }}</td>
                            <td></td>
                            <td>{{ $total_total_obtained_mark }}</td>
                            <td></td>
                            <td>{{ @$grade_details->grade_name }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table-bordered w-100 final_result">
                            <tbody>
                                <tr>
                                    <td width="15%">@lang('edulia.gpa')</td>
                                    <td width="5%">{{ $gpa }}</td>
                                    <td width="15%">@lang('edulia.grade')</td>
                                    <td width="5%">{{ @$grade_details->grade_name }}</td>
                                    <td width="15%">@lang('edulia.status')</td>
                                    <td width="15%">{{ @$grade_details->description }}</td>
                                    <td width="15%">@lang('edulia.attendance')</td>
                                    @if (isset($exam_content))
                                        <td width="15%">
                                            {{ @$student_attendance }}
                                            @lang('edulia.of')
                                            {{ @$total_class_days }}
                                        </td>
                                    @else
                                        <td width="15%">
                                            @lang('edulia.no_data_found')
                                        </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="remarks mt-3">
                        @if (@$grade_details->grade_name == 'F')
                            @lang('edulia.you_have_failed')
                        @else
                            @lang('edulia.you_have_passed')
                        @endif
                    </div>
                </div>
            </div>
            @if (isset($exam_content))
                <div class="row signature_container">
                    <div class="col-lg-12 col-md-12">
                        <div class="row align-items-end">
                            <div class="col-sm-4">
                                <div class="signature_area">@lang('edulia.guardian')</div>
                            </div>

                            <div class="col-sm-4">
                                <div class="signature_area">@lang('edulia.class_teacher')</div>
                            </div>

                            <div class="col-sm-4 text-center">
                                <div class="soft_signature_area w-75">
                                    <img class="mb-2" src="{{ asset($exam_content->file) }}" width="100px"
                                        alt="">
                                </div>
                                <div class="signature_area">{{ @$exam_content->title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <footer>
        {{footerContent()}}
    </footer>
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#printMarksheet").on("click", function(e) {
            e.preventDefault();
            window.print();
        })
    </script>
@endpushonce

