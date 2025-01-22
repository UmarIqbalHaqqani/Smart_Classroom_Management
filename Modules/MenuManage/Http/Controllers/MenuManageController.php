<?php

namespace Modules\MenuManage\Http\Controllers;

use App\SmGeneralSettings;
use App\SmSchool;
use Illuminate\Http\Request;
use App\Traits\SidebarDataStore;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\MenuManage\Entities\Sidebar;
use Modules\MenuManage\Entities\SidebarNew;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class MenuManageController extends Controller
{
    use SidebarDataStore;

    public function index(Request $request)
    {
        set_time_limit(120);
        $data = [];

        if(is_role_based_sidebar()){
            if(moduleStatusCheck('Saas') && !auth()->user()->is_administrator){
                abort(403);
            }
            if(auth()->user()->role_id != 1){
                abort(403);
            }
        }


        if(!is_role_based_sidebar()){

            $data['unused_menus'] = SidebarManagerController::unUsedMenu();
            $data['sidebar_menus'] = sidebar_menus();
            $data['permission_sections'] = Permission::where('user_id', auth()->user()->id)->pluck('id')->toArray();
            return view('menumanage::index', $data);
        }

        $role_id = $request->role_id;


        if(!$role_id){
            $data['roles'] = InfixRole::where('is_saas',0)->when((generalSetting()->with_guardian !=1), function ($query) {
                $query->where('id', '!=', 3);
            })->where('active_status', '=', 1)
                ->where(function ($q) {
                    $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
                })
                ->when(auth()->user()->role_id != 1, function ($q) {
                    $q->where('id', '!=', 1);
                })
                ->orderBy('id', 'asc')
                ->get();



            return view('menumanage::role', $data);
        }

        $request->validate([
            'role_id' => ['required', Rule::exists('infix_roles', 'id')->where(function ($query) {
                $query->where('is_saas',0)->when((generalSetting()->with_guardian !=1), function ($query) {
                    $query->where('id', '!=', 3);
                })->where('active_status', '=', 1)
                    ->where(function ($q) {
                        $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
                    })
                    ->when(auth()->user()->role_id != 1, function ($q) {
                        $q->where('id', '!=', 1);
                    });
            })],
        ]);

        $data['role'] = InfixRole::find($role_id);
        $this->defaultSidebarStore($role_id);
        $this->modulePermissionSidebar($role_id);
        $data['unused_menus'] = SidebarManagerController::unUsedMenu($role_id);
        $data['sidebar_menus'] = sidebar_menus($role_id);
        $data['permission_sections'] = Permission::where('role_id', $role_id)->pluck('id')->toArray();


        return view('menumanage::role_index', $data);



    }


    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $checked_ids = $request->module_id;
            SidebarNew::where('user_id', $user->id)->where('role_id', $user->role_id)->delete();
            foreach ($request->all_modules_id as $key => $id) {
                $status = in_array($id, $checked_ids) ? 1 : 0;
                $sidebar = new SidebarNew;
                $sidebar->infix_module_id = $id;
                if ($user->role_id == 2 || $user->role_id == 3) {
                    $student_p = InfixModuleStudentParentInfo::find($id);
                    $sidebar->module_id = $student_p ? $student_p->module_id : ' ';
                    $sidebar->route = $student_p ? $student_p->route : ' ';
                    $sidebar->name = $student_p ? $student_p->name : ' ';
                    $sidebar->parent_id = $student_p ? $student_p->parent_id : ' ';
                    $sidebar->type = $student_p ? $student_p->type : ' ';
                } else {
                    $infix_module = InfixModuleInfo::find($id);
                    $sidebar->module_id = $infix_module ? $infix_module->module_id : ' ';
                    $sidebar->route = $infix_module ? $infix_module->route : ' ';
                    $sidebar->name = $infix_module ? $infix_module->name : ' ';
                    $sidebar->parent_id = $infix_module ? $infix_module->parent_id : ' ';
                    $sidebar->type = $infix_module ? $infix_module->type : ' ';
                }
                $sidebar->role_id = auth()->user()->role_id;
                $sidebar->user_id = auth()->user()->id;
                $sidebar->school_id = auth()->user()->school_id;
                $sidebar->active_status = $status;
                $sidebar->parent_position_no = $key;
                $sidebar->child_position_no = $key;
                $sidebar->save();


            }


            Toastr::success('Successfully Insert', 'Success');
            return redirect()->back();

        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function manage()
    {

        $id = Auth::user()->role_id;
        $role = InfixRole::where('is_saas', 0)->where('id', $id)->first();
        $all_modules = InfixModuleInfo::where('is_saas', 0)->where('active_status', 1)->get();
        $all_modules = $all_modules->groupBy('module_id');
        $all_sidebars = SidebarNew::where('is_saas', 0)->distinct('module_id')->get();
        return view('menumanage::all_sidebar_menu', compact('role', 'all_modules', 'all_sidebars'));
    }

    public function reset()
    {
        try {
            $user = Auth::user();
            Sidebar::where('user_id', $user->id)->where('role_id', $user->role_id)->delete();
            $this->defaultStore();
            Toastr::success('Operation Successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function settings(){
        $data = [];

        if(moduleStatusCheck('Saas')){
            $data['schools'] = SmSchool::with('settings')->get();
        }

        return view('menumanage::settings', $data);
    }

    public function postSettings(Request $request){
       $data = $request->get('role_based_sidebar', []);
       SmGeneralSettings::query()->update([
           'role_based_sidebar' => 0
       ]);
       foreach ($data as $school => $value) {
           SmGeneralSettings::where('school_id', $school)->update([
               'role_based_sidebar' => $value
           ]);
       }

       $fees_setting   = Permission::where('route','fees_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $exam_settings  = Permission::where('route','exam_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $student_report  = Permission::where('route','students_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $exam_report  = Permission::where('route','exam_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $staff_report = Permission::where('route','staff_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $fees_report = Permission::where('route','fees_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
       $accounts_report = Permission::where('route','accounts_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();

        Toastr::success('Operation Successful', 'Success');
        return redirect()->back();
    }
}