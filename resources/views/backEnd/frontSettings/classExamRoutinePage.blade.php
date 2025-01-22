@extends('backEnd.master')
@section('title')
    @lang('front_settings.class_exam_routine_page')
@endsection
@push('css')
    <style>
        .col-lg-6.primary_input.sm_mb_20 {
            padding-left: 0;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.class_exam_routine_page')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#"> @lang('front_settings.front_settings')</a>
                    <a href="#"> @lang('front_settings.class_exam_routine_page')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (userPermission('news-heading-update'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'class-exam-routine-page-update',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">
                                        @lang('front_settings.class_exam_routine_page')
                                    </h3>
                                </div>
                                <div class="add-visitor {{ isset($update) ? '' : 'isDisabled' }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.title')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field "
                                                    type="text" name="title" autocomplete="off"
                                                    value="{{ isset($update) ? ($routine_page != '' ? $routine_page->title : '') : '' }}">
                                                @if ($errors->has('title'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('title') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-15">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('common.description')
                                                        <span class="text-danger"> *</span> </label>
                                                    <textarea class="primary_input_field form-control" cols="0" rows="5" name="description" id="description">{{ isset($update) ? ($routine_page != '' ? $routine_page->description : '') : '' }}</textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="primary_input mt-15">
                                                <label class="primary_input_label" for="">@lang('front_settings.main_title')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('main_title') ? ' is-invalid' : '' }}"
                                                    type="text" name="main_title" autocomplete="off"
                                                    value="{{ isset($update) ? ($routine_page != '' ? $routine_page->main_title : '') : '' }}">
                                                @if ($errors->has('main_title'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('main_title') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-15">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('front_settings.main_description')
                                                        <span class="text-danger"> *</span> </label>
                                                    <textarea class="primary_input_field form-control" cols="0" rows="5" name="main_description"
                                                        id="main_description">{{ isset($update) ? ($routine_page != '' ? $routine_page->main_description : '') : '' }}</textarea>
                                                    @if ($errors->has('main_description'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('main_description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="primary_input mt-15">
                                                <label class="primary_input_label" for="">@lang('front_settings.button_text')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                    type="text" name="button_text" autocomplete="off"
                                                    value="{{ isset($update) ? ($routine_page != '' ? $routine_page->button_text : '') : '' }}">
                                                @if ($errors->has('button_text'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('button_text') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-15">
                                                <label class="primary_input_label" for="">@lang('front_settings.button_url')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                    type="text" name="button_url" autocomplete="off"
                                                    value="{{ isset($update) ? ($routine_page != '' ? $routine_page->button_url : '') : '' }}">
                                                @if ($errors->has('button_url'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('button_url') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <span class="mt-10">@lang('front_settings.image')(1420px*450px)</span>
                                            <div class="primary_input">
                                                @if ($errors->has('image'))
                                                    <span class="text-danger mb-10" role="alert">
                                                        {{ $errors->first('image') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input
                                                        class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                                        id="placeholderInput" type="text"
                                                        placeholder="{{ isset($update) ? (($routine_page and $routine_page->image) != '' ? getFilePath3($routine_page->image) : trans('common.image') . ' *') : trans('common.image') . ' *' }}"
                                                        readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                            for="browseFile">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="image"
                                                            id="browseFile">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <span class="mt-10">@lang('common.image')(1420px*450px)</span>
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input
                                                        class="primary_input_field form-control{{ $errors->has('main_image') ? ' is-invalid' : '' }}"
                                                        id="placeholderIn" type="text"
                                                        placeholder="{{ isset($update) ? (($routine_page and $routine_page->main_image != '') ? getFilePath3($routine_page->main_image) : trans('common.main') . ' ' . trans('common.image') . ' *') : trans('common.main') . ' ' . trans('common.image') . ' *' }}"
                                                        readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                            for="browseFil">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="main_image"
                                                            id="browseFil">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-6 d-flex">
                                            <div class="col-lg-6 primary_input sm_mb_20">
                                                <label><strong>@lang('front_settings.class_routine')</strong></label>
                                            </div>
                                            <div class="col-lg-6 d-flex radio-btn-flex">
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="class_routine"
                                                        id="classRoutineShow"
                                                        class="common-radio" value="show"
                                                        {{ $routine_page->class_routine == 'show' ? 'checked' : '' }}>
                                                    <label for="classRoutineShow">@lang('front_settings.show')</label>
                                                </div>
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="class_routine"
                                                        id="classRoutineHide"
                                                        class="common-radio" value="hide"
                                                        {{ $routine_page->class_routine == 'hide' ? 'checked' : '' }}>
                                                    <label
                                                        for="classRoutineHide">@lang('front_settings.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">
                                            <div class="col-lg-6 primary_input sm_mb_20">
                                                <label><strong>@lang('front_settings.exam_routine')</strong></label>
                                            </div>
                                            <div class="col-lg-6 d-flex radio-btn-flex">
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="exam_routine"
                                                        id="examRoutineShow"
                                                        class="common-radio" value="show"
                                                        {{ $routine_page->exam_routine == 'show' ? 'checked' : '' }}>
                                                    <label for="examRoutineShow">@lang('front_settings.show')</label>
                                                </div>
                                                <div class="col-lg-6 primary_input sm_mb_20">
                                                    <input type="radio" name="exam_routine"
                                                        id="examRoutineHide"
                                                        class="common-radio" value="hide"
                                                        {{ $routine_page->exam_routine == 'hide' ? 'checked' : '' }}>
                                                    <label
                                                        for="examRoutineHide">@lang('front_settings.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tooltip = '';
                                        if (userPermission('news-heading-update')) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                    title="Disabled For Demo "> <button
                                                        class="primary-btn fix-gr-bg  demo_view"
                                                        style="pointer-events: none;" type="button">@lang('common.update')
                                                    </button></span>
                                            @else
                                                <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                    title="{{ @$tooltip }}">
                                                    <span class="ti-check"></span>
                                                    @if (isset($update))
                                                        @lang('common.update')
                                                    @else
                                                        @lang('common.save')
                                                    @endif
                                                </button>
                                            @endif
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
