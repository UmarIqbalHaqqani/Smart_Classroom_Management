@extends('backEnd.master')
@section('title')
    @lang('hr.add_new_staff')
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backEnd/') }}/css/croppie.css">
    <style>
        .input-right-icon button {
            top: 55% !important;
        }
    </style>
@endsection
@section('mainContent')
    <style type="text/css">
        .form-control:disabled {
            background-color: #FFFFFF;
        }

        .input-right-icon button {
            top: 55% !important;
        }
    </style>

    <input type="text" hidden id="urlStaff" value="{{ route('staffPicStore') }}">
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.add_new_staff')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ route('staff_directory') }}">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.add_new_staff')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staffStore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="container-fluid p-0">

            <div class="white-box">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-15">@lang('hr.staff_information') </h3>
                        </div>
                    </div>
                    <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                        <a href="{{ route('import-staff') }}" class="primary-btn small fix-gr-bg">
                            @lang('hr.import_staff')
                        </a>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 form_tab">
                        <ul class="nav nav-tabs tabs_scroll_nav no-scroll px-0" role="tablist">
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
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @lang('hr.save_staff')

                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="col-lg-12">
                            <div class="form-tab-container">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade show active" id="basic_info">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    <input type="hidden" name="url" id="url"
                                                        value="{{ URL::to('/') }}">
                                                    @if (moduleStatusCheck('MultiBranch') && isset($branches))
                                                        <div class="row">
                                                            <div class="col-lg-6 col-xl-3 mb-20">
                                                                <div class="primary_input">
                                                                    <select
                                                                        class="primary_select  form-control{{ $errors->has('branch_id') ? ' is-invalid' : '' }}"
                                                                        name="branch_id" id="branch_id">
                                                                        <option data-display="@lang('hr.branch') *"
                                                                            value="">@lang('hr.branch')
                                                                            *</option>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch->id }}"
                                                                                {{ isset($branch_id) ? ($branch->id == $branch_id ? 'selected' : '') : '' }}>
                                                                                {{ $branch->branch_name }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                    @if ($errors->has('branch_id'))
                                                                        <span class="text-danger invalid-select"
                                                                            role="alert">
                                                                            {{ $errors->first('branch_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.staff_no')
                                                                    {{ in_array('staff_no', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('staff_no') ? ' is-invalid' : '' }}"
                                                                    type="text" name="staff_no"
                                                                    value="{{ $max_staff_no != '' ? $max_staff_no + 1 : 1 }}"
                                                                    readonly>


                                                                @if ($errors->has('staff_no'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('staff_no') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.role')
                                                                    {{ in_array('role', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <select
                                                                    class="primary_select  form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                                                    name="role_id" id="role_id">
                                                                    <option
                                                                        data-display="@lang('hr.role') {{ in_array('role', $is_required) ? '*' : '' }}"
                                                                        value="">@lang('common.select')
                                                                        {{ in_array('role', $is_required) ? '*' : '' }}
                                                                    </option>
                                                                    @foreach ($roles as $key => $value)
                                                                        <option value="{{ $value->id }}"
                                                                            {{ old('role_id') == $value->id ? 'selected' : '' }}>
                                                                            {{__('rolepermission::role.'.$value->name) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                @if ($errors->has('role_id'))
                                                                    <span class="text-danger invalid-select"
                                                                        role="alert">
                                                                        {{ $errors->first('role_id') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

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
                                                                            {{ old('department_id') == $value->id ? 'selected' : '' }}>
                                                                            {{ $value->name }}</option>
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
                                                                        data-display="@lang('hr.designations') {{ in_array('designation', $is_required) ? '*' : '' }}"
                                                                        value="">@lang('common.select')
                                                                        {{ in_array('designation', $is_required) ? '*' : '' }}
                                                                    </option>
                                                                    @foreach ($designations as $key => $value)
                                                                        <option value="{{ $value->id }}"
                                                                            {{ old('designation_id') == $value->id ? 'selected' : '' }}>
                                                                            {{ $value->title }}</option>
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


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.first_name')
                                                                    {{ in_array('first_name', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <input
                                                                    class="primary_input_field form-control {{ $errors->has('first_name') ? 'is-invalid' : ' ' }}"
                                                                    type="text" name="first_name"
                                                                    value="{{ old('first_name') }}">


                                                                @if ($errors->has('first_name'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('first_name') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.last_name')
                                                                    {{ in_array('last_name', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                                                    type="text" name="last_name"
                                                                    value="{{ old('last_name') }}">


                                                                @if ($errors->has('last_name'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('last_name') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('student.father_name')
                                                                    {{ in_array('fathers_name', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('fathers_name') ? ' is-invalid' : '' }}"
                                                                    type="text" name="fathers_name"
                                                                    value="{{ old('first_name') }}">


                                                                @if ($errors->has('fathers_name'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('fathers_name') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.mother_name')
                                                                    {{ in_array('mothers_name', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('mothers_name') ? ' is-invalid' : '' }}"
                                                                    type="text" name="mothers_name"
                                                                    value="{{ old('mothers_name') }}">


                                                                @if ($errors->has('mothers_name'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('mothers_name') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.email')
                                                                    {{ in_array('email', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <input onkeyup="emailCheck(this)"
                                                                    class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                    type="email" name="email"
                                                                    value="{{ old('email') }}">


                                                                @if ($errors->has('email'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('email') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.gender')
                                                                    {{ in_array('gender', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <select
                                                                    class="primary_select  form-control{{ $errors->has('gender_id') ? ' is-invalid' : '' }}"
                                                                    name="gender_id">
                                                                    <option data-display="@lang('common.gender') "
                                                                        value="">@lang('common.gender')
                                                                        {{ in_array('gender', $is_required) ? '*' : '' }}
                                                                    </option>
                                                                    @foreach ($genders as $gender)
                                                                        <option value="{{ $gender->id }}"
                                                                            {{ old('gender_id') == $gender->id ? 'selected' : '' }}>
                                                                            {{ $gender->base_setup_name }}</option>
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
                                                                                    id="date_of_birth" type="text"
                                                                                    name="date_of_birth"
                                                                                    value="{{ old('date_of_birth') }}"
                                                                                    autocomplete="off">
                                                                            </div>
                                                                        </div>
                                                                        <button class="btn-date" data-id="#date_of_birth"
                                                                            type="button">
                                                                            <label class="m-0 p-0" for="date_of_birth">
                                                                                <i class="ti-calendar"
                                                                                    id="start-date-icon"></i>
                                                                            </label>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <span
                                                                    class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                                            </div>
                                                        </div>
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
                                                                                    id="date_of_joining" type="text"
                                                                                    name="date_of_joining"
                                                                                    value="{{ date('m/d/Y') }}"
                                                                                    autocomplete="off">
                                                                            </div>
                                                                        </div>
                                                                        <button class="btn-date"
                                                                            data-id="#date_of_joining" type="button">
                                                                            <label class="m-0 p-0" for="date_of_joining">
                                                                                <i class="ti-calendar"
                                                                                    id="start-date-icon"></i>
                                                                            </label>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <span
                                                                    class="text-danger">{{ $errors->first('date_of_joining') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-20">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.mobile')
                                                                    {{ in_array('mobile', $is_required) ? '*' : '' }}</label>
                                                                <input oninput="phoneCheck(this)"
                                                                    class="primary_input_field form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                                                    type="text" name="mobile"
                                                                    value="{{ old('mobile') }}">


                                                                @if ($errors->has('mobile'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('mobile') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
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

                                                                    <option
                                                                        {{ old('marital_status') == 'married' ? 'selected' : '' }}
                                                                        value="married">@lang('hr.married')</option>
                                                                    <option
                                                                        {{ old('marital_status') == 'unmarried' ? 'selected' : '' }}
                                                                        value="unmarried">@lang('hr.unmarried')</option>

                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.emergency_mobile')
                                                                    {{ in_array('emergency_mobile', $is_required) ? '*' : '' }}</label>
                                                                <input oninput="phoneCheck(this)"
                                                                    class="primary_input_field form-control{{ $errors->has('emergency_mobile') ? ' is-invalid' : '' }}"
                                                                    type="text" name="emergency_mobile"
                                                                    value="{{ old('emergency_mobile') }}">


                                                                @if ($errors->has('emergency_mobile'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('emergency_mobile') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.driving_license')
                                                                    {{ in_array('driving_license', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('driving_license') ? ' is-invalid' : '' }}"
                                                                    type="text" name="driving_license"
                                                                    value="{{ old('driving_license') }}">


                                                                @if ($errors->has('driving_license'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('driving_license') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="row mb-20">
                                                        <div class="col-lg-6 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.staff_photo')
                                                                    {{ in_array('staff_photo', $is_required) ? '*' : '' }}</label>
                                                                <div class="primary_file_uploader">
                                                                    <input
                                                                        class="primary_input_field form-control {{ $errors->has('staff_photo') ? ' is-invalid' : '' }}"
                                                                        type="text" id="placeholderStaffsName"
                                                                        placeholder="@lang('hr.staff_photo')" disabled>
                                                                    <code>(JPG,JPEG,PNG are allowed for upload)</code>
                                                                    <button class="" type="button">
                                                                        <label class="primary-btn small fix-gr-bg"
                                                                            for="addStaffImage">{{ __('common.browse') }}</label>
                                                                        <input type="file" class="d-none"
                                                                            name="staff_photo" id="addStaffImage">
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                                            {{ @$add_form_download->show_public == 1 ? 'checked' : '' }}>
                                                                        <label for="show_public">@lang('front_settings.yes')</label>
                                                                    </div>
                                                                    <div class="col-lg-7 primary_input sm_mb_20">
                                                                        <input type="radio" name="show_public"
                                                                            id="do_not_show_public" class="common-radio"
                                                                            value="0"
                                                                            {{ @$add_form_download->show_public == 0 ? 'checked' : '' }}>
                                                                        <label
                                                                            for="do_not_show_public">@lang('front_settings.no')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <img class="d-none previewImageSize" src="" alt="" id="staffImageShow" height="100%" width="100%">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.current_address')
                                                                    {{ in_array('current_address', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <textarea class="primary_input_field form-control {{ $errors->has('current_address') ? 'is-invalid' : '' }}"
                                                                    cols="0" rows="4" name="current_address" id="current_address">{{ old('current_address') }}</textarea>



                                                                @if ($errors->has('current_address'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('current_address') }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.permanent_address')
                                                                    {{ in_array('permanent_address', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <textarea class="primary_input_field form-control {{ $errors->has('permanent_address') ? 'is-invalid' : '' }}"
                                                                    cols="0" rows="4" name="permanent_address" id="permanent_address">{{ old('permanent_address') }}</textarea>


                                                                @if ($errors->has('permanent_address'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('permanent_address') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row md-20">
                                                        <div class="col-lg-6 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.qualifications')
                                                                    {{ in_array('qualifications', $is_required) ? '*' : '' }}</label>
                                                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="qualification"
                                                                    id="qualification">{{ old('qualification') }}</textarea>


                                                                @if ($errors->has('qualification'))
                                                                    <span class="danger text-danger">
                                                                        {{ $errors->first('qualification') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.experience')
                                                                    {{ in_array('experience', $is_required) ? '*' : '' }}</label>
                                                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="experience" id="experience"
                                                                    value="{{ old('experience') }}"></textarea>


                                                                @if ($errors->has('experience'))
                                                                    <span class="danger text-danger">
                                                                        {{ $errors->first('experience') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="payroll_details">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    <div class="row mb-20">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.epf_no')
                                                                    {{ in_array('epf_no', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('epf_no') ? ' is-invalid' : '' }}"
                                                                    type="text" name="epf_no"
                                                                    value="{{ old('epf_no') }}">


                                                                @if ($errors->has('epf_no'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('epf_no') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.basic_salary')
                                                                    {{ in_array('basic_salary', $is_required) ? '*' : '' }}</label>
                                                                <input oninput="numberCheck(this)"
                                                                    class="primary_input_field form-control{{ $errors->has('basic_salary') ? ' is-invalid' : '' }}"
                                                                    type="text" name="basic_salary"
                                                                    value="{{ old('basic_salary') }}"
                                                                    autocomplete="off">


                                                                @if ($errors->has('basic_salary'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('basic_salary') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.contract_type')
                                                                    {{ in_array('contract_type', $is_required) ? '*' : '' }}
                                                                </label>
                                                                <select class="primary_select  form-control"
                                                                    name="contract_type">
                                                                    <option
                                                                        data-display="@lang('hr.contract_type') {{ in_array('contract_type', $is_required) ? '*' : '' }}"
                                                                        value=""> @lang('hr.contract_type')
                                                                        {{ in_array('contract_type', $is_required) ? '*' : '' }}
                                                                    </option>
                                                                    <option value="permanent">@lang('hr.permanent') </option>
                                                                    <option value="contract"> @lang('hr.contract')</option>
                                                                </select>


                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('hr.location')
                                                                    {{ in_array('location', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('location') ? ' is-invalid' : '' }}"
                                                                    type="text" value="{{ old('location') }}"
                                                                    name="location">


                                                                @if ($errors->has('location'))
                                                                    <span class="text-danger">
                                                                        {{ $errors->first('location') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="bank_info_details">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    <div class="row mb-20">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.bank_account_name')
                                                                    {{ in_array('bank_account_name', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('bank_account_name') ? ' is-invalid' : '' }}"
                                                                    type="text" name="bank_account_name" value="{{ old('bank_account_name') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('accounts.account_no')
                                                                    {{ in_array('bank_account_no', $is_required) ? '*' : '' }}</label>
                            
                                                                <input onkeyup="numberCheck(this)"
                                                                    class="primary_input_field form-control{{ $errors->has('bank_account_no') ? ' is-invalid' : '' }}"
                                                                    type="text" name="bank_account_no" value="{{ old('bank_account_no') }}">
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('accounts.bank_name')
                                                                    {{ in_array('bank_name', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}"
                                                                    type="text" name="bank_name" value="{{ old('bank_name') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('accounts.branch_name')
                                                                    {{ in_array('bank_brach', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('bank_brach') ? ' is-invalid' : '' }}"
                                                                    type="text" name="bank_brach" value="{{ old('bank_brach') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="social_link_details">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    <div class="row mb-20">
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.facebook_url')
                                                                    {{ in_array('facebook', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('facebook_url') ? ' is-invalid' : '' }}"
                                                                    type="text" name="facebook_url" value={{ old('facebook_url') }}>
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.twitter_url')
                                                                    {{ in_array('twitter', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('twiteer_url') ? ' is-invalid' : '' }}"
                                                                    type="text" name="twiteer_url" value="{{ old('twiteer_url') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.linkedin_url')
                                                                    {{ in_array('linkedin', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('linkedin_url') ? ' is-invalid' : '' }}"
                                                                    type="text" name="linkedin_url" value="{{ old('linkedin_url') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-lg-6 col-xl-3 mb-20">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.instragram_url')
                                                                    {{ in_array('instragram', $is_required) ? '*' : '' }}</label>
                                                                <input
                                                                    class="primary_input_field form-control{{ $errors->has('instragram_url') ? ' is-invalid' : '' }}"
                                                                    type="text" name="instragram_url" value="{{ old('instragram_url') }}">
                            
                            
                            
                                                            </div>
                                                        </div>
                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="document_info">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    <div class="row mb-20">
                                                        <div class="col-lg-4">
                            
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.resume')
                                                                    {{ in_array('resume', $is_required) ? '*' : '' }}</label>
                                                                <div class="primary_file_uploader">
                                                                    <input class="primary_input_field" type="text" id="placeholderResume"
                                                                        placeholder="@lang('hr.resume')" readonly>
                                                                    <button class="" type="button">
                                                                        <label class="primary-btn small fix-gr-bg"
                                                                            for="resume">{{ __('common.browse') }}</label>
                                                                        <input type="file" class="d-none" name="resume" id="resume">
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                            
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.joining_letter')
                                                                    {{ in_array('joining_letter', $is_required) ? '*' : '' }}</label>
                                                                <div class="primary_file_uploader">
                                                                    <input class="primary_input_field" type="text" id="placeholderJoiningLetter"
                                                                        placeholder="@lang('hr.joining_letter')" readonly>
                                                                    <button class="" type="button">
                                                                        <label class="primary-btn small fix-gr-bg"
                                                                            for="joining_letter">{{ __('common.browse') }}</label>
                                                                        <input type="file" class="d-none" name="joining_letter"
                                                                            id="joining_letter">
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="primary_input_label" for="">@lang('hr.other_documents')
                                                                {{ in_array('other_documents', $is_required) ? '*' : '' }}</label>
                                                            <div class="primary_input">
                                                                <div class="primary_file_uploader">
                                                                    <input class="primary_input_field" type="text" id="placeholderOthersDocument"
                                                                        placeholder="@lang('hr.other_documents')" readonly>
                                                                    <button class="" type="button">
                                                                        <label class="primary-btn small fix-gr-bg"
                                                                            for="other_document">{{ __('common.browse') }}</label>
                                                                        <input type="file" class="d-none" name="other_document"
                                                                            id="other_document">
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="custom_field">
                                        <div class="row pt-4 row-gap-24">
                                            <div class="col-lg-12 p-0">
                                                <div class="form-section">
                                                    @if (isset($custom_fields) && $custom_fields->count())
                                                    {{-- Custom Field Start --}}
                                                    <div class="row mt-40">
                                                        <div class="col-lg-12">
                                                            <div class="main-title">
                                                                <h4>@lang('hr.custom_field')</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    @include('backEnd.studentInformation._custom_field')
                                                    {{-- Custom Field End --}}
                                                @endif
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

@endsection
@include('backEnd.partials.date_picker_css_js')
@section('script')
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
    </script>
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
