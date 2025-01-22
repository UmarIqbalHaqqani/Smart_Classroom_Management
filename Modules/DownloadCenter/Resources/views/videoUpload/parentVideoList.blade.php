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
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ $student_detail->full_name }} - @lang('downloadCenter.video_list') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('downloadCenter.download_center')</a>
                    <a href="{{ route('download-center.video-list') }}">@lang('downloadCenter.video_list')</a>
                </div>
            </div>
        </div>
    </section>
    @if (isset($videos))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-12 student-details up_admin_visitor">
                        <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">
                            @foreach ($records as $key => $record)
                                <li class="nav-item">
                                    @if (moduleStatusCheck('University'))
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->semesterLabel->name }} (
                                            {{ $record->unSemester->name }} - {{ $record->unAcademic->name }} ) </a>
                                    @else
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->class->class_name }}
                                            ({{ $record->section->section_name }})
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($records as $key => $record)
                                <div role="tabpanel"
                                    class="tab-pane fade @if ($key == 0) active show @endif"
                                    id="tab{{ $key }}">
                                    <div class="container-fluid p-0">
                                        <div class="row mt-40">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12 white-box mt-10">
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
                                                                                </ul>
                                                                            </div>
                                                                            <div class="vidoe-title">
                                                                                {{ $video->title }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
