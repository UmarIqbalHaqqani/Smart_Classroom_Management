@extends('backEnd.master')
@section('title')
@lang('reports.online_exam_report')
@endsection
@section('mainContent')
<input type="text" hidden value="{{ @$clas->class_name }}" id="cls">
<input type="text" hidden value="{{ @$sec->section_name }}" id="sec">
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.online_exam_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.online_exam_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-12">
           
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('common.select_criteria') </h3>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'online_exam_reports', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            @if(moduleStatusCheck('University'))
                                @includeIf(
                                    'university::common.session_faculty_depart_academic_semester_level',
                                    ['mt' => 'mt-30', 'hide' => ['USUB'], 'required' => ['USL']]
                                )
                            <div class="col-lg-3 mt-30">
                                <label class="primary_input_label" for="">{{ __('exam.exam') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('reports.select_exam') *" value="">@lang('reports.select_exam') *</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('exam') }}
                                </span>
                                @endif
                            </div>
                            @else
                            <div class="col-lg-4 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="">{{ __('exam.exam') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('reports.select_exam') *" value="">@lang('reports.select_exam') *</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('exam') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="">{{ __('common.class') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">
                                <label class="primary_input_label" for="">{{ __('common.section') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                    @if(isset($class_id))
                                    @foreach ($class->classSection ? $class->classSection  : []  as $section)
                                    <option value="{{ $section->sectionName->id }}" {{ old('section')==$section->sectionName->id ? 'selected' : '' }} >
                                        {{ $section->sectionName->section_name }}</option>
                                    @endforeach
                                @endif
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
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

@if(isset($online_exam_question))
<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="white-box mt-40">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('reports.result_view')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                 
                    <div class="table-responsive">
                    <table id="table_id_tt" class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('student.admission_no')</th>
                                <th>@lang('student.student')</th>
                                @if(moduleStatusCheck('University'))
                                    <th> @lang('university::un.semester_label') (@lang('common.section'))</th>
                                @else
                                    <th>@lang('common.class_Sec')</th>
                                @endif
                                <th>@lang('exam.exam')</th>
                                <th>@lang('common.subject')</th>
                                <th>@lang('exam.total_mark')</th>
                                <th>@lang('exam.obtained_marks')</th>
                                <th>@lang('reports.result')</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                                @foreach($students as $student)
                                <tr>
                                    <td>{{$student->admission_no}}</td>
                                    <td>{{$student->full_name}}</td>
                                    @if(moduleStatusCheck('University'))
                                        <td>{{ $student->studentRecord->unSemesterLabel ? $student->studentRecord->unSemesterLabel->name : '' }} {{ $student->studentRecord->section ? '(' . $student->studentRecord->section->section_name . ')' : '' }}</td>
                                    @else
                                        <td>
    
                                        @php if(!empty($student->recordClass)){ echo $student->recordClass->class->class_name; }else { echo ''; } @endphp
    
                                                    @if($section_id==null)
    
                                                        (@foreach ($student->recordClasses as $section)
                                                                {{$section->section->section_name}},
                                                        @endforeach)
                                                    @else
                                                    ({{$student->recordSection != ""? $student->recordSection->section->section_name:""}})
                                                    @endif
    
                                        </td>
                                    @endif
                                    <td>{{$online_exam_question->title}}</td>
                                    <td>{{$online_exam_question->subject !=""?$online_exam_question->subject->subject_name:""}}</td>
                                    <td>{{$total_marks}}</td>
                                    <td>
                                        @if(in_array($student->id, $present_students))
                                            @php
                                                if (moduleStatusCheck('OnlineExam')== TRUE) {
                                                    $obtained_marks = Modules\OnlineExam\Entities\InfixOnlineExam::obtainedMarks($online_exam_question->id, $student->id);
                                                } else {
                                                    $obtained_marks = App\SmOnlineExam::obtainedMarks($online_exam_question->id, $student->id);
                                                }
                                                if($obtained_marks){
                                                    if($obtained_marks->status == 1){
                                                            echo "Waiting for marks";
                                                        }else{
                                                            echo $obtained_marks->total_marks;
                                                        }
                                                }else{
                                                    echo "Waiting for marks";
                                                }
                                            @endphp
                                        @else
                                            @lang('common.absent')
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($student->id, $present_students))
                                            @php
                                                if (moduleStatusCheck('OnlineExam')== TRUE) {
                                                    $obtained_marks = Modules\OnlineExam\Entities\InfixOnlineExam::obtainedMarks($online_exam_question->id, $student->id);
                                                } else {
                                                    $obtained_marks = App\SmOnlineExam::obtainedMarks($online_exam_question->id, $student->id);
                                                }
                                                if($obtained_marks){
                                                    if($obtained_marks->status == 1){
                                                            echo "Waiting for marks";
                                                        }else{
                                                            $result = $obtained_marks->total_marks * 100 / $total_marks;
                                                            if($result >= $online_exam_question->percentage){
                                                                echo "Pass";  
                                                            }else{
                                                                echo "Fail";
                                                            }
                                                        }
                                                }else{
                                                    echo "Waiting for marks";
                                                }
                                                @endphp
                                            @else
        
                                                @lang('common.absent')
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                    </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif


            

@endsection
@include('backEnd.partials.data_table_js')