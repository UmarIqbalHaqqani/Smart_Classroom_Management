<nav class="heading_main_menu">
    <ul>
        @foreach ($menus as $menu)
            @if (count($menu->childs) > 0)
                <li class="heading_main_menu_list">
                    <a href="" class="heading_main_menu_list_link">
                        {{ $menu->title }}
                    </a>
                    <ul class="heading_main_menu_list_dropdown">
                        @foreach ($menu->childs as $key => $sub_menu)
                            <li class="{{ $sub_menu->show == 1 ? 'menu_list_left' : '' }}">
                                <a {{ $sub_menu->is_newtab ? 'target="_blank"' : '' }}
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
                                        @if(count($sub_menu->childs) > 0)
                                            <i class="fa fa-caret-right"></i>
                                        @endif
                                    @endif
                                    
                                </a>
                                
                                @if (count($sub_menu->childs) > 0)
                                    <ul>
                                        @foreach ($sub_menu->childs as $key => $child_sub_menu)
                                            <li>
                                                <a {{ $child_sub_menu->is_newtab ? 'target="_blank"' : '' }}
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
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="heading_main_menu_list">
                    <a class="heading_main_menu_list_link" {{ $menu->is_newtab ? 'target="_blank"' : '' }}
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
    </ul>
</nav>