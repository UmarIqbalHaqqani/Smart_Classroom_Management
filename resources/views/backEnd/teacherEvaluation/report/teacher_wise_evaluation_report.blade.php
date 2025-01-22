@extends('backEnd.master')
@push('css')
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            font-size: 1.5em;
            justify-content: space-around;
            text-align: center;
            width: 5em;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating :checked~label {
            color: #f90;
        }

        article {
            background-color: #ffe;
            box-shadow: 0 0 1em 1px rgba(0, 0, 0, .25);
            color: #006;
            font-family: cursive;
            font-style: italic;
            margin: 4em;
            max-width: 30em;
            padding: 2em;
        }
    </style>
@endpush
@section('title')
    @lang('teacherEvaluation.teacher_wise_evaluation_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('teacherEvaluation.teacher_wise_evaluation_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('teacherEvaluation.dashboard')</a>
                    <a href="#">@lang('teacherEvaluation.teacher_evaluation')</a>
                    <a href="#">@lang('teacherEvaluation.teacher_wise_evaluation_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-40">
                                <div class="col-lg-12">
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'teacher-wise-evaluation-report-search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-lg-4 no-gutters">
                                                <div class="main-title">
                                                    <h3 class="mb-15">@lang('teacherEvaluation.teacher_wise_evaluation_report') </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 mt-30-md" id="select_teacher_div">
                                                <label class="primary_input_label" for="">@lang('teacherEvaluation.teacher')
                                                    <span
                                                        class="text-danger"> </span></label>
                                                <select class="primary_select " id="select_teacher" name="teacher_id">
                                                    <option data-display="@lang('teacherEvaluation.select_teacher')" value="">
                                                        @lang('teacherEvaluation.select_teacher')</option>
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}"
                                                            {{ old('full_name') != '' ? 'selected' : '' }}>
                                                            {{ $teacher->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="pull-right loader loader_style"
                                                    id="select_teacher_loader">
                                                    <img class="loader_img_style"
                                                        src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                        alt="loader">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-30-md" id="select_submitted_by_div">
                                                <label class="primary_input_label" for="">@lang('teacherEvaluation.submitted_by')
                                                    <span
                                                        class="text-danger"> </span></label>
                                                <select class="primary_select " id="select_submitted_by"
                                                    name="submitted_by">
                                                    <option data-display="@lang('teacherEvaluation.select_submitted_by')" value="">
                                                        @lang('teacherEvaluation.select_submitted_by')</option>
                                                    <option data-display="@lang('teacherEvaluation.parent')" value="3">
                                                        @lang('teacherEvaluation.parent')</option>
                                                    <option data-display="@lang('teacherEvaluation.student')" value="2">
                                                        @lang('teacherEvaluation.student')</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12 mt-20 text-right">
                                                <button type="submit" class="primary-btn small fix-gr-bg">
                                                    <span class="ti-search pr-2"></span>
                                                    @lang('teacherEvaluation.search')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="white-box">
                                        <div class="mt-40">
                                            @include(
                                        'backEnd.teacherEvaluation.report._teacher_evaluation_report_common_table',
                                        [
                                            'approved_evaluation_button_enable' => false,
                                        ]
                                    )
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
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
