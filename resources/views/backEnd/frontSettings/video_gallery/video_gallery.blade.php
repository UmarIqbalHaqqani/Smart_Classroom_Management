@extends('backEnd.master')
@section('title')
    @lang('front_settings.video_gallery')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.video_gallery')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('video-gallery') }}">@lang('front_settings.video_gallery')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_video_gallery))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'video-gallery-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_video_gallery->id }}" name="id">
                        @else
                            @if (userPermission('video-gallery-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'video-gallery-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                        <div class="main-title">
                            <h3 class="mb-15">
                                @if (isset($add_video_gallery))
                                    @lang('front_settings.edit_video_gallery')
                                @else
                                    @lang('front_settings.video_gallery')
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
                                                value="{{ isset($add_video_gallery) ? @$add_video_gallery->name : old('name') }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.description') <span
                                                    class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0"
                                                rows="4" name="description" id="address">{{ isset($add_video_gallery) ? @$add_video_gallery->description : old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('description') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.video_link') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('video_link') ? ' is-invalid' : '' }}"
                                                type="text" name="video_link" autocomplete="off"
                                                value="{{ isset($add_video_gallery) ? @$add_video_gallery->video_link : old('video_link') }}">
                                            @if ($errors->has('video_link'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('video_link') }}
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
                                                @if (isset($add_video_gallery))
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

            <div class="col-lg-8">
                <div class="white-box">
                <div class="row">
                    <div class="col-xl-4 col-sm-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-15">@lang('front_settings.video_gallery_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table videoGallery" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('front_settings.name')</th>
                                        <th>@lang('front_settings.description')</th>
                                        <th>@lang('front_settings.thumbnail')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($videoGalleries as $key => $value)
                                        @php
                                            $variable = substr($value->video_link, 32, 11);
                                        @endphp
                                        <tr e_id="{{$value->id}}">
                                            <td><span class="mr-2" style="cursor: grab"><i class="ti-menu"></i></span>{{ $key + 1 }}</td>
                                            <td>{{ @$value->name }}</td>
                                            <td>{{ @$value->description }}</td>
                                            <td><img style="background-image: url(http://img.youtube.com/vi/{{ $variable }}/default.jpg);"
                                                    width="120px" height="90px">
                                            </td>
                                            <td>
                                                <x-drop-down>
                                                    <a href="{{ route('video-gallery-view-modal', @$value->id) }}"
                                                        class="dropdown-item small fix-gr-bg modalLink"
                                                        title="@lang('front_settings.view_video')" data-modal-size="modal-lg">
                                                        @lang('common.view')
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('video-gallery-edit', @$value->id) }}">@lang('common.edit')</a>
                                                    <a href="{{ route('video-gallery-delete-modal', @$value->id) }}"
                                                        class="dropdown-item small fix-gr-bg modalLink"
                                                        title="@lang('front_settings.delete_video_gallery')" data-modal-size="modal-md">
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
@push('script')
    <script type="text/javascript">
        datableArrange('.videoGallery', 'sm_video_galleries');
    </script>
@endpush