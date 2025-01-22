@pushonce(config('pagebuilder.site_style_var'))
<link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/packages/zeynep/zeynep.min.css')}}">
<link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/themify/themify-icons.min.css')}}">
@endpushonce
@php
    $generalSetting = generalSetting();
    $is_registration_permission = false;
    if (moduleStatusCheck('ParentRegistration')) {
        $reg_setting = Modules\ParentRegistration\Entities\SmRegistrationSetting::where('school_id', $generalSetting->school_id)->first();
        $is_registration_position = $reg_setting ? $reg_setting->position : null;
        $is_registration_permission = $reg_setting ? $reg_setting->registration_permission == 1 : false;
    }
@endphp
<header class="heading">
    <div class="heading_sub">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <nav class="heading_sub_left">
                        <ul>
                            @if (!empty(pagesetting('header-left-menus')))
                                @foreach(pagesetting('header-left-menus') as $rightMenu)
                                    <li>
                                        <a href="{{ gv($rightMenu, 'header-left-menu-icon-url') }}">
                                            <i class="{{gv($rightMenu, 'header-left-menu-icon-class')}}"></i>
                                            {{ gv($rightMenu, 'header-left-menu-label') }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>
                <div class="text-end">
                    <nav class="heading_sub_right">
                        <ul class='social-links'>
                            @if (!empty(pagesetting('header-right-menus')))
                                @foreach (pagesetting('header-right-menus') as $icon)
                                    <li class='social-links-list'>
                                        <a href="{{ gv($icon, 'header-right-icon-url') }}" target='_blank' class='social-links-list-link'>
                                            <i class="{{ gv($icon, 'header-right-icon-class') }}"></i>
                                            {{ gv($icon, 'header-right-menu-label') }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <ul>
                            <li>
                                @if (!auth()->check())
                                    <a href="{{url('/login')}}">
                                        <i class="far fa-user"></i>
                                        <span>{{ __('edulia.login')}}</span>
                                    </a>
                                @else
                                    <a href="{{url('/admin-dashboard')}}">
                                        <i class="far fa-user"></i>
                                        <span>{{ __('edulia.dashboard')}}</span>
                                    </a>
                                @endif
                            </li>

                            @if (moduleStatusCheck('ParentRegistration') && $is_registration_permission == 1) 
                                @if (pagesetting('header-parent-registration-is-show-menu') == 1)
                                    <li>
                                        <a href="{{ route('parentregistration/registration', $reg_setting->url) }}" target="{{ pagesetting('header-parent-registration-redirect-menu') == '1' ? '_self' : '_blank' }}">
                                            <i class="{{pagesetting('header-right-parent-registration-menu-icon-class')}}"></i>
                                            {{ pagesetting('header-parent-registration-menu-label') }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                        
                            @if (moduleStatusCheck('Saas') && session('domain') == 'school')
                                @if (pagesetting('header-school-is-show-menu') == 1)
                                    <li>
                                        <a href="{{ route('institution-register-new') }}" target="{{ pagesetting('header-school-redirect-menu') == '1' ? '_self' : '_blank' }}">
                                            <i class="{{pagesetting('header-school-menu-icon-class')}}"></i>
                                            {{ pagesetting('header-school-menu-label') }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="heading_mobile">
        <div>
            <div class="heading_mobile_thum"><i class="far fa-bars"></i></div>
        </div>
        <div class='text-center'>
            <a href='{{url('/')}}' class="heading_logo">
                <img src="{{pagesetting('header_menu_image') ? pagesetting('header_menu_image')[0]['thumbnail'] : defaultLogo($generalSetting->logo) }}" alt="">
            </a>
        </div>
        <form action="#" class='heading_main_search m_s'>
            <div class="input-control">
                <label for="search" class="input-control-icon"><i class="far fa-search"></i></label>
                <input type="search" name='search' id='search' class="input-control-input"
                    placeholder='Search for course, skills and Videos' required>
            </div>
        </form>
    </div>

    <div class="heading_main">
        <div class="container">
            <div class="row">
                <div class="col-md-2 my-auto">
                    <a href="{{url('/')}}" class="heading_main_logo mobile-menu-left">
                        <img src="{{pagesetting('header_menu_image') ? pagesetting('header_menu_image')[0]['thumbnail'] : defaultLogo($generalSetting->logo) }}" alt="">
                    </a>
                </div>
                <div class="col-md-7">
                    <x-header-content-menu></x-header-content-menu>
                </div>
                @if (!empty(pagesetting('header_menu_search')) && pagesetting('header_menu_search') == 1)
                    <div class="col-md-3 text-end mobile-none">
                        <form action='#' methods='GET' class="heading_main_search mb-0">
                            <div class="input-control">
                                <input type="search" class="input-control-input" placeholder='{{pagesetting('header_menu_search_placeholder')}}' required>
                                <button type='submit' class="input-control-icon"><i class="far fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
<div class="clear_head"></div>


<!-- mobile menu -->
<div class="heading_mobile_menu zeynep">
    <x-header-content-mobile-menu></x-header-content-mobile-menu>
</div>
<!-- mobile menu -->


@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/'.activeTheme().'/packages/zeynep/zeynep.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            // MOBILE MENU ACTIVE JS
            var zeynep = $('.zeynep').zeynep({})
            $('.heading_mobile_thum').on('click', function() {
                zeynep.open()
                $('.bg-shade').fadeIn();
            })
            $('.bg-shade').on('click', function() {
                zeynep.close()
                $('.bg-shade').fadeOut();
            })

            $('[data-mobile-search]').on('click', function(e) {
                e.stopPropagation();
                $('.m_s').fadeToggle('fast')
            });
            $(document).on('click', function(e) {
                if (!$(e.target).is('.m_s *')) {
                    $('.m_s').fadeOut('fast')
                }
            })
        });
    </script>
@endpushonce