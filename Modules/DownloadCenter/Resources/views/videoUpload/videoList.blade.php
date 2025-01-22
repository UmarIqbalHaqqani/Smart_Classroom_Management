@extends('backEnd.master')
@section('title')
    @lang('downloadCenter.video_list')
@endsection
@push('css')
    <style>
        .vidoe-list {
            row-gap: 30px;
        }

        .single-video-item {
            height: 200px;
            width: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            padding: 20px;
            position: relative;
        }

        .single-video-item::before {
            content: '';
            background: rgba(0, 0, 0, 0.4);
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            transition: all 0.4s ease 0s;
            opacity: 0.2;
        }

        .single-video-item:hover::before {
            opacity: 1;
        }

        .single-video-item:hover .video-action-btns {
            display: block;
        }

        .single-video-info {
            position: absolute;
            bottom: 25px;
            z-index: 2;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            width: 90%;
            left: 5%;
            right: 5%;
        }

        .video-action-btns {
            display: none;
        }

        .video-action-btns ul {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .single-video-item .vidoe-title {
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            font-family: inherit;
            text-align: center;
        }

        .video-action-btns ul li a i {
            color: #ffffff;
            transition: 0.4s all ease-in-out;
            font-size: 15px;
        }

        .video-action-btns ul li a:hover i {
            transform: scale(1.2)
        }
        div#showDetaildModalBody iframe {
            width: 100%;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('downloadCenter.video') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('downloadCenter.download_center')</a>
                    <a href="{{ route('download-center.video-list') }}">@lang('downloadCenter.video')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (auth()->user()->role_id != 2)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box filter_card">
                            <div class="row">
                                <div class="col-lg-8 col-md-6 col-sm-6 col-6">
                                    <div class="main-title mt_0_sm mt_0_md">
                                        <h3 class="mb-15">@lang('common.search')</h3>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-md-right col-md-6 mb-30-lg col-6 text-right">
                                    <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button"
                                        data-toggle="modal" data-target="#addVideo">
                                        <span class="ti-plus pr-2"></span>
                                        @lang('common.add')
                                    </button>
                                </div>
                            </div>
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.video-list-search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                @include('backEnd.common.class_section_subject', [
                                    'div' => 'col-lg-4',
                                    'visiable' => ['class', 'section'],
                                ])
                                <div class="col-lg-4">
                                    <div class="primary_input sm_mb_20 ">
                                        <label class="primary_input_label" for="">
                                            @lang('downloadCenter.title')
                                        </label>
                                        <input class="primary_input_field" type="text" placeholder="@lang('downloadCenter.title')"
                                            name="title">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-right mt-20">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 white-box mt-10">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-15">@lang('downloadCenter.video_list')</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row vidoe-list">
                                @foreach ($videos as $video)
                                    @php
                                        $variable = substr($video->youtube_link, 32, 11);
                                    @endphp
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="single-video-item"
                                            style="background-image: url(http://img.youtube.com/vi/{{ $variable }}/hqdefault.jpg);">
                                            <div class="single-video-info">
                                                <div class="video-action-btns">
                                                    <ul>
                                                        <li>
                                                            <a class="modalLink"
                                                                data-modal-size="large-modal"
                                                                title="@lang('downloadCenter.view_video')"
                                                                href="{{ route('download-center.video-list-view-modal', [$video->id]) }}">
                                                                <i class="fas fa-bars"></i>
                                                            </a>
                                                        </li>
                                                        @if (auth()->user()->role_id != 2)
                                                            <li>
                                                                <a class="modalLink"
                                                                    data-modal-size="large-modal"
                                                                    title="@lang('downloadCenter.edit_video')"
                                                                    href="{{ route('download-center.video-list-edit-modal', [$video->id]) }}">
                                                                    <i class="fas fa-pen"></i>
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->role_id != 2)
                                                            <li>
                                                                <a data-toggle="modal"
                                                                    data-target="#deleteModal{{ @$video->id }}"
                                                                    href="#">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="vidoe-title">
                                                    {{ $video->title }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Start Delete Modal --}}
                                    <div class="modal fade admin-query"
                                        id="deleteModal{{ @$video->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">
                                                        @lang('downloadCenter.delete_video')
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        &times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">
                                                            @lang('common.cancel')
                                                        </button>
                                                        <a href="{{ route('download-center.video-list-delete', [@$video->id]) }}"
                                                            class="text-light">
                                                            <button class="primary-btn fix-gr-bg" type="submit">
                                                                @lang('common.delete')
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- End Delete Modal --}}
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start Video Add Modal -->
    <div class="modal fade admin-query" id="addVideo">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('downloadCenter.add_video')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.video-list-save', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    @include('backEnd.common.search_criteria', [
                                        'div' => 'col-lg-6',
                                        'required' => ['class', 'section'],
                                        'visiable' => ['class', 'section'],
                                    ])
                                    <div class="col-lg-6 mt-30">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.title')<span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field read-only-input form-control form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text"
                                                name="title" id="title">
                                            <span class="text-danger" id="titleError">
                                            </span>
                                        </div>
                                        @if ($errors->has('title'))
                                            <span class="text-danger">
                                                {{ $errors->first('title') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-lg-6 mt-30">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.video_link')<span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field read-only-input form-control form-control{{ $errors->has('video_link') ? ' is-invalid' : '' }}"
                                                type="text"
                                                name="video_link" id="video_link">
                                            <span class="text-danger" id="videoLinkError">
                                            </span>
                                        </div>
                                        @if ($errors->has('video_link'))
                                            <span class="text-danger">
                                                {{ $errors->first('video_link') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-25">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label"
                                                for="">@lang('downloadCenter.description')<span></span> </label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="3"
                                                name="description" id="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center mt-25">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('downloadCenter.cancel')</button>
                                    <button class="primary-btn fix-gr-bg submit" id="save_button_query"
                                        type="submit">@lang('downloadCenter.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- End Video Add Modal -->
@endsection
