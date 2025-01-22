@extends('backEnd.master')
@section('title')
    @lang('front_settings.donor')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.donor')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="{{ route('donor') }}">@lang('front_settings.donor')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if (isset($add_donor))
                                    @lang('front_settings.edit_donor')
                                @else
                                    @lang('front_settings.donor')
                                @endif
                            </h3>
                        </div>
                        @if (isset($add_donor))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'donor-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_donor->id }}" name="id">
                        @else
                            @if (userPermission('donor-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'donor-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.name') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off"
                                                value="{{ isset($add_donor) ? @$add_donor->full_name : old('name') }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.profession') </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('profession') ? ' is-invalid' : '' }}"
                                                type="text" name="profession" autocomplete="off"
                                                value="{{ isset($add_donor) ? @$add_donor->profession : old('profession') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label"
                                                for="date_of_birth">@lang('common.date_of_birth')</label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="date_of_birth" type="text" name="date_of_birth"
                                                                value="{{ !@$add_donor->date_of_birth ? (old('admission_date') != '' ? old('admission_date') : date('m/d/Y')) : date('m/d/Y', strtotime($add_donor->date_of_birth)) }}"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" style="top: 55% !important;"
                                                        data-id="#date_of_birth" type="button">
                                                        <label class="m-0 p-0" for="date_of_birth">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.email') </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                type="text" name="email" autocomplete="off"
                                                value="{{ isset($add_donor) ? @$add_donor->email : old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.mobile') </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                                type="text" name="mobile" autocomplete="off"
                                                value="{{ isset($add_donor) ? @$add_donor->mobile : old('mobile') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.photo') </label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="photo"
                                                    placeholder="{{ isset($add_donor) ? (@$add_donor->photo != '' ? getFilePath3(@$add_donor->photo) : trans('common.photo')) : trans('common.photo') }}"
                                                    id="placeholderUploadContent" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="photo"
                                                        id="document_file_1">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.age') </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('age') ? ' is-invalid' : '' }}"
                                                type="text" name="age" autocomplete="off"
                                                value="{{ isset($add_donor) ? @$add_donor->age : old('age') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <label><strong>@lang('front_settings.show_public')</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 radio-btn-flex">
                                            <div class="row">
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="show_public"
                                                        id="show_public"
                                                        class="common-radio" value="1"
                                                        {{ @$add_donor->show_public == 1 ? 'checked' : '' }}>
                                                    <label for="show_public">@lang('front_settings.show_public')</label>
                                                </div>
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="show_public"
                                                        id="do_not_show_public"
                                                        class="common-radio" value="0"
                                                        {{ @$add_donor->show_public == 0 ? 'checked' : '' }}>
                                                    <label for="do_not_show_public">@lang('front_settings.do_not_show_public')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.gender') </label>
                                            <select
                                                class="primary_select  form-control"
                                                name="gender">
                                                <option data-display="@lang('front_settings.gender')" value="">
                                                    @lang('front_settings.gender')
                                                </option>
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ @$add_donor->gender_id && $add_donor->gender_id == $gender->id ? 'selected' : '' }}>
                                                        {{ $gender->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.blood_group') </label>
                                            <select
                                                class="primary_select  form-control"
                                                name="blood_group">
                                                <option data-display="@lang('front_settings.blood_group')" value="">
                                                    @lang('front_settings.blood_group')
                                                </option>
                                                @foreach ($blood_groups as $blood_group)
                                                    <option value="{{ $blood_group->id }}"
                                                        {{ @$add_donor->bloodgroup_id && $add_donor->bloodgroup_id == $blood_group->id ? 'selected' : '' }}>
                                                        {{ $blood_group->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.religion') </label>
                                            <select
                                                class="primary_select  form-control"
                                                name="religion">
                                                <option data-display="@lang('front_settings.religion')" value="">
                                                    @lang('front_settings.religion')
                                                </option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion->id }}"
                                                        {{ @$add_donor->religion_id && $add_donor->religion_id == $religion->id ? 'selected' : '' }}>
                                                        {{ $religion->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6">
                                        <div class="primary_input  mt-20">
                                            <label class="primary_input_label" for="">@lang('front_settings.current_address')
                                            </label>
                                            <textarea class="primary_input_field form-control"
                                                cols="0" rows="3" name="current_address" id="current_address">{{ @$add_donor->current_address ? $add_donor->current_address : old('current_address') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input  mt-20">
                                            <label class="primary_input_label" for="">@lang('front_settings.permanent_address')
                                            </label>
                                            <textarea class="primary_input_field form-control"
                                                cols="0" rows="3" name="permanent_address" id="permanent_address">{{ @$add_donor->permanent_address ? $add_donor->permanent_address : old('permanent_address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @if (count($custom_fields) && is_show('custom_field') && isMenuAllowToShow('custom_field'))
                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="main-title">
                                                <h4 class="stu-sub-head">@lang('front_settings.custom_field')</h4>
                                            </div>
                                        </div>
                                    </div>
                                    @include('backEnd.studentInformation._custom_field')
                                @endif
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            @if (isset($add_donor))
                                                @lang('common.update')
                                            @else
                                                @lang('common.add')
                                            @endif
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

        <div class="row mt-50">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('front_settings.donor_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('front_settings.name')</th>
                                        <th>@lang('front_settings.profession')</th>
                                        <th>@lang('front_settings.email')</th>
                                        <th>@lang('front_settings.mobile')</th>
                                        <th>@lang('front_settings.image')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donors as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ @$value->full_name }}</td>
                                            <td>{{ @$value->profession }}</td>
                                            <td>{{ @$value->email }}</td>
                                            <td>{{ @$value->mobile }}</td>
                                            <td><img src="{{ asset(@$value->photo) }}" width="50px" height="50px">
                                            </td>
                                            <td>
                                                <x-drop-down>
                                                    <a class="dropdown-item"
                                                        href="{{ route('donor-edit', @$value->id) }}">@lang('common.edit')</a>
                                                    <a href="{{ route('donor-delete-modal', @$value->id) }}"
                                                        class="dropdown-item small fix-gr-bg modalLink"
                                                        title="@lang('front_settings.delete_donor')" data-modal-size="modal-md">
                                                        @lang('common.delete')
                                                    </a>
                                                </x-drop-down>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
