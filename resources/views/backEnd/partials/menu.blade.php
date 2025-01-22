@php
    $generalSetting = generalSetting();
    $languages = systemLanguage();
    $styles = userColorThemes(auth()->user()->id);

@endphp

@php
    $coltroller_role = 1;
@endphp

<style>
    .fas.fa-robot.menu-only {
        font-size: 20px;
        color: #828bb2;
        margin-right: 5px;
    }

    a.pulse.theme_color.bell_notification_clicker {
        margin-right: 15px !important;
    }

    @media (min-width: 1350px) {
        .header_middle {
            display: block !important;
        }
    }
    button#mobileSelectorDropdown {
        background: transparent;
        border: 0;
    }
    button#mobileSelectorDropdown svg {
        color: #2f2f3bad;
    }
    button#mobileSelectorDropdown + .dropdown-menu{
        background: var(--text_white);
        box-shadow: 0 9px 20px rgba(46, 35, 94, .07);
        border: 0;
        padding: 16px 0!important;
    }
    .dropdown.open .dropdown-menu {
        display: block;
    }

    .infix_session.open .nice_Select, 
    .languageChange.open .nice_Select {
        display: block;
    }
    button#mobileSelectorDropdown + .dropdown-menu .list,
    button#mobileSelectorDropdown + .dropdown-menu .nice-select-search-box,
    button#mobileSelectorDropdown + .dropdown-menu .nice-select{
        min-width: 100%;
        width: 100%!important;
    }

    button#mobileSelectorDropdown + .dropdown-menu .infix_session,
    button#mobileSelectorDropdown + .dropdown-menu .languageChange {
        padding: 0 16px;
    }

    button#mobileSelectorDropdown + .dropdown-menu .infix_session:hover,
    button#mobileSelectorDropdown + .dropdown-menu .languageChange:hover {
        background: #f6f6f6;
    }
</style>
<div class="container-fluid no-gutters" id="main-nav-for-chat">
    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="header_iner d-flex justify-content-between align-items-center">
                <div class="small_logo_crm d-lg-none">
                    <a href="#">
                        @if (!is_null($generalSetting->logo))
                            <img src="{{ asset($generalSetting->logo) }}" alt="logo">
                        @else
                            <img src="{{ asset('public/uploads/settings/logo.png') }}" alt="logo">
                        @endif
                    </a>
                </div>
                <div id="sidebarCollapse" class="sidebar_icon  d-lg-none">
                    <i class="ti-menu"></i>
                </div>
                <div class="collaspe_icon open_miniSide">
                    <i class="ti-menu"></i>
                </div>

                <div class="serach_field-area ml-40">
                    <div class="search_inner">
                        <form action="#">
                            <div class="search_field">
                                <input type="text" class="form-control primary_input_field input-left-icon"
                                    placeholder="Search" id="search" onkeyup="showResult(this.value)">
                            </div>
                            <button type="submit" style="padding-top: 3px"><i
                                    style="font-size: 13px; padding-left: 13px;" class="ti-search"></i></button>
                        </form>
                    </div>
                    <div id="livesearch" style="display: none;"></div>
                </div>
                <div class="header_right d-flex justify-content-between align-items-center">
                    <div class="serach_field-area mr-10">
                        <div class="search_inner">
                            <div class="search_field">
                                <input type="text" class="form-control primary_input_field input-left-icon"
                                    placeholder="Name/Admission No." id="searchStudent"
                                    onkeyup="showStudent(this.value)">
                            </div>
                            <button type="submit" style="padding-top: 3px"><i
                                    style="font-size: 13px; padding-left: 13px;" class="ti-search"></i></button>
                        </div>
                        <div id="liveStudentSearch" style="display: none;"></div>
                    </div>
                    <select name="#" class="nice_Select bgLess mb-0 infix_session" id="infix_session">
                        @foreach (academicYears() as $academic_year)
                            @if (moduleStatusCheck('University'))
                                <option value="{{ @$academic_year->id }}"
                                    {{ getAcademicId() == @$academic_year->id ? 'selected' : '' }}>
                                    {{ @$academic_year->name }} </option>
                            @else
                                <option value="{{ @$academic_year->id }}"
                                    {{ getAcademicId() == @$academic_year->id ? 'selected' : '' }}>
                                    {{ @$academic_year->year }} [{{ @$academic_year->title }}]
                                </option>
                            @endif
                        @endforeach
                    </select>

                    {{-- @if (@$styles && Auth::user()->role_id == 1)
                        @if (generalSetting()->style_btn == 1)
                            <select class="nice_Select bgLess mb-0 infix_theme_style" id="infix_theme_style">
                                <option data-display="@lang('common.select_style')"
                                    value="0">@lang('common.select_style')
                                </option>
                                @foreach ($styles as $style)
                                    <option value="{{ $style->id }}"
                                        {{ color_theme()->id == $style->id ? 'selected' : '' }}>
                                        {{ $style->title }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    @endif --}}
                    @if (generalSetting()->lang_btn == 1)
                        <select class="nice_Select bgLess mb-0 languageChange" id="languageChange">
                            <option data-display="@lang('common.select_language')"
                                value="0">@lang('common.select_language')
                            </option>
                            @foreach ($languages as $lang)
                                <option data-display="{{ $lang->language_universal }}" value="{{ $lang->language_universal }}"
                                    {{ $lang->language_universal == userLanguage() ? 'selected' : '' }}>
                                    {{ $lang->native }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (moduleStatusCheck('AiContent'))
                        @include('aicontent::inc.menu_btn')
                    @endif

                    <!-- Mobile view Dropdown for selection Academic Year and Language : Start -->

                    <div class="dropdown d-sm-none">
                        <button class="" type="button" id="mobileSelectorDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="5" r="2"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                            <circle cx="12" cy="19" r="2"></circle>
                        </svg>

                        </button>
                        <div class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                        <select name="#" class="nice_Select bgLess mb-0 infix_session" id="infix_session_mbl">
                            @foreach (academicYears() as $academic_year)
                                @if (moduleStatusCheck('University'))
                                    <option value="{{ @$academic_year->id }}"
                                        {{ getAcademicId() == @$academic_year->id ? 'selected' : '' }}>
                                        {{ @$academic_year->name }} </option>
                                @else
                                    <option value="{{ @$academic_year->id }}"
                                        {{ getAcademicId() == @$academic_year->id ? 'selected' : '' }}>
                                        {{ @$academic_year->year }} [{{ @$academic_year->title }}]
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        @if (generalSetting()->lang_btn == 1)
                            <select class="nice_Select bgLess mb-0 languageChange" id="languageChange_mbl">
                                <option data-display="@lang('common.select_language')"
                                    value="0">@lang('common.select_language')
                                </option>
                                @foreach ($languages as $lang)
                                    <option data-display="{{ $lang->language_universal }}" value="{{ $lang->language_universal }}"
                                        {{ $lang->language_universal == userLanguage() ? 'selected' : '' }}>
                                        {{ $lang->native }}</option>
                                @endforeach
                            </select>
                        @endif
                        </div>
                    </div>

                    <!-- Mobile view Dropdown for selection Academic Year and Language : END -->

                    <ul class="header_notification_warp d-flex align-items-center">

                        @if (generalSetting()->chatting_method !== null || generalSetting()->chatting_method == 'log')
                            <jquery-notification-component
                                :unreads="{{ json_encode($notifications_for_chat) }}"
                                :user_id="{{ json_encode(auth()->id()) }}"
                                :redirect_url="{{ json_encode(route('chat.index')) }}"
                                :check_new_notification_url="{{ json_encode(route('chat.notification.check')) }}"
                                :asset_type="{{ json_encode(asset('/public')) }}"
                                :mark_all_as_read_url="{{ json_encode(route('chat.notification.allRead')) }}">
                            </jquery-notification-component>
                        @else
                            <notification-component
                                :unreads="{{ json_encode($notifications_for_chat) }}"
                                :user_id="{{ json_encode(auth()->id()) }}"
                                :redirect_url="{{ json_encode(route('chat.index')) }}"
                                :asset_type="{{ json_encode(asset('/public')) }}"
                                :mark_all_as_read_url="{{ json_encode(route('chat.notification.allRead')) }}">
                            </notification-component>
                        @endif
                        {{-- Start Notification --}}
                        <li class="scroll_notification_list">
                            <a class="pulse theme_color bell_notification_clicker show_notifications" href="#">
                                <!-- bell   -->
                                <img src="{{asset('public/backEnd/assets/img/icons/notification.svg')}}" alt="">

                                <!--/ bell   -->
                                <span
                                    class="notificationCount notification_count">{{ count($notifications ?? []) }}</span>
                                <span class="pulse-ring notification_count_pulse"></span>
                            </a>
                            <!-- Menu_NOtification_Wrap  -->
                            <div class="Menu_NOtification_Wrap notifications_wrap">
                                <div class="notification_Header">
                                    <h4>{{ __('common.no_unread_notification') }}</h4>
                                </div>
                                <div class="Notification_body">
                                    <!-- single_notify  -->
                                    @forelse ($notifications as $notification)
                                        <div class="single_notify d-flex align-items-center"
                                            id="menu_notification_show_{{ $notification->id }}">
                                            <div class="notify_thumb">
                                                <i class="fa fa-bell"></i>
                                            </div>
                                            <a href="#" class="unread_notification flex-grow-1" title="Mark As Read"
                                                data-notification_id="{{ $notification->id }}">
                                                <div class="notify_content">
                                                    <p class="notification_title">{!! strip_tags(\Illuminate\Support\Str::limit(@$notification->message, 70, $end = '...')) !!}</p>
                                                </div>
                                            </a>
                                            <h5 class="notification_time text-nowrap">{{ formatedDate($notification->created_at) }}</h5>
                                            <a href="{{ route('view-single-notification', $notification->id) }}">
                                                <svg width="20" height="20" class="notification_close_icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
                                                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @empty
                                        <span class="text-center">{{ __('common.no_unread_notification') }}</span>
                                    @endforelse

                                </div>
                                <div class="nofity_footer">
                                    <div class="submit_button text-center pt_20">
                                        <a href="{{ route('view/all/notification', Auth()->user()->id) }}"
                                            class="primary-btn radius_30px text_white  fix-gr-bg mark-all-as-read">{{ __('common.mark_all_as_read') }}</a>
                                        <a href="{{ route('all-notification') }}"
                                            class="primary-btn radius_30px text_white  fix-gr-bg see_all_notification">{{ __('common.see_more') }}</a>
                                    </div>
                                </div>
                            </div>
                            <!--/ Menu_NOtification_Wrap  -->
                        </li>
                        {{-- End Notification --}}


                    </ul>

                    <div class="redirect_links">
                        <div class="select_style d-flex">
                            @if (generalSetting()->website_btn == 1)
                                <a target="_blank" class=" mr-10 tab_hide"
                                    href="{{ url('/') }}"><img src="{{asset('public/backEnd/assets/img/icons/globe.svg')}}" alt=""></a>
                            @endif
                            {{-- @if (generalSetting()->dashboard_btn == 1)
                                @if (Auth::user()->role_id == $coltroller_role)
                                    <a class="primary-btn white mr-10 tab_hide"
                                        href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                                @endif
                            @endif --}}
                            @if (generalSetting()->report_btn == 1)
                                @if (Auth::user()->role_id == $coltroller_role)
                                    <a class="mr-10 tab_hide"
                                        href="{{ route('student_report') }}"><img src="{{asset('public/backEnd/assets/img/icons/report.svg')}}" alt=""></a>
                                @endif
                            @endif
                            {{-- <div class="border_1px tab_hide"></div> --}}
    
    
                        </div>
                    </div>

                    
                    <div class="profile_info">

                        <div class="user_avatar_div">
                            <img id="profile_pic"
                                src="{{ @profile() && file_exists(@profile()) ? asset(profile()) : asset('public/backEnd/assets/img/avatar.png') }}"
                                alt="">
                        </div>

                        <div class="profile_info_iner">
                            <p class="email"> {{ Auth::user()->email }}</p>
                            <h5>{{ Auth::user()->full_name }} @if (isset(Auth::user()->wallet_balance))
                                    @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                        <p class="message">
                                            <strong>
                                                @lang('common.balance'):
                                                {{ Auth::user()->wallet_balance != null ? currency_format(Auth::user()->wallet_balance) : currency_format(0.0) }}
                                            </strong>
                                        </p>
                                    @endif
                                @endif
                            </h5>
                            <div class="profile_info_details">
                                @if (Auth::user()->is_saas == 1)
                                    <a href="{{ route('saasStaffDashboard') }}">

                                        @lang('common.saas_dashboard')
                                        <span class="fa fa-home"></span>
                                    </a>
                                @endif
                                @if (Auth::user()->role_id == '2' && Auth::user()->is_saas == 0)
                                    <a href="{{ route('student-profile') }}">
                                        <img src="{{asset('public/backEnd/assets/img/icons/profile.svg')}}" class="mr-1" alt="">
                                        @lang('common.view_profile')
                                        {{-- <span class="ti-user"></span> --}}
                                    </a>
                                @elseif(Auth::user()->role_id != '3' && Auth::user()->is_saas == 0 && Auth::user()->staff)
                                    <a href="{{ route('viewStaff', Auth::user()->staff->id) }}">
                                        <img src="{{asset('public/backEnd/assets/img/icons/profile.svg')}}" alt="">
                                        @lang('common.view_profile')
                                        {{-- <span class="ti-user"></span> --}}
                                    </a>
                                @endif
                                @if (config('app.app_sync') && auth()->user()->role_id != 1)
                                    @if (auth()->user()->staff && auth()->user()->staff->parent_id && auth()->user()->role_id == 3)
                                        <a href="{{ route('viewAsRole') }}">

                                            <img src="{{asset('public/backEnd/assets/img/icons/key.svg')}}" alt="" class="mr-1">
                                            @lang('common.VIEW_AS_' . strtoupper(auth()->user()->staff->previousRole->name))
                                        </a>
                                    @elseif(auth()->user()->staff && auth()->user()->staff->parent_id)
                                        <a href="{{ route('viewAsParent') }}">

                                            <img src="{{asset('public/backEnd/assets/img/icons/key.svg')}}" alt="" class="mr-1">
                                            @lang('common.VIEW_AS_PARENT')
                                        </a>
                                    @endif
                                @endif
                                @if (moduleStatusCheck('Saas') == true &&
                                        Auth::user()->is_administrator == 'yes' &&
                                        Auth::user()->role_id == 1 &&
                                        Auth::user()->is_saas == 0)

                                    <a href="{{ route('viewAsSuperadmin') }}">
                                        <img src="{{asset('public/backEnd/assets/img/icons/key.svg')}}" alt="">

                                        @if (Session::get('isSchoolAdmin') == true)
                                            @lang('common.view_as_saas_admin')
                                        @else
                                            @lang('common.view_as_school_admin')
                                        @endif
                                    </a>
                                @endif
                                <a href="{{ route('updatePassowrd') }}">
                                    <img src="{{asset('public/backEnd/assets/img/icons/password.svg')}}" alt="">
                                    @lang('common.password')
                                    {{-- <span style="margin-left: 3px;" class="ti-key"></span> --}}
                                </a>


                                <a href="{{ Auth::user()->role_id == 2 ? route('student-logout') : route('logout') }}"
                                    onclick="event.preventDefault();

                                              document.getElementById('logout-form').submit();">
                                    <img src="{{asset('public/backEnd/assets/img/icons/logout.svg')}}" alt="">
                                    @lang('common.logout')
                                    {{-- <span class="ti-unlock"></span> --}}
                                </a>

                                <form id="logout-form"
                                    action="{{ Auth::user()->role_id == 2 ? route('student-logout') : route('logout') }}"
                                    method="POST" class="d-none">

                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (moduleStatusCheck('AiContent'))
    @include('aicontent::content_generator_modal')
@endif

@if (moduleStatusCheck('WhatsappSupport'))
    @include('whatsappsupport::partials._popup')
@endif

<style>
    .messengerContainer:hover {
        cursor: pointer;
    }
</style>
@if($messenger_position == 'right') 
    <style>
        .messengerContainer {
            position: fixed;
            bottom: 85px;
            right: 22px;
            width: 48px;
            height: 45px;
            border-radius: 50%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            z-index: 3;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
        }
    </style>
@elseif($messenger_position == 'left') 
    <style>
        .messengerContainer {
            position: fixed;
            bottom: 85px;
            width: 48px;
            height: 45px;
            left: 22px;
            border-radius: 50%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            z-index: 3;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
        }
    </style>
@endif

@php
    $school_id = @Auth::user()->school_id;
    $tawk_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'tawk')->first()->is_enable;
    $messenger_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'messenger')->first()->is_enable;
@endphp

@if ($tawk_is_enable == 1)
    <div class="tawk-min-container tawk-test">
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{},
            Tawk_LoadStart=new Date();
            (function(){ var s1=document.createElement("script"),
            s0=document.getElementsByTagName("script")[0];
            s1.async=true; s1.src=`https://embed.tawk.to/@include('plugins.tawk_to')`;
            s1.charset='UTF-8'; s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0); })();
        </script>
    </div>  
@endif

@if ($messenger_is_enable == 1)
    <div class="messengerContainer">
        <!-- Messenger Chat Plugin Code -->
        <div id="fb-root"></div>

        <!-- Your Chat Plugin code -->
        <div id="fb-customer-chat" class="fb-customerchat">
        </div>

        <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "@include('plugins.messenger')");
        chatbox.setAttribute("attribution", "biz_inbox");
        </script>

        <!-- Your SDK code -->
        <script>
        window.fbAsyncInit = function() {
            FB.init({
            xfbml            : true,
            version          : 'v18.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        </script>
    </div>
@endif

<script>
    var position = '{{ $position }}';
    var tawkposition = '';

    if(position == 'left'){
        tawkposition = 'bl';
    }else{
        tawkposition = 'br';
    }
	var Tawk_API = Tawk_API || {};

	Tawk_API.customStyle = {
		visibility : {
			desktop : {
				position : tawkposition,
				xOffset : 0,
				yOffset : 20
			},
			mobile : {
				position : tawkposition,
				xOffset : 0,
				yOffset : 0
			},
			bubble : {
				rotate : '0deg',
			 	xOffset : -20,
			 	yOffset : 0
			}
		}
	};

    $(document).ready(function () {

        $('#infix_session_mbl').on('change', function () {
            const selectedSession = $(this).val();
        });

        $('#languageChange_mbl').on('change', function () {
            const selectedLanguage = $(this).val();
        });

        $('#mobileSelectorDropdown').on('click', function () {
            $(this).closest('.dropdown').toggleClass('open');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown.open').removeClass('open');
            }
        });
    });

</script>
@section('script')
@endsection
