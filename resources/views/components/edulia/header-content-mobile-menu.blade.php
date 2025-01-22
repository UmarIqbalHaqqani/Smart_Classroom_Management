@php
    $generalSetting = generalSetting();
    $is_registration_permission = false;
    if (moduleStatusCheck('ParentRegistration')) {
        $reg_setting = Modules\ParentRegistration\Entities\SmRegistrationSetting::where('school_id', $generalSetting->school_id)->first();
        $is_registration_position = $reg_setting ? $reg_setting->position : null;
        $is_registration_permission = $reg_setting ? $reg_setting->registration_permission == 1 : false;
    }
@endphp

<div class="heading_mobile_menu_top">
    <ul>
        <li>
            <a href="{{ route('admin-dashboard') }}" class='text-primary'>
                @if (!auth()->check())
                    @lang('common.login')
                @else
                    @lang('edulia.dashboard')
                @endif
            </a>
        </li>
    </ul>
</div>
<ul>
    @foreach ($menus as $menu)
        @if (count($menu->childs) > 0)
            <li class="has-submenu">
                <a href="#" data-submenu="pages_{{$menu->id}}">
                    {{ $menu->title }}
                    <i class="ti-angle-right"></i>
                </a>
                <div id="pages_{{$menu->id}}" class="submenu">
                    <div class="submenu-header">
                        <a href="#" data-submenu-close="pages_{{$menu->id}}">
                            <i class='ti ti-angle-left'></i>
                            {{ $menu->title }}
                        </a>
                    </div>
                    <ul>
                        @foreach ($menu->childs as $key => $sub_menu)
                            <li class="has-submenu">
                                <a @if(count($sub_menu->childs) > 0) data-submenu="Events_{{$sub_menu->id}}" @endif {{ $sub_menu->is_newtab ? 'target="_blank"' : '' }}
                                    @if ($sub_menu->type == 'dPages') 
                                        href="{{ route('view-page', $sub_menu->link) }}" 
                                    @endif
                                    @if ($sub_menu->type == 'sPages') 
                                        @if ($sub_menu->link == '/login')
                                            @if (!auth()->check())
                                                href="{{ url('/') }}{{ $sub_menu->link }}"
                                            @else
                                                href="{{ url('/') }}{{ $sub_menu->link }}" 
                                            @endif
                                        @else
                                            href="{{ url('/') }}{{ $sub_menu->link }}"
                                        @endif
                                    @endif
                                    @if ($sub_menu->type == 'dCourse') href="{{ route('course-Details', $sub_menu->element_id) }}" @endif
                                    @if ($sub_menu->type == 'dCourseCategory') href="{{ route('view-course-category', $sub_menu->element_id) }}" @endif
                                    @if ($sub_menu->type == 'dNewsCategory') href="{{ route('view-news-category', $sub_menu->element_id) }}" @endif
                                    @if ($sub_menu->type == 'dNews') href="{{ route('news-Details', $sub_menu->element_id) }}" @endif
                                    @if ($sub_menu->type == 'customLink') href="{{ $sub_menu->link }}" @endif
                                    >
                                    @if ($sub_menu->link == '/login')
                                        @if (!auth()->check())
                                            {{ $sub_menu->title }}
                                        @else
                                            @lang('edulia.dashboard')
                                        @endif
                                    @else
                                        {{ $sub_menu->title }}
                                    @endif 
                                    @if (count($sub_menu->childs) > 0)
                                        <i class='ti ti-angle-right'></i>
                                    @endif
                                </a>
                                @if (count($sub_menu->childs) > 0)
                                    <div id="Events_{{$sub_menu->id}}" class="submenu">
                                        <div class="submenu-header">
                                            <a href="#" data-submenu-close="Events_{{$sub_menu->id}}"><i class='ti ti-angle-left'></i>{{$sub_menu->title}}</a>
                                        </div>
                                        <ul>
                                            @foreach ($sub_menu->childs as $key => $child_sub_menu)
                                                <li>
                                                    <a  {{ $child_sub_menu->is_newtab ? 'target="_blank"' : '' }}
                                                        @if ($child_sub_menu->type == 'dPages') 
                                                            href="{{ route('view-page', $child_sub_menu->link) }}" 
                                                        @endif
                                                        @if ($child_sub_menu->type == 'sPages') 
                                                            @if ($child_sub_menu->link == '/login')
                                                                @if (!auth()->check())
                                                                    href="{{ url('/') }}{{ $child_sub_menu->link }}"
                                                                @else
                                                                    href="{{ url('/') }}{{ $child_sub_menu->link }}" 
                                                                @endif
                                                            @else
                                                                href="{{ url('/') }}{{ $child_sub_menu->link }}"
                                                            @endif
                                                        @endif
                                                        @if ($child_sub_menu->type == 'dCourse') href="{{ route('course-Details', $child_sub_menu->element_id) }}" @endif
                                                        @if ($child_sub_menu->type == 'dCourseCategory') href="{{ route('view-course-category', $child_sub_menu->element_id) }}" @endif
                                                        @if ($child_sub_menu->type == 'dNewsCategory') href="{{ route('view-news-category', $child_sub_menu->element_id) }}" @endif
                                                        @if ($child_sub_menu->type == 'dNews') href="{{ route('news-Details', $child_sub_menu->element_id) }}" @endif
                                                        @if ($child_sub_menu->type == 'customLink') href="{{ $child_sub_menu->link }}" @endif
                                                        >
                                                        @if ($child_sub_menu->link == '/login')
                                                            @if (!auth()->check())
                                                                {{ $child_sub_menu->title }}
                                                            @else
                                                                @lang('edulia.dashboard')
                                                            @endif
                                                        @else
                                                            {{ $child_sub_menu->title }}
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @else
            <li class='has-submenu'>
                <a {{ $menu->is_newtab ? 'target="_blank"' : '' }}
                    @if ($menu->type == 'dPages') href="{{ route('view-page', $menu->link) }}" @endif
                        @if ($menu->type == 'sPages') @if ($menu->link == '/login')
                            @if (!auth()->check())
                            href="{{ url('/') }}{{ $menu->link }}"
                            @else
                            href="{{ url('/admin-dashboard') }}" @endif
                    @else
                        href="{{ url('/') }}{{ $menu->link }}"
                        @endif
                        @endif
                        @if ($menu->type == 'dCourse') href="{{ route('course-Details', $menu->element_id) }}" @endif
                        @if ($menu->type == 'dCourseCategory') href="{{ route('view-course-category', $menu->element_id) }}" @endif
                        @if ($menu->type == 'dNewsCategory') href="{{ route('view-news-category', $menu->element_id) }}" @endif
                        @if ($menu->type == 'dNews') href="{{ route('news-Details', $menu->element_id) }}" @endif
                        @if ($menu->type == 'customLink') href="{{ $menu->link }}" @endif
                    >
                    @if ($menu->link == '/login')
                        @if (!auth()->check())
                            {{ $menu->title }}
                        @else
                            @lang('edulia.dashboard')
                        @endif
                    @else
                        {{ $menu->title }}
                    @endif
                </a>
            </li>
        @endif
    @endforeach
    @if (moduleStatusCheck('ParentRegistration') && $is_registration_permission && $is_registration_permission == 1)
        @if (pagesetting('header-parent-registration-is-show-menu') == 1)
            <li class='has-submenu'>
                <a href="{{ route('parentregistration/registration', $reg_setting->url) }}"> {{ __('edulia.student_registration')}} </a>
            </li>
        @endif
    @endif

    @if (moduleStatusCheck('Saas') && session('domain') == 'school')
        @if (pagesetting('header-school-is-show-menu') == 1)
            <li class='has-submenu'>
                <a href="{{ route('institution-register-new') }}"> {{ pagesetting('header-school-menu-label') }} </a>
            </li>
        @endif
    @endif
</ul>