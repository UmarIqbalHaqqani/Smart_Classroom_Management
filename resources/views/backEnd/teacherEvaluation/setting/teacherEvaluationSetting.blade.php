@extends('backEnd.master')
@section('title')
    @lang('teacherEvaluation.teacher_evaluation_setting')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('teacherEvaluation.teacher_evaluation_setting')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('teacherEvaluation.dashboard')</a>
                    <a href="#">@lang('teacherEvaluation.teacher_evaluation')</a>
                    <a href="#">@lang('teacherEvaluation.settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'teacher-evaluation-setting-update', 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
                            <input type="hidden" name="type" value="evaluation">
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('teacherEvaluation.evaluation_settings')</h3>
                                </div>
                                <div class="add-visitor">
                                    <div class="row mb-0">
                                        <div class="col-lg-12 mt-0">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('teacherEvaluation.evaluation')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 radio-btn-flex">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="is_enable"
                                                                id="teacherEvaluationEnable"
                                                                class="common-radio" value="0"
                                                                {{ $teacherEvaluationSetting->is_enable == 0 ? 'checked' : '' }}>
                                                            <label for="teacherEvaluationEnable">@lang('teacherEvaluation.enable')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="is_enable"
                                                                id="teacherEvaluationDisable"
                                                                class="common-radio" value="1"
                                                                {{ $teacherEvaluationSetting->is_enable == 1 ? 'checked' : '' }}>
                                                            <label for="teacherEvaluationDisable">@lang('teacherEvaluation.disable')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-10">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('teacherEvaluation.evaluation_approval')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 radio-btn-flex">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="auto_approval"
                                                                id="evaluationApprovalAuto"
                                                                class="common-radio permission-checkAll" value="0"
                                                                {{ $teacherEvaluationSetting->auto_approval == 0 ? 'checked' : '' }}>
                                                            <label for="evaluationApprovalAuto">@lang('teacherEvaluation.auto')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="auto_approval"
                                                                id="evaluationApprovalManual"
                                                                class="common-radio permission-checkAll" value="1"
                                                                {{ $teacherEvaluationSetting->auto_approval == 1 ? 'checked' : '' }}>
                                                            <label for="evaluationApprovalManual">@lang('teacherEvaluation.manual')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button type="submit" class="primary-btn small fix-gr-bg">
                                                @lang('behaviourRecords.save')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'teacher-evaluation-setting-update', 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
                            <input type="hidden" name="type" value="submission">
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('teacherEvaluation.submission_settings')</h3>
                                </div>
                                <div class="add-visitor">
                                    <div class="row mb-0">
                                        <div class="col-lg-12 mt-0">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('teacherEvaluation.submitted_by')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="submitted_by[]"
                                                                id="submitted_by1"
                                                                class="common-checkbox permission-checkAll" value="2"
                                                                {{ in_array('2', $teacherEvaluationSetting->submitted_by) ? 'checked' : '' }}>
                                                            <label for="submitted_by1">@lang('teacherEvaluation.student')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="submitted_by[]"
                                                                id="submitted_by2"
                                                                class="common-checkbox permission-checkAll" value="3"
                                                                {{ in_array('3', $teacherEvaluationSetting->submitted_by) ? 'checked' : '' }}>
                                                            <label for="submitted_by2">@lang('teacherEvaluation.parent')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-10">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('teacherEvaluation.submission_time')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 radio-btn-flex">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="rating_submission_time"
                                                                id="ratingSubmissionAnytime"
                                                                class="common-radio permission-checkAll" value="any"
                                                                {{ $teacherEvaluationSetting->rating_submission_time == 'any' ? 'checked' : '' }}>
                                                            <label for="ratingSubmissionAnytime">@lang('teacherEvaluation.any_time')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <input type="radio" name="rating_submission_time"
                                                                id="ratingSubmissionFixedtime"
                                                                class="common-radio permission-checkAll" value="fixed"
                                                                {{ $teacherEvaluationSetting->rating_submission_time == 'fixed' ? 'checked' : '' }}>
                                                            <label
                                                                for="ratingSubmissionFixedtime">@lang('teacherEvaluation.fixed_time')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12" id="submissionTimeRange">
                                                    <div class="row align-items-center mt-20">
                                                        <div class="col-lg-5">
                                                            <div class="primary_input sm_mb_20">
                                                                <label><strong>@lang('teacherEvaluation.start_date')</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7 d-flex">
                                                            <div class="primary_datepicker_input flex-grow-1">
                                                                <div class="no-gutters input-right-icon">
                                                                    <div class="col">
                                                                        <div class="">
                                                                            <input
                                                                                class="primary_input_field date form-control"
                                                                                id="startDate"
                                                                                type="text"
                                                                                name="startDate" readonly="true"
                                                                                value="{{ $teacherEvaluationSetting->from_date ? date('m/d/Y', strtotime($teacherEvaluationSetting->from_date)) : date('m/d/Y') }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn-date" data-id="#date_from"
                                                                        type="button">
                                                                        <label class="m-0 p-0" for="startDate">
                                                                            <i class="ti-calendar"
                                                                                id="start-date-icon"></i>
                                                                        </label>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center mt-20">
                                                        <div class="col-lg-5">
                                                            <div class="primary_input sm_mb_20">
                                                                <label><strong>@lang('teacherEvaluation.end_date')</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7 d-flex">
                                                            <div class="primary_datepicker_input flex-grow-1">
                                                                <div class="no-gutters input-right-icon">
                                                                    <div class="col">
                                                                        <div class="">
                                                                            <input
                                                                                class="primary_input_field date form-control"
                                                                                id="endDate" type="text"
                                                                                name="endDate" autocomplete="off"
                                                                                readonly="true"
                                                                                value="{{ $teacherEvaluationSetting->to_date ? date('m/d/Y', strtotime($teacherEvaluationSetting->to_date)) : date('m/d/Y', strtotime(' + 1 days')) }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn-date" data-id="#date_from"
                                                                        type="button">
                                                                        <label class="m-0 p-0" for="endDate">
                                                                            <i class="ti-calendar" id="end-date-icon"></i>
                                                                        </label>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="primary-btn small fix-gr-bg">
                                            @lang('behaviourRecords.save')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
@push('script')
    <script>
        $(document).ready(function() {
            $("#submissionTimeRange").attr('style', 'display: none!important');
            $("input[name='rating_submission_time']").change(function() {
                if ($(this).val() == "fixed") {
                    console.log('ok');
                    $("#submissionTimeRange").show();
                } else if ($(this).val() == "any") {
                    $("#submissionTimeRange").attr('style', 'display: none!important');
                }
            });
        })
    </script>
@endpush
