@extends('backEnd.master')
@section('title')
    @lang('hr.edit_staff')
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backEnd/') }}/css/croppie.css">
@endsection
@section('mainContent')
    <style type="text/css">
        .form-control:disabled {
            background-color: #FFFFFF;
        }

        .input-right-icon button.primary-btn-small-input {
            top: 66% !important;
            right: 11px !important;
        }
    </style>
    <input type="text" hidden id="urlStaff" value="{{ route('staffProfileUpdate', $editData->id) }}">
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.edit_staff')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ route('staff_directory') }}">@lang('hr.staff_list')</a>
                    <a href="{{ route('editStaff', $editData->id) }}">@lang('hr.edit_staff')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-dashboard', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
            @else
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staffUpdate', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('hr.edit_staff')</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 form_tab">
                                <ul class="nav nav-tabs tabs_scroll_nav px-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#basic_info" role="tab"
                                            data-toggle="tab">@lang('hr.basic_info')</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#payroll_details" role="tab"
                                            data-toggle="tab">@lang('hr.payroll_details')</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#bank_info_details" role="tab"
                                            data-toggle="tab">@lang('hr.bank_info_details')</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#social_link_details" role="tab"
                                            data-toggle="tab">@lang('hr.social_links_details')</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#document_info" role="tab"
                                            data-toggle="tab">@lang('hr.document_info')</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#custom_field" role="tab"
                                            data-toggle="tab">@lang('hr.custom_field')</a>
                                    </li>

                                    <li class="nav-item flex-grow-1 text-right">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12 text-end">
                                                        @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                                title="Disabled For Demo ">
                                                                <button class="primary-btn small fix-gr-bg  demo_view"
                                                                    style="pointer-events: none;" type="button">
                                                                    @lang('hr.update_staff')</button></span>
                                                        @else
                                                            <button class="primary-btn fix-gr-bg submit">
                                                                <span class="ti-check"></span>
                                                                @lang('hr.update_staff')
                                                            </button>
                                                        @endif
                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-tab-container">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade show active" id="basic_info">
                                            <div class="row row-gap-24 pt-4">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        <div class="row">
                                                            <input type="hidden" name="staff_id"
                                                                value="{{ @$editData->id }}" id="_id">
                                                            @if (in_array('staff_no', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.staff_number')
                                                                            {{ in_array('staff_no', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('staff_no') ? ' is-invalid' : '' }}"
                                                                            type="text" name="staff_no" readonly
                                                                            value="@if (isset($editData)) {{ $editData->staff_no }} @endif">


                                                                        @if ($errors->has('staff_no'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('staff_no') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('role', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.role')
                                                                            {{ in_array('role', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select
                                                                            class="primary_select  form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                                                            name="role_id" id="role_id">
                                                                            @if ($editData->role_id != 1)
                                                                                <option
                                                                                    data-display="@lang('hr.role') {{ in_array('role', $is_required) ? '*' : '' }}"
                                                                                    value="">@lang('common.select')
                                                                                    {{ in_array('role', $is_required) ? '*' : '' }}
                                                                                </option>

                                                                                @foreach ($roles as $key => $value)
                                                                                    <option value="{{ $value->id }}"
                                                                                        @if (isset($editData)) @if (($editData->role_id == 3 ? $editData->previous_role_id : $editData->role_id) == $value->id)
                                                                                                selected @endif
                                                                                        @endif
                                                                                        >{{ $value->name }}</option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="1">Superadmin</option>

                                                                            @endif
                                                                        </select>

                                                                        @if ($errors->has('role_id'))
                                                                            <span class="text-danger invalid-select"
                                                                                role="alert">
                                                                                {{ $errors->first('role_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('department', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.department')
                                                                            {{ in_array('department', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select
                                                                            class="primary_select  form-control{{ $errors->has('department_id') ? ' is-invalid' : '' }}"
                                                                            name="department_id" id="department_id">
                                                                            <option
                                                                                data-display="@lang('hr.department') {{ in_array('department', $is_required) ? '*' : '' }}"
                                                                                value="">@lang('common.select')
                                                                                {{ in_array('department', $is_required) ? '*' : '' }}
                                                                            </option>
                                                                            @foreach ($departments as $key => $value)
                                                                                <option value="{{ $value->id }}"
                                                                                    @if (isset($editData)) @if ($editData->department_id == $value->id)
                                                                                            selected @endif
                                                                                    @endif
                                                                                    >{{ $value->name }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                        @if ($errors->has('department_id'))
                                                                            <span class="text-danger invalid-select"
                                                                                role="alert">
                                                                                {{ $errors->first('department_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('designation', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.designation')
                                                                            {{ in_array('designation', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select
                                                                            class="primary_select  form-control{{ $errors->has('designation_id') ? ' is-invalid' : '' }}"
                                                                            name="designation_id" id="designation_id">
                                                                            <option
                                                                                data-display="@lang('hr.designation') {{ in_array('designation', $is_required) ? '*' : '' }}"
                                                                                value="">@lang('common.select')
                                                                                {{ in_array('designation', $is_required) ? '*' : '' }}
                                                                            </option>
                                                                            @foreach ($designations as $key => $value)
                                                                                <option value="{{ $value->id }}"
                                                                                    @if (isset($editData)) @if ($editData->designation_id == $value->id)
                                                                                            selected @endif
                                                                                    @endif
                                                                                    >{{ $value->title }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                        @if ($errors->has('designation_id'))
                                                                            <span class="text-danger invalid-select"
                                                                                role="alert">
                                                                                {{ $errors->first('designation_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if (in_array('first_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.first_name')
                                                                            {{ in_array('first_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="first_name"
                                                                            value="@if (isset($editData)) {{ $editData->first_name }} @endif">


                                                                        @if ($errors->has('first_name'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('first_name') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('last_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.last_name')
                                                                            {{ in_array('last_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="last_name"
                                                                            value="@if (isset($editData)) {{ $editData->last_name }} @endif">


                                                                        @if ($errors->has('last_name'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('last_name') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('fathers_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('student.father_name')
                                                                            {{ in_array('fathers_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('fathers_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="fathers_name"
                                                                            value="@if (isset($editData)) {{ $editData->fathers_name }} @endif">


                                                                        @if ($errors->has('fathers_name'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('fathers_name') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('mothers_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('student.mother_name')
                                                                            {{ in_array('mothers_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('mothers_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="mothers_name"
                                                                            value="@if (isset($editData)) {{ $editData->mothers_name }} @endif">


                                                                        @if ($errors->has('mothers_name'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('mothers_name') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('email', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('common.email')
                                                                            {{ in_array('email', $is_required) ? '*' : '' }}</label>
                                                                        <input oninput="emailCheck(this)"
                                                                            class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                            type="email" name="email"
                                                                            value="@if (isset($editData)) {{ $editData->email }} @endif">


                                                                        @if ($errors->has('email'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('email') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('gender', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('common.gender')
                                                                            {{ in_array('gender', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select
                                                                            class="primary_select  form-control{{ $errors->has('gender_id') ? ' is-invalid' : '' }}"
                                                                            name="gender_id">
                                                                            <option
                                                                                data-display="@lang('common.gender') {{ in_array('gender', $is_required) ? '*' : '' }}"
                                                                                value="">@lang('common.gender')
                                                                                {{ in_array('gender', $is_required) ? '*' : '' }}
                                                                            </option>
                                                                            @foreach ($genders as $gender)
                                                                                <option value="{{ $gender->id }}"
                                                                                    @if (isset($editData)) @if ($editData->gender_id == $gender->id)
                                                            selected @endif
                                                                                    @endif
                                                                                    >{{ $gender->base_setup_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @if ($errors->has('gender_id'))
                                                                            <span class="text-danger invalid-select"
                                                                                role="alert">
                                                                                {{ $errors->first('gender_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if (in_array('date_of_birth', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('common.date_of_birth')
                                                                            {{ in_array('date_of_birth', $is_required) ? '*' : '' }}</label>
                                                                        <div class="primary_datepicker_input">
                                                                            <div class="no-gutters input-right-icon">
                                                                                <div class="col">
                                                                                    <div class="">
                                                                                        <input
                                                                                            class="primary_input_field primary_input_field date form-control"
                                                                                            id="date_of_birth"
                                                                                            type="text"
                                                                                            name="date_of_birth"
                                                                                            value="{{ date('m/d/Y', strtotime($editData->date_of_birth)) }}"
                                                                                            autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <button class="btn-date"
                                                                                    data-id="#date_of_birth"
                                                                                    type="button">
                                                                                    <i class="ti-calendar"
                                                                                        id="start-date-icon"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <span
                                                                            class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('date_of_joining', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.date_of_joining')
                                                                            {{ in_array('date_of_joining', $is_required) ? '*' : '' }}</label>
                                                                        <div class="primary_datepicker_input">
                                                                            <div class="no-gutters input-right-icon">
                                                                                <div class="col">
                                                                                    <div class="">
                                                                                        <input
                                                                                            class="primary_input_field primary_input_field date form-control"
                                                                                            id="date_of_joining"
                                                                                            type="text"
                                                                                            name="date_of_joining"
                                                                                            value="{{ date('m/d/Y', strtotime($editData->date_of_joining)) }} "
                                                                                            autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <button class="btn-date"
                                                                                    data-id="#date_of_joining"
                                                                                    type="button">
                                                                                    <i class="ti-calendar"
                                                                                        id="start-date-icon"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <span
                                                                            class="text-danger">{{ $errors->first('date_of_joining') }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('mobile', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('common.mobile')
                                                                            {{ in_array('mobile', $is_required) ? '*' : '' }}</label>
                                                                        <input oninput="phoneCheck(this)"
                                                                            class="primary_input_field form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                                                            type="text" name="mobile"
                                                                            value="@if (isset($editData)) {{ $editData->mobile }} @endif">


                                                                        @if ($errors->has('mobile'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('mobile') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('marital_status', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.marital_status')
                                                                            {{ in_array('marital_status', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select class="primary_select  form-control"
                                                                            name="marital_status">
                                                                            <option
                                                                                data-display="@lang('hr.marital_status') {{ in_array('marital_status', $is_required) ? '*' : '' }}"
                                                                                value="">@lang('hr.marital_status')
                                                                                {{ in_array('marital_status', $is_required) ? '*' : '' }}
                                                                            </option>
                                                                            <option value="married"
                                                                                {{ $editData->marital_status == 'married' ? 'selected' : '' }}>
                                                                                @lang('hr.married')</option>
                                                                            <option value="unmarried"
                                                                                {{ $editData->marital_status == 'unmarried' ? 'selected' : '' }}>
                                                                                @lang('hr.unmarried')
                                                                            </option>

                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('emergency_mobile', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.emergency_mobile')
                                                                            {{ in_array('emergency_mobile', $is_required) ? '*' : '' }}</label>
                                                                        <input oninput="phoneCheck(this)"
                                                                            class="primary_input_field form-control{{ $errors->has('emergency_mobile') ? ' is-invalid' : '' }}"
                                                                            type="text" name="emergency_mobile"
                                                                            value="@if (isset($editData)) {{ $editData->emergency_mobile }} @endif">


                                                                        @if ($errors->has('emergency_mobile'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('emergency_mobile') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('driving_license', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.driving_license')
                                                                            {{ in_array('driving_license', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('driving_license') ? ' is-invalid' : '' }}"
                                                                            type="text" name="driving_license"
                                                                            value="{{ $editData->driving_license }}">


                                                                        @if ($errors->has('driving_license'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('driving_license') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('staff_photo', $has_permission))
                                                                <div class="col-lg-6 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">{{ trans('hr.staff_photo') }}</label>
                                                                        <div class="primary_file_uploader">
                                                                            <input class="primary_input_field"
                                                                                id="placeholderStaffsName" type="text"
                                                                                placeholder="{{ $editData->staff_photo != '' ? getFilePath3($editData->staff_photo) : (in_array('staff_photo', $is_required) ? trans('hr.staff_photo') . '*' : trans('hr.staff_photo')) }}"
                                                                                readonly>
                                                                            <button class="" type="button"
                                                                                id="pic">
                                                                                <label class="primary-btn small fix-gr-bg"
                                                                                    for="addStaffImage">@lang('common.browse')</label>
                                                                                <input type="file"
                                                                                    class="d-none form-control"
                                                                                    name="staff_photo" id="addStaffImage">
                                                                            </button>
                                                                        </div>

                                                                        @if ($errors->has('upload_event_image'))
                                                                            <span class="text-danger d-block">
                                                                                {{ $errors->first('upload_event_image') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <img class="previewImageSize my-2 {{ @$editData->staff_photo ? '' : 'd-none' }}"
                                                                src="{{ @$editData->staff_photo ? asset($editData->staff_photo) : '' }}"
                                                                alt="" id="staffImageShow" height="100%" width="100%">
                                                                </div>
                                                            @endif
                                                            <div class="col-lg-6 mb-20">
                                                                <div class="col-lg-12">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 primary_input sm_mb_20">
                                                                            <label>@lang('front_settings.show_as_expert_staff')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 radio-btn-flex">
                                                                    <div class="row">
                                                                        <div class="col-lg-5 primary_input sm_mb_20">
                                                                            <input type="radio" name="show_public"
                                                                                id="show_public" class="common-radio"
                                                                                value="1"
                                                                                {{ @$editData->show_public == 1 ? 'checked' : '' }}>
                                                                            <label
                                                                                for="show_public">@lang('front_settings.yes')</label>
                                                                        </div>
                                                                        <div class="col-lg-7 primary_input sm_mb_20">
                                                                            <input type="radio" name="show_public"
                                                                                id="do_not_show_public"
                                                                                class="common-radio" value="0"
                                                                                {{ @$editData->show_public == 0 ? 'checked' : '' }}>
                                                                            <label
                                                                                for="do_not_show_public">@lang('front_settings.no')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-6">

                                                            </div> -->
                                                            @if (in_array('current_address', $has_permission))
                                                                <div class="col-lg-6 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.current_address')
                                                                            {{ in_array('current_address', $is_required) ? '*' : '' }}</label>
                                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="current_address"
                                                                            id="current_address">
                                                            @if (isset($editData)){{ $editData->current_address }}@endif
                                                                </textarea>

                                                                        <span class="focus-border textarea "></span>
                                                                        @if ($errors->has('current_address'))
                                                                            <span class="text-danger d-block">
                                                                                {{ $errors->first('current_address') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if (in_array('permanent_address', $has_permission))
                                                                <div class="col-lg-6 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.permanent_address')
                                                                            {{ in_array('permanent_address', $is_required) ? '*' : '' }}</label>
                                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="permanent_address"
                                                                            id="permanent_address">
                                                                            @if (isset($editData)){{ $editData->permanent_address }}@endif
                                                                            </textarea>


                                                                        @if ($errors->has('permanent_address'))
                                                                            <span class="danger text-danger">
                                                                                {{ $errors->first('permanent_address') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('qualifications', $has_permission))
                                                                <div class="col-lg-6 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.qualifications')
                                                                            {{ in_array('qualifications', $is_required) ? '*' : '' }}</label>
                                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="qualification"
                                                                            id="qualification">
                                                                        @if (isset($editData)){{ $editData->qualification }}@endif
                                                                        </textarea>


                                                                        @if ($errors->has('qualification'))
                                                                            <span class="danger text-danger">
                                                                                {{ $errors->first('qualification') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('experience', $has_permission))
                                                                <div class="col-lg-6 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.experience')
                                                                            {{ in_array('experience', $is_required) ? '*' : '' }}</label>
                                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="experience" id="experience">
                                                                        @if (isset($editData)){{ $editData->experience }}@endif
                                                                        </textarea>


                                                                        @if ($errors->has('experience'))
                                                                            <span class="danger text-danger">
                                                                                {{ $errors->first('experience') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (moduleStatusCheck('Lms'))
                                                                <div class="row">
                                                                    @if (in_array('staff_bio', $has_permission))
                                                                        <div class="col-lg-12">
                                                                            <div class="primary_input">
                                                                                <label class="primary_input_label"
                                                                                    for="">@lang('staff.staff_bio')
                                                                                    {{ in_array('staff_bio', $is_required) ? '*' : '' }}</label>
                                                                                <textarea class="primary_input_field form-control" cols="0" rows="6" name="staff_bio" id="staff_bio">
                                                                                    @if (isset($editData)){{ $editData->staff_bio }}@endif
                                                                                    </textarea>


                                                                                @if ($errors->has('staff_bio'))
                                                                                    <span class="danger text-danger">
                                                                                        {{ $errors->first('staff_bio') }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="payroll_details">
                                            <div class="row pt-4 row-gap-24">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        <div class="row">
                                                            @if (in_array('epf_no', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.epf_no')
                                                                            {{ in_array('epf_no', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('epf_no') ? ' is-invalid' : '' }}"
                                                                            type="text" name="epf_no"
                                                                            value="{{ $editData->epf_no }}">


                                                                        @if ($errors->has('epf_no'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('epf_no') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('basic_salary', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.basic_salary')
                                                                            {{ in_array('basic_salary', $is_required) ? '*' : '' }}</label>
                                                                        <input oninput="numberCheckWithDot(this)"
                                                                            class="primary_input_field form-control{{ $errors->has('basic_salary') ? ' is-invalid' : '' }}"
                                                                            type="text" name="basic_salary"
                                                                            value="{{ $editData->basic_salary }}">


                                                                        @if ($errors->has('basic_salary'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('basic_salary') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('contract_type', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.contract_type')
                                                                            {{ in_array('contract_type', $is_required) ? '*' : '' }}
                                                                        </label>
                                                                        <select class="primary_select  form-control"
                                                                            name="contract_type">
                                                                            <option
                                                                                data-display="@lang('common.select') {{ in_array('contract_type', $is_required) ? '*' : '' }}"
                                                                                value="">@lang('common.select')
                                                                                {{ in_array('contract_type', $is_required) ? '*' : '' }}
                                                                            </option>
                                                                            <option value="permanent"
                                                                                @if (isset($editData)) @if ($editData->contract_type == 'permanent')
                                                        selected @endif
                                                                                @endif
                                                                                >@lang('hr.permanent')
                                                                            </option>
                                                                            <option value="contract"
                                                                                @if (isset($editData)) @if ($editData->contract_type == 'contract')
                                                        selected @endif
                                                                                @endif
                                                                                > @lang('hr.contract')
                                                                            </option>
                                                                        </select>


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('location', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.location')
                                                                            {{ in_array('location', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('location') ? ' is-invalid' : '' }}"
                                                                            type="text" name="location"
                                                                            value="{{ $editData->location }}">


                                                                        @if ($errors->has('location'))
                                                                            <span class="text-danger">
                                                                                {{ $errors->first('location') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="bank_info_details">
                                            <div class="row pt-4 row-gap-24">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        <div class="row">
                                                            @if (in_array('bank_account_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.bank_account_name')
                                                                            {{ in_array('bank_account_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('bank_account_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="bank_account_name"
                                                                            value="{{ $editData->bank_account_name }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('bank_account_no', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('accounts.account_no')
                                                                            {{ in_array('bank_account_no', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('bank_account_no') ? ' is-invalid' : '' }}"
                                                                            type="text" name="bank_account_no"
                                                                            value="{{ $editData->bank_account_no }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('bank_name', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('accounts.bank_name')
                                                                            {{ in_array('bank_name', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}"
                                                                            type="text" name="bank_name"
                                                                            value="{{ $editData->bank_name }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('bank_brach', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.branch_name')
                                                                            {{ in_array('bank_brach', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('bank_brach') ? ' is-invalid' : '' }}"
                                                                            type="text" name="bank_brach"
                                                                            value="{{ $editData->bank_brach }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="social_link_details">
                                            <div class="row pt-4 row-gap-24">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        <div class="row">
                                                            @if (in_array('facebook', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.facebook_url')
                                                                            {{ in_array('facebook', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('facebook_url') ? ' is-invalid' : '' }}"
                                                                            type="text" name="facebook_url"
                                                                            value="{{ $editData->facebook_url }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('twitter', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.twitter_url')
                                                                            {{ in_array('twitter', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('twiteer_url') ? ' is-invalid' : '' }}"
                                                                            type="text" name="twiteer_url"
                                                                            value="{{ $editData->twiteer_url }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('linkedin', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.linkedin_url')
                                                                            {{ in_array('linkedin', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('linkedin_url') ? ' is-invalid' : '' }}"
                                                                            type="text" name="linkedin_url"
                                                                            value="{{ $editData->linkedin_url }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('instagram', $has_permission))
                                                                <div class="col-lg-6 col-xl-3 mb-20">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">@lang('hr.instragram_url')
                                                                            {{ in_array('instagram', $is_required) ? '*' : '' }}</label>
                                                                        <input
                                                                            class="primary_input_field form-control{{ $errors->has('instragram_url') ? ' is-invalid' : '' }}"
                                                                            type="text" name="instragram_url"
                                                                            value="{{ $editData->instragram_url }}">


                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="document_info">
                                            <div class="row pt-4 row-gap-24">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        <div class="row">
                                                            @if (in_array('resume', $has_permission))
                                                                <div class="col-lg-4">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">{{ trans('hr.resume') }}</label>
                                                                        <div class="primary_file_uploader">
                                                                            <input
                                                                                class="primary_input_field form-control {{ $errors->has('resume') ? ' is-invalid' : '' }}"
                                                                                type="text"
                                                                                placeholder="{{ isset($editData->resume) && $editData->resume != '' ? getFilePath3($editData->resume) : (in_array('resume', $is_required) ? trans('hr.resume') . '*' : trans('hr.resume')) }}"
                                                                                readonly id="placeholderResume">
                                                                            <button class="" type="button"
                                                                                id="pic">
                                                                                <label class="primary-btn small fix-gr-bg"
                                                                                    for="resume">@lang('common.browse')</label>
                                                                                <input type="file"
                                                                                    class="d-none form-control"
                                                                                    name="resume" id="resume">
                                                                            </button>
                                                                        </div>

                                                                        @if ($errors->has('upload_event_image'))
                                                                            <span class="text-danger d-block">
                                                                                {{ $errors->first('upload_event_image') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('joining_letter', $has_permission))

                                                                <div class="col-lg-4">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">{{ trans('hr.joining_letter') }}</label>
                                                                        <div class="primary_file_uploader">
                                                                            <input
                                                                                class="primary_input_field form-control {{ $errors->has('joining_letter') ? ' is-invalid' : '' }}"
                                                                                type="text"
                                                                                placeholder="{{ isset($editData->joining_letter) && $editData->joining_letter != '' ? getFilePath3($editData->joining_letter) : (in_array('joining_letter', $is_required) ? trans('hr.joining_letter') . '*' : trans('hr.joining_letter')) }}"
                                                                                readonly id="placeholderJoiningLetter">
                                                                            <button class="" type="button"
                                                                                id="pic">
                                                                                <label class="primary-btn small fix-gr-bg"
                                                                                    for="joining_letter">@lang('common.browse')</label>
                                                                                <input type="file"
                                                                                    class="d-none form-control"
                                                                                    name="joining_letter"
                                                                                    id="joining_letter">
                                                                            </button>
                                                                        </div>

                                                                        @if ($errors->has('joining_letter'))
                                                                            <span class="text-danger d-block">
                                                                                {{ $errors->first('joining_letter') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if (in_array('other_document', $has_permission))

                                                                <div class="col-lg-4">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label"
                                                                            for="">{{ trans('hr.other_documents') }}</label>
                                                                        <div class="primary_file_uploader">
                                                                            <input
                                                                                class="primary_input_field form-control {{ $errors->has('other_document') ? ' is-invalid' : '' }}"
                                                                                type="text"
                                                                                placeholder="{{ isset($editData->other_document) && $editData->other_document != '' ? getFilePath3($editData->joining_letter) : (in_array('other_documents', $is_required) ? trans('hr.other_documents') . '*' : trans('hr.other_documents')) }}"
                                                                                readonly id="placeholderOthersDocument">
                                                                            <button class="" type="button"
                                                                                id="pic">
                                                                                <label class="primary-btn small fix-gr-bg"
                                                                                    for="other_document">@lang('common.browse')</label>
                                                                                <input type="file"
                                                                                    class="d-none form-control"
                                                                                    name="other_document"
                                                                                    id="other_document">
                                                                            </button>
                                                                        </div>

                                                                        @if ($errors->has('other_document'))
                                                                            <span class="text-danger d-block">
                                                                                {{ $errors->first('other_document') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="custom_field">
                                            <div class="row pt-4 row-gap-24">
                                                <div class="col-lg-12">
                                                    <div class="form-section">
                                                        {{-- Custom Field Start --}}
                                                        @if (in_array('custom_fields', $has_permission) && isMenuAllowToShow('custom_field') && count($custom_fields) > 0)
                                                            @include('backEnd.studentInformation._custom_field')
                                                        @endif

                                                        {{-- Custom Field End --}}
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
            </div>
            {{ Form::close() }}
        </div>
    </section>


    <div class="modal" id="LogoPic">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">@lang('hr.crop_image_and_upload')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div id="resize"></div>
                    <button class="btn rotate float-lef" data-deg="90">
                        <i class="ti-back-right"></i></button>
                    <button class="btn rotate float-right" data-deg="-90">
                        <i class="ti-back-left"></i></button>
                    <hr>
                    <a href="javascript:;" class="primary-btn fix-gr-bg pull-right"
                        id="upload_logo">@lang('hr.crop')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('backEnd.partials.date_picker_css_js')
@section('script')
    <script src="{{ asset('public/backEnd/') }}/js/croppie.js"></script>
    <script src="{{ asset('public/backEnd/') }}/js/editStaff.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '.cutom-photo', function() {
                let v = $(this).val();
                let v1 = $(this).data("id");
                console.log(v, v1);
                getFileName(v, v1);
            });

            function getFileName(value, placeholder) {
                if (value) {
                    var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                    var filename = value.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    $(placeholder).attr('placeholder', '');
                    $(placeholder).attr('placeholder', filename);
                }
            }
        })
        $(document).on('change', '#addStaffImage', function(event) {
            $('#staffImageShow').removeClass('d-none');
            getFileName($(this).val(), '#placeholderStaffsName');
            imageChangeWithFile($(this)[0], '#staffImageShow');
        });
    </script>
@endsection
@push('script')
    <script>
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    </script>
@endpush
