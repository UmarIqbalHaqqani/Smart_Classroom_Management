@props(['sidebar_menus' => null, 'level' => 0, 'parent' => null])
@if($sidebar_menus)

    @foreach ($sidebar_menus as $sidebar_menu)

        @if($sidebar_menu->subModule->count() > 0 && sidebarPermission($sidebar_menu->permissionInfo))
            @if($level == 0 && $sidebar_menu->permissionInfo->name)
                    <span class="menu_seperator" id="seperator_{{ $sidebar_menu->permissionInfo->route }}" data-section="{{ $sidebar_menu->permissionInfo->route }}">{{ $sidebar_menu->permissionInfo->name }} </span>
            @endif

            @if($level > 0)


            @endif

            <x-menu-item :sidebar_menus="$sidebar_menu->subModule" :level="$level+1" :parent="$sidebar_menu" />

        @endif


    @endforeach
@endif