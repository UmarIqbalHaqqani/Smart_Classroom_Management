@extends('backEnd.master')
@section('title')
@lang('reports.merit_list_report')
@endsection
@section('mainContent')
<style>
    tr {
        border: 1px solid var(--border_color);
    }

    table.meritList {
        width: 100%;
        border: 1px solid var(--border_color);
    }

    table.meritList th {
        padding: 2px;
        text-transform: capitalize !important;
        font-size: 11px !important;
        text-align: center !important;
        border: 1px solid var(--border_color);
        text-align: center;

    }

    table.meritList td {
        padding: 2px;
        font-size: 11px !important;
        border: 1px solid var(--border_color);
        text-align: center !important;
    }

    .single-report-admit table tr td {
        padding: 5px 5px !important;

        border: 1px solid var(--border_color);
    }

    .single-report-admit table tr th {
        padding: 5px 5px !important;
        vertical-align: middle;
        border: 1px solid var(--border_color);
    }

    .main-wrapper {
        display: block !important;
    }

    #main-content {
        width: auto !important;
    }

    hr {
        margin: 0px;
    }

    .gradeChart tbody td {
        padding: 0;
        border: 1px solid var(--border_color);
    }

    table.gradeChart {
        padding: 0px;
        margin: 0px;
        width: 60%;
        text-align: right;
    }

    table.gradeChart thead th {
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }

    table.gradeChart tbody td {
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }

    #grade_table th {
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }

    #grade_table td {
        color: black;
        text-align: center;
        border: 1px solid black;
    }

</style>
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.merit_list_report') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.merit_list_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-15">@lang('common.select_criteria')</h3>
                        </div>
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'route' => 'merit_list_reports', 'method' => 'POST', 'id' => 'search_student']) }}
                <div class="row">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    @if(moduleStatusCheck('University'))
                    <div class="col-lg-12">
                        <div class="row">
                            @includeIf('university::common.session_faculty_depart_academic_semester_level',
                            ['required' =>
                            ['USN', 'UD', 'UA', 'US', 'USL'],'hide'=> ['USUB']
                            ])

                            <div class="col-lg-3 mt-15" id="select_exam_typ_subject_div">
                                <label for="">@lang('exam.select_exam')</label>
                                {{ Form::select('exam_type',[""=>__('exam.select_exam').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('exam_type') ? ' is-invalid' : ''), 'id'=>'select_exam_typ_subject']) }}

                                <div class="pull-right loader loader_style" id="select_exam_type_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}"
                                        alt="loader">
                                </div>
                                @if ($errors->has('exam_type'))
                                <span class="text-danger custom-error-message" role="alert">
                                    {{ @$errors->first('exam_type') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-lg-4 mt-30-md md_mb_20">
                        <label class="primary_input_label" for="">{{ __('exam.exam') }}<span class="text-danger">
                                *</span></label>
                        <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                            name="exam">
                            <option data-display="@lang('reports.select_exam')*" value="">@lang('reports.select_exam') *
                            </option>
                            @foreach($exams as $exam)
                            <option value="{{$exam->id}}"
                                {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('exam'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('exam') }}
                        </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md md_mb_20">
                        <label class="primary_input_label" for="">{{ __('common.class') }}<span class="text-danger">
                                *</span></label>
                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                            id="select_class" name="class">
                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')
                                *</option>
                            @foreach($classes as $class)
                            <option value="{{$class->id}}"
                                {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('class'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('class') }}
                        </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">
                        <label class="primary_input_label" for="">{{ __('common.section') }}<span class="text-danger">
                                *</span></label>
                        <select
                            class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                            id="select_section" name="section">
                            <option data-display="@lang('common.select_section')*" value="">
                                @lang('common.select_section') *</option>
                        </select>
                        <div class="pull-right loader loader_style" id="select_section_loader">
                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}"
                                alt="loader">
                        </div>
                        @if ($errors->has('section'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('section') }}
                        </span>
                        @endif
                    </div>
                    @endif
                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search pr-2"></span>
                            @lang('common.search')
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@if(isset($allresult_data))
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row justify-content-end">
            <div class="col-lg-8 pull-right mt-2 pt-2">
                <a href="{{route('merit-list/print', [$InputExamId, $InputClassId, $InputSectionId])}}"
                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                    @lang('reports.print')</a>
            </div>
        </div>
        <div class="white-box mt-20">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-15 mt-0">@lang('reports.merit_list_report')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="single-report-admit">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex">
                                                <div class="offset-2">
                                                </div>
                                                <div class="col-lg-2">
                                                    <img class="logo-img" src="{{ generalSetting()->logo }}"
                                                        alt="{{generalSetting()->school_name}}">
                                                </div>
                                                <div class="col-lg-6 ml-30">
                                                    <h3 class="text-white">
                                                        {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}
                                                    </h3>
                                                    <p class="text-white mb-0">
                                                        {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}
                                                    </p>
                                                    <p class="text-white mb-0">@lang('common.email'):
                                                        {{isset(generalSetting()->email)?generalSetting()->email:'hello@aorasoft.com'}},
                                                        @lang('common.phone'):
                                                        {{isset(generalSetting()->phone)?generalSetting()->phone:'+96897002784'}}
                                                    </p>
                                                </div>
                                                <div class="offset-2"></div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    {{-- start col-lg-8 --}}
                                                    <div class="col-lg-8">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h3>@lang('reports.order_of_merit_list')</h3>
                                                                <p class="mb-0">
                                                                    @lang('reports.academic_year') : <span
                                                                        class="primary-color fw-500">{{@generalSetting()->academic_Year->year}}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('reports.exam') : <span
                                                                        class="primary-color fw-500">{{$exam_name}}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('common.class') : <span
                                                                        class="primary-color fw-500">{{$class_name}}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('common.section') : <span
                                                                        class="primary-color fw-500">{{$section->section_name}}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h3>@lang('common.subjects')</h3>
                                                                @foreach($assign_subjects as $subject)
                                                                <p class="mb-0">
                                                                    <span
                                                                        class="primary-color fw-500">{{$subject->subject->subject_name}}</span>
                                                                </p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end col-lg-8 --}}
                                                    {{-- sm_marks_grades --}}
                                                    <div class="col-lg-4 text-black">
    
                                                    </div>
                                                    {{--end sm_marks_grades --}}
                                                </div>
                                            </div>
                                            <h3 class="primary-color fw-500 text-center">
                                                @lang('reports.order_of_merit_list')</h3>
                                            {{-- Mark Distributation Table Start --}}
                                            <div class="table-responsive">
                                                <table class="w-100 mt-30 mb-20 table table-bordered meritList">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('common.name')</th>
                                                            <th>@lang('student.admission_no')</th>
                                                            <th>@lang('student.roll_no')</th>
                                                            <th>@lang('reports.position')</th>
                                                            <th>@lang('exam.total_mark')</th>
                                                            <th>@lang('reports.gpa_without_additional')</th>
                                                            <th>@lang('exam.gpa')</th>
                                                            @foreach($subjectlist as $subject)
                                                            <th>{{$subject}}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php 
                                                            $i = 1; 
                                                        @endphp
                                                    
                                                        @foreach($allresult_data as $key => $row)
                                                            @php
                                                                $student_detail = App\SmStudent::where('id', $row->student_id)->first();
                                                                $optional_subject = App\SmOptionalSubjectAssign::where('student_id', $student_detail->id)
                                                                    ->where('session_id', $student_detail->session_id)
                                                                    ->where('academic_id', getAcademicId())
                                                                    ->first();
                                                                $optional_subject_id = $optional_subject ? $optional_subject->subject_id : null;
                                                    
                                                                $markslist = explode(',', $row->marks_string);
                                                                $get_subject_id = explode(',', $row->subjects_id_string);
                                                                $count = 0;
                                                                $additioncheck = [];
                                                                $subject_mark = [];
                                                                $total_grade_point = 0;
                                                                $total_grade_point_without_optional = 0;
                                                                $number_of_subjects = count($markslist);
                                                                $number_of_subjects_without_optional = 0;
                                                                $current_total_marks = 0;  // NEW variable to store current student's total marks
                                                            @endphp
                                                    
                                                            @foreach($markslist as $mark)
                                                                @php
                                                                    $is_optional = App\SmOptionalSubjectAssign::is_optional_subject($row->student_id, $get_subject_id[$count]);
                                                                    $grade_gpa = DB::table('sm_marks_grades')
                                                                        ->where('percent_from', '<=', $mark)
                                                                        ->where('percent_upto', '>=', $mark)
                                                                        ->where('academic_id', getAcademicId())
                                                                        ->first();
                                                    
                                                                    $total_grade_point += @$grade_gpa->gpa;
                                                    
                                                                    if (!$is_optional) {
                                                                        $total_grade_point_without_optional += @$grade_gpa->gpa;
                                                                        $number_of_subjects_without_optional++;
                                                                    } else {
                                                                        $additioncheck[] = $mark;
                                                                    }
                                                    
                                                                    $current_total_marks += $mark;
                                                    
                                                                    $count++;
                                                                    $subject_mark[] = $mark;
                                                                @endphp
                                                            @endforeach
                                                    
                                                            @php
                                                                $gpa = ($number_of_subjects_without_optional != 0) ? number_format((float)$total_grade_point / $number_of_subjects_without_optional, 2, '.', '') : 0;
                                                                if ($gpa > 5) {
                                                                    $gpa = 5.00;
                                                                }
                                                                $gpa_without_optional = $number_of_subjects_without_optional ? number_format((float)$total_grade_point / $number_of_subjects, 2, '.', '') : $failgpa;
                                                            @endphp
                                                    
                                                            <tr>
                                                                <td>{{ $row->student_name }}</td>
                                                                <td>{{ $row->admission_no }}</td>
                                                                <td>{{ $row->studentinfo->roll_no }}</td>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $current_total_marks }}</td> <!-- Use current student's total marks -->
                                                                
                                                                <!-- GPA Without Optional -->
                                                                <td>{{ $gpa_without_optional }}</td>
                                                                
                                                                <!-- GPA With All Subjects -->
                                                                <td>{{ number_format($gpa, 2) }}</td>
                                                    
                                                                @foreach($markslist as $mark)
                                                                    <td>{{ !empty($mark) ? $mark : 0 }}</td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    
                                                </table>
                                            </div>
                                            {{-- Mark Distributation Table End --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif
@endsection
@if(moduleStatusCheck('University'))
@push('script')
<script>
    $( document ).ready( function () {
        $( "#select_semester_label" ).on( "change", function () {

            var url = $( "#url" ).val();
            var i = 0;
            let semester_id = $( this ).val();
            let academic_id = $( '#select_academic' ).val();
            let session_id = $( '#select_session' ).val();
            let faculty_id = $( '#select_faculty' ).val();
            let department_id = $( '#select_dept' ).val();
            let un_semester_label_id = $( '#select_semester_label' ).val();

            if ( session_id == '' ) {
                setTimeout( function () {
                    toastr.error(
                        "Session Not Found",
                        "Error ", {
                            timeOut: 5000,
                        } );
                }, 500 );

                $( "#select_semester option:selected" ).prop( "selected", false )
                return;
            }
            if ( department_id == '' ) {
                setTimeout( function () {
                    toastr.error(
                        "Department Not Found",
                        "Error ", {
                            timeOut: 5000,
                        } );
                }, 500 );
                $( "#select_semester option:selected" ).prop( "selected", false )

                return;
            }
            if ( semester_id == '' ) {
                setTimeout( function () {
                    toastr.error(
                        "Semester Not Found",
                        "Error ", {
                            timeOut: 5000,
                        } );
                }, 500 );
                $( "#select_semester option:selected" ).prop( "selected", false )

                return;
            }
            if ( academic_id == '' ) {
                setTimeout( function () {
                    toastr.error(
                        "Academic Not Found",
                        "Error ", {
                            timeOut: 5000,
                        } );
                }, 500 );
                return;
            }
            if ( un_semester_label_id == '' ) {
                setTimeout( function () {
                    toastr.error(
                        "Semester Label Not Found",
                        "Error ", {
                            timeOut: 5000,
                        } );
                }, 500 );
                return;
            }

            var formData = {
                semester_id: semester_id,
                academic_id: academic_id,
                session_id: session_id,
                faculty_id: faculty_id,
                department_id: department_id,
                un_semester_label_id: un_semester_label_id,
            };

            // Get Student
            $.ajax( {
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/university/" + "get-university-wise-student",
                beforeSend: function () {
                    $( '#select_un_student_loader' ).addClass( 'pre_loader' ).removeClass(
                        'loader' );
                },
                success: function ( data ) {
                    var a = "";
                    $.each( data, function ( i, item ) {
                        if ( item.length ) {
                            $( "#select_un_student" ).find( "option" ).not(
                                ":first" ).remove();
                            $( "#select_un_student_div ul" ).find( "li" ).not(
                                ":first" ).remove();

                            $.each( item, function ( i, students ) {
                                console.log( students );
                                $( "#select_un_student" ).append(
                                    $( "<option>", {
                                        value: students.student.id,
                                        text: students.student
                                            .full_name,
                                    } )
                                );

                                $( "#select_un_student_div ul" ).append(
                                    "<li data-value='" +
                                    students.student.id +
                                    "' class='option'>" +
                                    students.student.full_name +
                                    "</li>"
                                );
                            } );
                        } else {
                            $( "#select_un_student_div .current" ).html(
                                "SELECT STUDENT *" );
                            $( "#select_un_student" ).find( "option" ).not(
                                ":first" ).remove();
                            $( "#select_un_student_div ul" ).find( "li" ).not(
                                ":first" ).remove();
                        }
                    } );
                },
                error: function ( data ) {
                    console.log( "Error:", data );
                },
                complete: function () {
                    i--;
                    if ( i <= 0 ) {
                        $( '#select_un_student_loader' ).removeClass( 'pre_loader' )
                            .addClass( 'loader' );
                    }
                }
            } );
        } );
    } );

</script>
@endpush
@endif
