@extends('backEnd.master')
@push('css')
    <style>
        .notification_pagination_container.notification_list ul {
            gap: 10px;
            display: none;
        }

        .notification_pagination_container.notification_list ul li a {
            height: 32px;
            width: 32px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #828bb2;
        }

        .notification_pagination_container.notification_list ul li a:hover {
            background: var(--primary-color);
            color: var(--white);
        }

        .notification_pagination_container.notification_list ul li a.current {
            background: var(--primary-color);
            color: var(--white);
        }

        .notification_pagination_container.notification_list ul li a.current i {
            color: var(--white);
        }
    </style>
@endpush
@section('title')
    @lang('common.notifications')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.notifications')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.notifications')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="white-box">
                        <div class="main-title">
                            <h3 class="mb-15">@lang('common.notification_list')</h3>
                        </div>
                        <div class="notification_container">
                            @foreach ($notifications as $notification)
                                <div class="single_notification">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <div class="notification_time"><i class="fas fa-calendar-alt"></i>
                                                <span>
                                                    {{ formatedDate($notification->created_at) }}
                                                </span>
                                            </div>
                                            <div class="notification_description">{{ $notification->message }}</div>
                                            @if($notification->url)
                                            <a href="{{ @$notification->url }}"
                                                class="notification_view_details">@lang('common.view_details')</a>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('view-single-notification', $notification->id) }}"
                                                class="dissmiss_single_notification">
                                                <svg width="25"
                                                    height="25" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="notification_close_icon">
                                                    <circle opacity="0.5" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="1.5"></circle>
                                                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5"
                                                        stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $notifications->onEachSide(1)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
