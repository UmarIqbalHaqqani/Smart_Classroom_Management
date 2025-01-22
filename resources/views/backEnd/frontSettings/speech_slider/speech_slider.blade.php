@extends('backEnd.master')
@section('title')
    @lang('front_settings.speech_slider')
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.speech_slider')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="{{ route('speech-slider') }}">@lang('front_settings.speech_slider')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row row-gap-24">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_speech_slider))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'speech-slider-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_speech_slider->id }}" name="id">
                        @else
                            @if (userPermission('speech-slider-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'speech-slider-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($add_speech_slider))
                                        @lang('front_settings.edit_speech_slider')
                                    @else
                                        @lang('front_settings.speech_slider')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.name') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off"
                                                value="{{ isset($add_speech_slider) ? @$add_speech_slider->name : old('name') }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.designation') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('designation') ? ' is-invalid' : '' }}"
                                                type="text" name="designation" autocomplete="off"
                                                value="{{ isset($add_speech_slider) ? @$add_speech_slider->designation : old('designation') }}">
                                            @if ($errors->has('designation'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('designation') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.title') </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off"
                                                value="{{ isset($add_speech_slider) ? @$add_speech_slider->title : old('title') }}">
                                            @if ($errors->has('title'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('title') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.speech') <span class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('speech') ? ' is-invalid' : '' }}" id="summernote"
                                                      name="speech" autocomplete="off"  cols="30" rows="4">{{ isset($add_speech_slider) ? @$add_speech_slider->speech : old('speech') }}</textarea>
                                            @if ($errors->has('speech'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('speech') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>                              
                                <div class="row mt-10">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.image') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="image"
                                                    placeholder="{{ isset($add_speech_slider) ? (@$add_speech_slider->image != '' ? getFilePath3(@$add_speech_slider->image) : trans('front_settings.image') . ' *') : trans('front_settings.image') . ' *' }}"
                                                    id="placeholderUploadContent" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="image"
                                                        id="document_file_1">
                                                </button>
                                                <code>(jpg,png,jpeg are allowed for upload)</code>
                                            </div>
                                            @if ($errors->has('image'))
                                                <span class="text-danger">
                                                    {{ $errors->first('image') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            @if (isset($add_speech_slider))
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
                                <h3 class="mb-15">@lang('front_settings.speech_slider_list')</h3>
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
                                            <th>@lang('front_settings.designation')</th>
                                            <th>@lang('front_settings.title')</th>
                                            <th>@lang('front_settings.image')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($speechSliders as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ @$value->name }}</td>
                                                <td>{{ @$value->designation }}</td>
                                                <td>{{ @$value->title }}</td>
                                                <td><img src="{{ asset(@$value->image) }}" width="60px"
                                                    height="50px"></td>
                                                <td>
                                                    <x-drop-down>
                                                        @if ($value->image)
                                                            <a class="dropdown-item"
                                                                href="{{ url(@$value->image) }}"
                                                                download>@lang('front_settings.download')</a>
                                                        @endif
                                                        <a class="dropdown-item"
                                                            href="{{ route('speech-slider-edit', @$value->id) }}">@lang('common.edit')</a>
                                                        <a href="{{ route('speech-slider-delete-modal', @$value->id) }}"
                                                            class="dropdown-item small fix-gr-bg modalLink"
                                                            title="@lang('front_settings.delete_speech_slider')" data-modal-size="modal-md">
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

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 200, // Set the height of the editor
            placeholder: 'Enter the speech content here...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

    
@endpush