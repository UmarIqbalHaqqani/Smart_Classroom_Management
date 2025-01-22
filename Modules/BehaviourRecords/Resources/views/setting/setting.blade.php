@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.setting')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.setting')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.setting')</a>
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
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.setting_update', 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
                            <input type="hidden" name="type" value="comment">
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('behaviourRecords.incident_comment_setting')</h3>
                                </div>
                                <div class="add-visitor">
                                    <div class="row mb-0">
                                        <div class="col-lg-12 mt-0">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('behaviourRecords.comment_option')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="studentComment" id="studentComment"
                                                                class="common-checkbox permission-checkAll" value="1"
                                                                {{ $setting->student_comment == 1 ? 'checked' : '' }}>
                                                            <label for="studentComment" class="mt-0">@lang('behaviourRecords.student_comment')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="parentComment" id="parentComment"
                                                                class="common-checkbox permission-checkAll" value="1"
                                                                {{ $setting->parent_comment == 1 ? 'checked' : '' }}>
                                                            <label for="parentComment" class="mt-0">@lang('behaviourRecords.parent_comment')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 text-center mt-3">
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
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.setting_update', 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
                            <input type="hidden" name="type" value="view">
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('behaviourRecords.incident_view_setting')</h3>
                                </div>
                                <div class="add-visitor">
                                    <div class="row mb-0">
                                        <div class="col-lg-12 mt-0">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                            <label><strong>@lang('behaviourRecords.view_option')</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="studentView" id="studentView"
                                                                class="common-checkbox permission-checkAll" value="1"
                                                                {{ $setting->student_view == 1 ? 'checked' : '' }}>
                                                            <label for="studentView" class="mt-0">@lang('behaviourRecords.student_view')</label>
                                                        </div>
                                                        <div class="col-lg-6 primary_input sm_mb_20 p-1">
                                                            <input type="checkbox" name="parentView" id="parentView"
                                                                class="common-checkbox permission-checkAll" value="1"
                                                                {{ $setting->parent_view == 1 ? 'checked' : '' }}>
                                                            <label for="parentView" class="mt-0">@lang('behaviourRecords.parent_view')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 text-center mt-3">
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
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
