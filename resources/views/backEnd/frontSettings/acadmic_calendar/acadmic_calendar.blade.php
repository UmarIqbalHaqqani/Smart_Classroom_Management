@extends('backEnd.master')
@section('title')
    @lang('front_settings.front_academic_calendar')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.front_academic_calendar')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('front-academic-calendar') }}">@lang('front_settings.front_academic_calendar')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_front_academic_calendar))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'front-academic-calendar-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_front_academic_calendar->id }}" name="id">
                        @else
                            @if (userPermission('front-academic-calendar-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'front-academic-calendar-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if (isset($add_front_academic_calendar))
                                        @lang('front_settings.edit_front_academic_calendar')
                                    @else
                                        @lang('front_settings.front_academic_calendar')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.title') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off"
                                                value="{{ isset($add_front_academic_calendar) ? @$add_front_academic_calendar->title : old('title') }}">
                                            @if ($errors->has('title'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('title') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label"
                                                for="publish_date">{{ __('common.publish_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field date form-control{{ $errors->has('publish_date') ? ' is-invalid' : '' }}"
                                                                id="publish_date" type="text" name="publish_date"
                                                                value="{{ isset($add_front_academic_calendar) ? date('m/d/Y', strtotime(@$add_front_academic_calendar->publish_date)) : date('m/d/Y') }}"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" style="top: 55% !important;"
                                                        data-id="#publish_date" type="button">
                                                        <label class="m-0 p-0" for="publish_date">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('publish_date'))
                                                <span class="text-danger">
                                                    {{ $errors->first('publish_date') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.calendar_file') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('calendar_file') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="calendar_file"
                                                    placeholder="{{ isset($add_front_academic_calendar) ? (@$add_front_academic_calendar->calendar_file != '' ? getFilePath3(@$add_front_academic_calendar->calendar_file) : trans('front_settings.calendar_file') . ' *') : trans('front_settings.calendar_file') . ' *' }}"
                                                    id="placeholderUploadContent" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="calendar_file"
                                                        id="document_file_1">
                                                </button>
                                                <code>(jpg,png,jpeg,pdf are allowed for upload)</code>
                                            </div>
                                            @if ($errors->has('calendar_file'))
                                                <span class="text-danger">
                                                    {{ $errors->first('calendar_file') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            @if (isset($add_front_academic_calendar))
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

            <div class="col-lg-8">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('front_settings.front_academic_calendar_list')</h3>
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
                                            <th>@lang('front_settings.title')</th>
                                            <th>@lang('front_settings.date')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($frontAcademicCalendars as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ @$value->title }}</td>
                                                <td>{{ date('m/d/Y', strtotime(@$value->publish_date)) }}</td>
                                                <td>
                                                    <x-drop-down>
                                                        @if ($value->calendar_file)
                                                            <a class="dropdown-item"
                                                                href="{{ url(@$value->calendar_file) }}"
                                                                download>@lang('front_settings.download')</a>
                                                        @endif
                                                        <a class="dropdown-item"
                                                            href="{{ route('front-academic-calendar-edit', @$value->id) }}">@lang('common.edit')</a>
                                                        <a href="{{ route('front-academic-calendar-delete-modal', @$value->id) }}"
                                                            class="dropdown-item small fix-gr-bg modalLink"
                                                            title="@lang('front_settings.delete_front_academic_calendar')" data-modal-size="modal-md">
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
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
