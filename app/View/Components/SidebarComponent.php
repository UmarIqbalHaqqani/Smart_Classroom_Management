<?php

namespace App\View\Components;

use App\SmParent;
use Illuminate\View\Component;
use App\Traits\SidebarDataStore;
use Illuminate\Support\Facades\Cache;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;
use Modules\MenuManage\Entities\AlternativeModule;
use Modules\MenuManage\Entities\PermissionSection;
use Modules\RolePermission\Entities\AssignPermission;

class SidebarComponent extends Component
{
    use SidebarDataStore;
    public function __construct()
    {
        //
    }

    public function render()
    {
        $data = [];
        // deActivePermissions();
        // $this->modulePermissionSidebar();
        $this->defaultSidebarStore();
        $this->modulePermissionSidebar();     
        $this->deActiveForPgsql();
        $this->deActiveForSaas();
        session()->put('role_permission_user_type', '');

        $data['sidebar_menus'] = sidebar_menus();

        $data['paid_modules'] = $this->allActivePaidModules();
       
        $data['childrens'] = SmParent::myChildrens();

        return view('components.sidebar-component', $data);
    }
}
