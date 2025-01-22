@php $routine_page_title="All about Infix School management system; School management software"; @endphp
@extends('frontEnd.home.front_master')
@push('css')
    <link rel="stylesheet" href="{{ asset('public/') }}/frontend/css/new_style.css" />
    <link rel="stylesheet" href="{{ url('/public/') }}/landing/css/toastr.css">
    <link rel="stylesheet" href="{{ url('/public/') }}/backEnd/assets/vendors/static_style2.css">
    <link rel="stylesheet" href="{{ url('/public/') }}/backEnd/assets/vendors/vendors_static_style.css">
    <style>
        table.table.class_exam_routine_table tbody tr td:first-child {
            padding-left: 35px !important;
        }

        table.table.class_exam_routine_table tbody tr td:nth-child(2) {
            padding-left: 20px !important;
        }

        table.table.class_exam_routine_table tbody tr td {
            padding-left: 45px !important;
        }

        table.table.table-bordered {
            font-weight: 400;
        }
    </style>
@endpush
@section('main_content')
    <!--================ Home Banner Area =================-->
    @if ($routine_page)
        <section class="container box-1420">
            <div class="banner-area"
                style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{ $routine_page->image != '' ? $routine_page->image : '../img/client/common-banner1.jpg' }}) no-repeat center;">
                <div class="banner-inner">
                    <div class="banner-content">
                        <h2>{{ $routine_page->title }}</h2>
                        <p>{{ $routine_page->description }}</p>
                        <a class="primary-btn fix-gr-bg semi-large"
                            href="{{ $routine_page->button_url }}">{{ $routine_page->button_text }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--================ End Home Banner Area =================-->

    <!--================ Start Search Area =================-->
    <section class="fact-area section-gap">
        <div class="container">
            <form method="get" action="{{ route('class-exam-routine-search') }}">
                <div class="row align-items-center">
                    @csrf
                    <div class="col-lg-3 mt-30-md">
                        <label class="primary_input_label" for="">
                            {{ __('reports.type') }}
                            <span class="text-danger"> *</span>
                        </label>
                        <select class="primary_select form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                            name="type" onchange="hideShowExamName(this)">
                            <option data-display="@lang('reports.select_type') *" value="">
                                @lang('reports.select_type') *</option>
                            @if ($routine_page->class_routine == 'show')
                                <option data-display="@lang('reports.class_routine')" value="class">
                                    @lang('reports.class_routine')</option>
                            @endif
                            @if ($routine_page->exam_routine == 'show')
                                <option data-display="@lang('reports.exam_routine')" value="exam">
                                    @lang('reports.exam_routine')</option>
                            @endif
                        </select>
                        @if ($errors->has('type'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('type') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-lg-3 mt-30-md">
                        <label class="primary_input_label" for="">
                            {{ __('common.class') }}
                            <span class="text-danger"> *</span>
                        </label>
                        <select
                            class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                            name="class">
                            <option data-display="@lang('common.select_class') *" value="">
                                @lang('common.select_class') *
                            </option>
                            @foreach ($classes as $class)
                                <option value="{{ @$class->id }}"
                                    {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                    {{ @$class->class_name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('class'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-lg-3 mt-30-md">
                        <label class="primary_input_label"
                            for="">{{ __('common.section') }}
                            <span class="text-danger"> *</span>
                        </label>
                        <select
                            class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                            name="section">
                            <option data-display="@lang('common.select_section') *" value="">
                                @lang('common.select_section') *
                            </option>
                            @foreach ($sections as $section)
                                <option value="{{ @$section->id }}"
                                    {{ isset($section_id) ? ($section_id == $section->id ? 'selected' : '') : '' }}>
                                    {{ @$section->section_name }}</option>
                            @endforeach
                        </select>
                        <div class="pull-right loader loader_style">
                            <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                alt="loader">
                        </div>
                        @if ($errors->has('section'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('section') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-lg-3 mt-30-md" id="exam_field_hide_show" style="display: none">
                        <label class="primary_input_label" for="">
                            {{ __('exam.exam') }}
                            <span class="text-danger"> *</span>
                        </label>
                        <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                            name="exam">
                            <option data-display="@lang('reports.select_exam') *" value="">
                                @lang('reports.select_exam') *
                            </option>
                            @foreach ($exam_types as $exam)
                                <option value="{{ $exam->id }}"
                                    {{ isset($exam_term_id) ? ($exam->id == $exam_term_id ? 'selected' : '') : '' }}>
                                    {{ $exam->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('exam'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('exam') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search"></span>
                            @lang('common.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--================ End Search Area =================-->

    <!--================ Start Class Routine Area =================-->
    @if (isset($sm_weekends))
        <section class="mt-20">
            <div class="container p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @lang('reports.class_routine')- {{ $header_class->class_name }}({{ $header_section->section_name }})
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-30  col-md-6">
                        <a href="{{ route('classRoutinePrint', [$class_id, $section_id]) }}"
                            class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                            @lang('common.print')</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped" style="width: 100%; table-layout: fixed">
                            <tr>
                                <th style="width:7%;padding: 2px; padding-left:8px;">
                                </th>
                                @php
                                    $height = 0;
                                    $tr = [];
                                @endphp
                                @foreach ($sm_weekends as $sm_weekend)
                                    @if ($sm_weekend->classRoutine->count() > $height)
                                        @php
                                            $height = $sm_weekend->classRoutine->count();
                                        @endphp
                                    @endif
                                    <th style="margin-top: 0px;padding: 2px; padding-left:8px">{{ @$sm_weekend->name }}
                                    </th>
                                @endforeach
                            </tr>
                            @php
                                $used = [];
                                $tr = [];
                            @endphp
                            @foreach ($sm_weekends as $sm_weekend)
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($sm_weekend->classRoutine as $routine)
                                    @php
                                        if (!in_array($routine->id, $used)) {
                                            $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ? $routine->subject->subject_name : '';
                                            $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->subject ? $routine->subject->subject_code : '';
                                            $tr[$i][$sm_weekend->name][$loop->index]['class_room'] = $routine->classRoom ? $routine->classRoom->room_no : '';
                                            $tr[$i][$sm_weekend->name][$loop->index]['teacher'] = $routine->teacherDetail ? $routine->teacherDetail->full_name : '';
                                            $tr[$i][$sm_weekend->name][$loop->index]['start_time'] = $routine->start_time;
                                            $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;
                                            $tr[$i][$sm_weekend->name][$loop->index]['is_break'] = $routine->is_break;
                                            $used[] = $routine->id;
                                        }
                                    @endphp
                                @endforeach
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            @for ($i = 0; $i < $height; $i++)
                                <tr style="border-bottom:1px solid #000000">
                                    <td
                                        style="padding-top:0px;padding-bottom:0px;font-size:14px !important; padding-left:8px">
                                        @lang('common.time')</td>
                                    @foreach ($tr as $days)
                                        @foreach ($sm_weekends as $sm_weekend)
                                            <td style="padding-top:0px ;padding-bottom:0px; padding-left:8px">
                                                @php
                                                    $classes = gv($days, $sm_weekend->name);
                                                @endphp
                                                @if ($classes && gv($classes, $i))
                                                    <span style="font-size:14px !important;">
                                                        {{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }} -
                                                        {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }} </span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                                <tr>
                                    <td
                                        style="padding-top:0px;padding-bottom:0px;font-size:14px !important; padding-left:8px">
                                        @lang('common.details')</td>
                                    @foreach ($tr as $days)
                                        @foreach ($sm_weekends as $sm_weekend)
                                            <td style="padding-top:0px ;padding-bottom:0px; padding-left:8px">
                                                @php
                                                    $classes = gv($days, $sm_weekend->name);
                                                @endphp
                                                @if ($classes && gv($classes, $i))
                                                    @if ($classes[$i]['is_break'])
                                                        <strong class="routineBorder"> @lang('common.break') </strong>
                                                    @else
                                                        @if ($classes[$i]['subject'])
                                                            <span class=""> <strong> {{ $classes[$i]['subject'] }}
                                                                </strong>
                                                                @if ($classes[$i]['class_room'])
                                                                    ({{ $classes[$i]['class_room'] }})
                                                                @endif <br>
                                                            </span>
                                                        @endif

                                                        @if ($classes[$i]['teacher'])
                                                            <span class=""> {{ $classes[$i]['teacher'] }} <br>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endfor
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--================ End Class Routine Area =================-->

    <!--================ Start Exam Routine Area =================-->
    @if (isset($exam_schedules))
        <section class="mt-20">
            <div class="container p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @lang('reports.exam_routine')- {{ $header_class->class_name }}({{ $header_section->section_name }})
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-30  col-md-6">
                        <a href="{{ route('exam-routine-print', [$class_id, $section_id, $exam_type_id]) }}"
                            class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                            @lang('common.print')</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table class="table class_exam_routine_table  table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('reports.date_&_day')</th>
                                        <th>@lang('common.subject')</th>
                                        <th>@lang('common.class_Sec')</th>
                                        <th>@lang('common.teacher')</th>
                                        <th>@lang('common.time')</th>
                                        <th>@lang('common.duration')</th>
                                        <th>@lang('common.room')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exam_schedules as $date => $exam_routine)
                                        <tr>
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
                                            <td>{{ $exam_routine->teacher ? $exam_routine->teacher->full_name : '' }}</td>

                                            <td> {{ date('h:i A', strtotime(@$exam_routine->start_time)) }} -
                                                {{ date('h:i A', strtotime(@$exam_routine->end_time)) }} </td>
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
                        </x-table>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--================ End Exam Routine Area =================-->
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    <script>
        function hideShowExamName(event) {
            let type = $(event).val();
            if (type === "exam") {
                $("#exam_field_hide_show").css("display", "block");
            } else {
                $("#exam_field_hide_show").css("display", "none");
            }
        }
    </script>
@endsection
