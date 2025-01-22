<?php

namespace Modules\MenuManage\Http\Controllers;

use App\GlobalVariable;
use Illuminate\Http\Request;
use App\Traits\SidebarDataStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\Permission;
use Modules\MenuManage\Entities\PermissionSection;
use Modules\RolePermission\Entities\AssignPermission;
use Modules\MenuManage\Http\Requests\SectionRequestFrom;

class SidebarManagerController extends Controller
{
    use SidebarDataStore;

    public function __construct(){

    }
    public function sectionStore(SectionRequestFrom $request)
    {

        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $permission_position = Permission::whereNotNull('permission_section')->when(is_role_based_sidebar(), function($q){
            $q->where('role_id', request('role_id'))->orWhere(function($q){
                $q->whereNull('role_id')->orWhereNull('user_id');
            });
        }, function($q){
            $q->where('user_id', auth()->user()->id);
        })->latest()->first();


            $role_id = is_role_based_sidebar() ? $request->role_id : auth()->user()->role_id;
            $position = ($permission_position? $permission_position->id: 0) + 1;

            $permission = Permission::create([
                'name' => $request->name,
                'route'=>strtolower($request->name),
                'position' => $position,
                'user_id' => is_role_based_sidebar() ? null : auth()->user()->id,
                'role_id' => is_role_based_sidebar() ? $request->role_id : null,
                'permission_section'=>1,
                'type'=>1,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'is_admin'=>!in_array($role_id, [2,3,GlobalVariable::isAlumni()]) ? 1 : 0,
                'is_student'=>$role_id == 2 ? 1 : 0,
                'is_parent'=>$role_id == 3 ? 1 : 0,
            ]);
            Sidebar::create([
                'permission_id' => $permission->id,
                'user_id' => is_role_based_sidebar() ? null : auth()->user()->id,
                'role_id' => $role_id,
                'position' => $position,
                'active_status' => 1,
                'parent'=>NULL
            ]);
            Cache::forget(sidebar_cache_key($role_id));

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('menumanage.index', ['role_id' => $role_id]);

    }
    public function sectionEditForm(Request $request, $id)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $data = [];
        $role_id = $request->role_id;
        $data['editPermissionSection'] = Permission::when(!is_role_based_sidebar(), function ($q) {
            $q->where('user_id', auth()->user()->id);
        }, function ($q) use ($role_id) {
            $q->where(function ($query) use ($role_id) {
                $query->where('role_id', $role_id)
                      ->orWhereNull('role_id');
            });
        })
        ->where('id', $id)
        ->first();
        
        $data['unused_menus'] = self::unUsedMenu($role_id);
        Cache::forget(sidebar_cache_key($role_id));
        $data['sidebar_menus'] = sidebar_menus($role_id);

        if($role_id){
            $data['role'] = InfixRole::find($role_id);
        }

        if($role_id){
            $view = 'menumanage::role_index';
        } else{
            $view = 'menumanage::index';
        }
        return view($view, $data);
    }

    public function sectionUpdate(SectionRequestFrom $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $section = Permission::find($request->id);
        $section->name = $request->name;
        $section->save();

        Toastr::success('Operation successful', 'Success');
        if($role_id = $section->role_id){
            $route = route('menumanage.index', ['role_id' => $role_id]);
        } else{
            $route = route('menumanage.index');
        }
        return redirect()->to($route);
    }
    public function deleteSection(Request $request)
    {


        if(config('app.app_sync')) {
            return $this->reloadWithData();
        }

        try {

            if ($request->id != 1) {
                $role_id = $request->role_id;
                $is_role_based_sidebar = is_role_based_sidebar();
                $section = Sidebar::with('subModule')->where('id', $request->id)->when(!$is_role_based_sidebar, function($q){
                    $q->where('user_id', auth()->user()->id);
                }, function ($q) use($role_id){
                    $q->where('role_id', $role_id);
                })->first();
                if (count($section->subModule)!=0) {

                    foreach ($section->subModule as $sidebar) {
                        $sidebar->update(['active_status' => 0, 'ignore'=>0]);
                    }
                }

                if($section->permissionInfo->permission_section == 1 && count($section->subModule)==0) {

                    Permission::when(!$is_role_based_sidebar, function($q){
                        $q->where('user_id', auth()->user()->id);
                    }, function ($q) use($role_id){
                        $q->where('role_id', $role_id);
                    })->where('id', $section->permission_id)->delete();
                    $section->delete();
                }

            }
            Cache::forget(sidebar_cache_key($role_id));
            return $this->reloadWithData();
        } catch (\Exception $e) {
            return response()->json([
                'msg' => __('common.Operation failed')
            ], 500);
        }

    }

    public function removeMenu(Request $request)
    {

        $is_role_based_sidebar = is_role_based_sidebar();
        $role_id = $request->role_id;

        $sidebar = Sidebar::with(['userChildMenu' => function ($q) use($role_id, $is_role_based_sidebar){
            $q->when($is_role_based_sidebar, function ($q) use($is_role_based_sidebar, $role_id){
                $q->where('role_id', $role_id)->whereNull('user_id');
            });
        }])->where('id', $request->id)->when(!$is_role_based_sidebar, function ($q) {
            $q->where('user_id', auth()->user()->id);
        }, function ($q) use($role_id){
            $q->where('role_id', $role_id);
        })->first();
        if ($sidebar && !config('app.app_sync')) {
            if($sidebar->userChildMenu->count() > 0) {
                foreach($sidebar->userChildMenu as $child) {
                    $child->update(['active_status'=>0]);
                }

            }
            Sidebar::where('parent', $sidebar->permission_id)->update(['active_status'=>0]);
            $sidebar->active_status = 0;
            $sidebar->save();
        }
        Cache::forget(sidebar_cache_key($role_id));
        return $this->reloadWithData();

    }


    private function orderMenu(array $menuItems,  $menu_status = 1, $parent_id = null, $un_used = null)
    {

        foreach ($menuItems as $index => $item) {
            $menuItem = Sidebar::where('id', $item->id)
                ->when(!$un_used, function($q){
                    $q->where('active_status', 1);
                })
                ->first();

            $data = [
                'position' => $index + 1,
                'parent'=>$parent_id,
                'active_status' => $menu_status ?? 1,
            ];

            if ($menuItem) {
                $menuItem->update($data);
                if (isset($item->children)) {
                    $this->orderMenu($item->children, $menu_status, $menuItem->permission_id, $un_used);
                }
            }

        }

    }


    public function menuUpdate(Request $request)
    {

        if (!config('app.app_sync')) {
            $menuItemOrder = json_decode($request->get('order'));

            if ($request->unused_ids) {
                Sidebar::whereIn('id', $request->unused_ids)->update([
                    'active_status' => 0,
                ]);
            }
            if ($request->ids) {
                Sidebar::whereIn('id', $request->ids)->update([
                    'active_status' => 1,
                ]);
            }

        }
        $this->orderMenu($menuItemOrder, $request->menu_status, $request->section, $request->un_used);

        Cache::forget(sidebar_cache_key($request->role_id));
        return $this->reloadWithData();
    }


    public function sortSection(Request $request)
    {
        $role_id =$request->role_id;
        if($request->ids && !config('app.app_sync')) {
            foreach($request->ids as $key=>$permissionSection) {

                $sidebar =  Sidebar::find($permissionSection);

                if($sidebar){
                    $sidebar->position = $key+1;
                    $sidebar->save();
                }

            }
        }
        Cache::forget(sidebar_cache_key($role_id));
    }

    public function resetMenu(Request $request)
    {
        set_time_limit(120);
        $role_id = $request->role_id;


        Sidebar::when(!is_role_based_sidebar(), function ($q){
            $q-> where('user_id', auth()->user()->id)
                ->where('role_id', auth()->user()->role_id);
        }, function ($q) use($role_id){
            $q->where('role_id', $role_id)->whereNull('user_id');
        })

            ->delete();
        $this->resetSidebarStore($role_id);
        Cache::forget(sidebar_cache_key($role_id));
        return redirect()->back();



    }
    public function resetWithDefault()
    {
        try {

            Sidebar::where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->delete();
            $this->defaultSidebarStore();
            return redirect()->back();
        } catch (\Exception $e) {
            ;
        }
    }

    private function reloadWithData()
    {

        $data = $this->getMenusData();
        $data['role'] = InfixRole::find(request()->role_id);
        return response()->json([
            'msg' => 'Success',
            'available_list' => (string)view('menumanage::components.available_list', $data),
            'menus' => (string)view('menumanage::components.components', $data),
            'live_preview' => (string)view('menumanage::components.live_preview', $data)
        ], 200);
    }

    public function getMenusData()
    {
        $role_id = request('role_id');
        $unused_menus = self::unUsedMenu($role_id);
        $sidebar_menus = sidebar_menus($role_id);

        return compact('unused_menus', 'sidebar_menus');
    }
    public static function unUsedMenu($role_id = null)
    {
        $sectionIds = Sidebar::whereNull('parent')->pluck('permission_id')->toArray();

        $parentSidebars = Sidebar::whereIn('parent', $sectionIds)
            ->deActiveMenuUser($role_id)
            ->pluck('permission_id')
            ->toArray();

        $single = Sidebar::whereNotIn('parent', $parentSidebars)
            ->deActiveMenuUser($role_id)
            ->pluck('permission_id')
            ->toArray();
        $hasIds = array_merge($parentSidebars, $single);

        $hasIds = (array_unique($hasIds));
        if($hasIds) {
            return Sidebar::whereIn('permission_id', $hasIds)->deActiveMenuUser($role_id)->get();
        }
        return collect();
    }
}
