@extends('backEnd.master')

@section('title')
    {{ $menu_item->title }}
@endsection

@push('css')
    <style>
        .iframe-container {
            position: relative;
            height: 0;
            overflow: hidden;
            max-width: 100%;
            background: #000;
            padding-bottom: 0;
            height: 70vh;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        iframe {
            max-width: 100%;
        }
    </style>
@endpush

@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{ @$menu_item->title }}</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.custom_menu')</a>
                <a href="#">{{ @$menu_item->title }}</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor mt-25">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            {{-- <div class="main-title">
                                <h3 class="mb-15">{{ $menu_item->title }}</h3>
                            </div> --}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 my-3">
                            @if ($menu_item->menu_type == 'iframe')
                                @php
                                    $iframe =  trim($menu_item->iframe_src);
                                @endphp
                                <div class="iframe-container">
                                    <iframe src="{{ $iframe }}" height="100%" width="100%" frameborder="0" allowfullscreen></iframe>                                
                                </div>
                            @elseif($menu_item->menu_type == 'content')
                                <div> 
                                    {!! $menu_item->content !!}             
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection