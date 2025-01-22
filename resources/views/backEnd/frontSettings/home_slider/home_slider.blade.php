@extends('backEnd.master')
@section('title')
    @lang('front_settings.home_slider')
@endsection

@push('css')
<style>
    .child .dtr-data {
        word-break: break-all;
    }
</style>
@endpush


@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.home_slider')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('home-slider') }}">@lang('front_settings.home_slider')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_home_slider))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'home-slider-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_home_slider->id }}" name="id">
                        @else
                            @if (userPermission('home-slider-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'home-slider-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if (isset($add_home_slider))
                                        @lang('front_settings.edit_home_slider')
                                    @else
                                        @lang('front_settings.home_slider')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.image') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                                    type="text" name="image"
                                                    placeholder="{{ isset($add_home_slider) ? (@$add_home_slider->image != '' ? getFilePath3(@$add_home_slider->image) : trans('common.image') . ' *') : trans('common.image') . ' *' }}"
                                                    id="placeholderFileOneName" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="addHomeSliderImage">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="image"
                                                        id="addHomeSliderImage">
                                                </button>
                                            </div>
                                            @if ($errors->has('image'))
                                                <span class="text-danger">
                                                    {{ $errors->first('image') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <img class="previewImageSize {{ @$add_home_slider->image ? '' : 'd-none' }}" src="{{ @$add_home_slider->image ? asset($add_home_slider->image) : '' }}" alt="" id="homeSliderImageShow" height="100%" width="100%">
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.link')</label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('link') ? ' is-invalid' : '' }}"
                                                type="text" name="link" autocomplete="off"
                                                value="{{ isset($add_home_slider) ? @$add_home_slider->link : old('link') }}">
                                            @if ($errors->has('link'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('link') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        @if(config('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.add')</button></span>
                                        @else
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                @if (isset($add_home_slider))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.add')
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

            <div class="col-lg-9">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('front_settings.home_slider_list')</h3>
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
                                            <th>@lang('front_settings.image')</th>
                                            <th>@lang('front_settings.link')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($homeSliders as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><img src="{{ asset(@$value->image) }}" width="70px" height="50px">
                                                </td>
                                                <td class="text-break">{{ @$value->link }}</td>
                                                <td>
                                                    <x-drop-down>
                                                        <a class="dropdown-item"
                                                            href="{{ route('home-slider-edit', @$value->id) }}">@lang('common.edit')</a>
                                                        <a href="{{ route('home-slider-delete-modal', @$value->id) }}"
                                                            class="dropdown-item small fix-gr-bg modalLink"
                                                            title="@lang('front_settings.delete_home_slider')" data-modal-size="modal-md">
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
@section('script')
    <script>
        $(document).on('change', '#addHomeSliderImage', function(event) {
            $('#homeSliderImageShow').removeClass('d-none');
            getFileName($(this).val(), '#placeholderFileOneName');
            imageChangeWithFile($(this)[0], '#homeSliderImageShow');
        });
    </script>
@endsection
