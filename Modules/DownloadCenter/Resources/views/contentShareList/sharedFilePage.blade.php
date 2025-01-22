<!DOCTYPE html>
@php
    $generalSetting = generalSetting();
@endphp
<html lang="{{ app()->getLocale() }}" @if (userRtlLtl() == 1) dir="rtl" class="rtl" @endif>

<head>
    @if (!is_null(schoolConfig()))
        <link rel="icon" href="{{ asset(schoolConfig()->favicon) }}" type="image/png" />
    @else
        <link rel="icon" href="{{ asset('public/uploads/settings/favicon.png') }}" type="image/png" />
    @endif
    <title>{{ @schoolConfig()->school_name ? @schoolConfig()->school_name : 'Infix Edu ERP' }} |
        @lang('downloadCenter.shared_content')
    </title>
    <style>
        .attached-content-list {
            padding-left: 0
        }

        li.attached-content-item {
            border: 1px solid rgba(130, 139, 178, 0.3);
            padding: 10px 15px;
            margin-bottom: -1px;
            list-style: none;
        }

        li.attached-content-item:first-child {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        li.attached-content-item:last-child {
            margin-bottom: 0;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        li.attached-content-item:hover {
            background-color: #f5f4f4bf;
        }

        h2.section_title {
            color: #444;
            font-size: 25px;
            margin-top: 0;
            line-height: 50px;
            font-weight: 400;
            font-family: inherit;
            margin: 20px 0px;
        }

        .value_grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr)
        }

        .shared-link-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .shared-link-content-container {
            width: 50%;
            background: #fff;
            box-shadow: 0px 0px 12px rgb(0 0 0 / 29%);
            overflow: hidden;
            border-radius: 4px;
            padding: 1rem;
        }

        .attached-content-item a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="shared-link-container">
        <div class="shared-link-content-container">
            <div class="company_logo">
                <img src="{{  asset(schoolConfig()->logo ? schoolConfig()->logo : 'public/uploads/settings/logo.png') }}" alt="logo" style="max-width:180px">
            </div>
            <h2 class="section_title">{{ $sharedContent->title }}</h2>
            <div class="value_grid">
                <div class="grid_item">
                    <p><b class="value_type">@lang('downloadCenter.upload_date')</b> : <span
                            class="value">{{ date('Y-m-d', strtotime($sharedContent->share_date)) }}</span></p>
                </div>
                <div class="grid_item">
                    <p><b class="value_type">@lang('downloadCenter.valid_upto')</b> : <span
                            class="value">{{ date('Y-m-d', strtotime($sharedContent->valid_upto)) }}</span></p>
                </div>
                <div class="grid_item">
                    <p><b class="value_type">@lang('downloadCenter.uploaded_by')</b> : <span
                            class="value">{{ $sharedContent->user->full_name }}</span>
                    </p>
                </div>
            </div>
            <div class="attached-content">
                <ul class="attached-content-list">
                    @foreach ($contents as $content)
                        <li class="attached-content-item">
                            @if (@$content->youtube_link)
                                <a href="{{ $content->youtube_link }}"
                                    target="_blank">{{ $content->file_name }}</a>
                            @else
                                <a href="{{ url("$content->upload_file") }}"
                                    download>{{ $content->file_name }}
                                    <span class="pl ti-download"></span></a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</body>

</html>
