@extends('backEnd.master')
@section('title')
    @lang('front_settings.front_result')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.front_result')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('front-result') }}">@lang('front_settings.front_result')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        
                        @if (isset($add_front_result))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'front-result-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_front_result->id }}" name="id">
                        @else
                            @if (userPermission('front-result-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'front-result-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if (isset($add_front_result))
                                        @lang('front_settings.edit_front_result')
                                    @else
                                        @lang('front_settings.front_result')
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
                                                value="{{ isset($add_front_result) ? @$add_front_result->title : old('title') }}">
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
                                                                value="{{ isset($add_front_result) ? date('m/d/Y', strtotime(@$add_front_result->publish_date)) : date('m/d/Y') }}"
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
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('front_settings.content_type')</label>
                                        <select id="content_type"
                                            class="primary_select form-control"
                                            name="content_type">
                                            <option data-display="@lang('front_settings.link')" value="link"
                                                {{ @$add_front_result->link ? 'selected' : (old('content_type') == 'link' ? 'selected' : '') }}>
                                                @lang('front_settings.link')
                                            </option>
                                            <option data-display="@lang('front_settings.file')" value="file"
                                                {{ @$add_front_result->result_file ? 'selected' : (old('content_type') == 'file' ? 'selected' : '') }}>
                                                @lang('front_settings.file')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-20" id="linkSection">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.link') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('link') ? ' is-invalid' : '' }}"
                                                type="text" name="link" autocomplete="off"
                                                value="{{ isset($add_front_result) ? @$add_front_result->link : old('link') }}">
                                            @if ($errors->has('link'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('link') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20 d-none" id="fileSection">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.file') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('file') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="file"
                                                    placeholder="{{ isset($add_front_result) ? (@$add_front_result->result_file != '' ? getFilePath3(@$add_front_result->result_file) : trans('front_settings.file') . ' *') : trans('front_settings.file') . ' *' }}"
                                                    id="placeholderUploadContent" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="file"
                                                        id="document_file_1">
                                                </button>
                                                <code>(jpg,png,jpeg,pdf are allowed for upload)</code>
                                            </div>
                                            @if ($errors->has('file'))
                                                <span class="text-danger">
                                                    {{ $errors->first('file') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            @if (isset($add_front_result))
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
                                <h3 class="mb-15">@lang('front_settings.front_result_list')</h3>
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
                                        @foreach ($frontResults as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ @$value->title }}</td>
                                                <td>{{ date('m/d/Y', strtotime(@$value->publish_date)) }}</td>
                                                <td>
                                                    <x-drop-down>
                                                        @if ($value->result_file)
                                                            <a class="dropdown-item"
                                                                href="{{ url(@$value->result_file) }}"
                                                                download>@lang('front_settings.download')</a>
                                                        @endif
                                                        @if ($value->link)
                                                            <a class="dropdown-item"
                                                                href="{{ @$value->link }}"
                                                                target="_blank">@lang('front_settings.link')</a>
                                                        @endif
                                                        <a class="dropdown-item"
                                                            href="{{ route('front-result-edit', @$value->id) }}">@lang('common.edit')</a>
                                                        <a href="{{ route('front-result-delete-modal', @$value->id) }}"
                                                            class="dropdown-item small fix-gr-bg modalLink"
                                                            title="@lang('front_settings.delete_front_result')" data-modal-size="modal-md">
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
@push('script')
    <script>
        $(document).ready(function() {
            errorShowHide($("#content_type").val());

            $("#content_type").change(function() {
                errorShowHide($(this).val());
            });

            function errorShowHide(selector) {
                if (selector == "link") {
                    $("#fileSection").addClass('d-none');
                    $("#linkSection").removeClass('d-none');
                } else {
                    $("#fileSection").removeClass('d-none');
                    $("#linkSection").addClass('d-none');
                }
            }
        })
    </script>
@endpush
