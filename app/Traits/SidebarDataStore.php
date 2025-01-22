<?php

namespace App\Traits;

use App\GlobalVariable;
use App\InfixModuleManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Modules\Saas\Entities\SaasSettings;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;

trait SidebarDataStore
{


    function defaultSidebarStore($role_id = null)
    {


        $is_role_based_sidebar = is_role_based_sidebar();

        $user = auth()->user();
        if (!$role_id) {
            $role_id = $user->role_id;
        }
        $cache_key = sidebar_cache_key($role_id);

        $exit = Sidebar::when(!$is_role_based_sidebar, function ($q) use ($user) {
            $q->where('user_id', auth()->user()->id)->where('role_id', $user->role_id);
        }, function ($q) use ($role_id) {
            $q->where('role_id', $role_id)->whereNull('user_id');
        })->first();


        if ($exit) {
            return true;
        }


        $permissionInfos = $this->permissions($role_id);



        /*if ($role_id == 2 || $role_id == 3) {

            Sidebar::updateOrCreate([
                'permission_id' => 1,
                'user_id' => $is_role_based_sidebar ? null : $user->id,
                'role_id' => $role_id,

            ], [
                'position' => 1,
                'level' => 1,
                'parent' => null,
            ]);


        }*/
        if ($role_id == 2 || $role_id == 3) {
            foreach ($permissionInfos as $key => $sidebar) {
                $parent_id = $this->parentId($sidebar, $role_id);
                $this->storeSidebar($sidebar, $key, $parent_id, $role_id);
            }

            Cache::forget($cache_key);


        } else {
            $this->resetSidebarStore($role_id);
        }

        if ($role_id == 2 || $role_id == 3) {
            Sidebar::whereNull('parent')->when(!$is_role_based_sidebar, function ($q){
                $q->where('user_id', auth()->user()->id);
            }, function ($q) use($role_id){
                $q->where('role_id', $role_id);
            })->where('permission_id', '!=', 1)->update(['parent' => 1]);
        }

        Cache::forget($cache_key);

    }

    function resetSidebarStore($role_id = null)
    {
        $is_role_based_sidebar = is_role_based_sidebar();

        $user = auth()->user();
        if(!$role_id){
            $role_id = $user->role_id;
        }

        if ($role_id == 2 || $role_id == 3) {
            $this->defaultSidebarStore($role_id);
            return true;
        }


        $dashboardSections = ["dashboard", "menumanage.index"];
        $administration_sections = ["admin_section", "academics", "study_material", 'download-center', "lesson-plan", "bulk_print", "certificate", "university", "lms"];
        $student_sections = ["student_info", "fees", "fees_collection", "transport", "dormitory", "library", "homework", "behaviour_records", "alumni_records"];
        $alumni_sections = ["student_info", "fees", "fees_collection", "transport", "dormitory", "library", "homework", "behaviour_records", "alumni_records"];
        $exam_sections = ["examination", "online_exam", "examplan"];
        $hr_sections = ["role_permission", "human_resource", "teacher-evaluation", "leave"];
        $account_sections = ["accounts", "inventory", "wallet"];
        $utilities_sections = ["chat", "style", "communicate"];
        $report_sections = ["students_report", "exam_report", "staff_report", "fees_report", "accounts_report"];
        $settings_sections = ["general_settings", "fees_settings", "exam_settings", "frontend_cms", "custom_field"];

        //permission section
        $permissionSections = include './resources/var/permission/permission_section_sidebar.php';

        $permissionSectionRoutes = [];
        foreach ($permissionSections as $item) {
            storePermissionData($item, $user->id, null, $role_id);
        }
        // end
        $userPermissionSections = Permission::where('permission_section', 1)
            ->when(!$is_role_based_sidebar, function($q) use($user){
                $q->where('user_id', $user->id);
            }, function ($q) use ($role_id) {
                $q->where('role_id', $role_id)->whereNull('user_id');
            })
            ->where('is_saas', 0)->get(['id', 'name', 'type', 'route', 'parent_route', 'permission_section']);


        foreach ($userPermissionSections as $key => $userSection) {
            $parent = $userSection->parent_route != null
                ? Permission::where('route', $userSection->parent_route)
                    ->when($role_id == 2 || $role_id == GlobalVariable::isAlumni(), function ($q) {
                        $q->where('is_student', 1);
                    })->when($role_id == 3, function ($q) {
                        $q->where('is_parent', 1);
                    })->where('is_menu', 1)
                    ->value('id') : null;
            $this->storeSidebar($userSection, $key, $parent, $role_id);
        }
        $permissionInfos = $this->permissions($role_id);

        foreach ($permissionInfos as $key => $sidebar) {
            $parent_id = $this->parentId($sidebar, $role_id);

            if (in_array($sidebar->route, $dashboardSections)) {
                $parent_id = Permission::where('route', 'dashboard_section')
                    ->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id);
                    })
                   ->value('id');
            }
            if (in_array($sidebar->route, $administration_sections)) {
                $parent_id = Permission::where('route', 'administration_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                    $q->where('user_id', $user->id);
                }, function ($q) use ($role_id) {
                    $q->where('role_id', $role_id)->whereNull('user_id');
                })->value('id');
            }
            if (in_array($sidebar->route, $student_sections)) {
                $parent_id = Permission::where('route', 'student_section')
                    ->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            // if (in_array($sidebar->route, $alumni_sections)) {
            //     $parent_id = Permission::where('route', 'alumni_sections')
            //     ->where('user_id', $user->id)->value('id');
            // }
            if (in_array($sidebar->route, $exam_sections)) {
                $parent_id = Permission::where('route', 'exam_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            if (in_array($sidebar->route, $hr_sections)) {
                $parent_id = Permission::where('route', 'hr_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            if (in_array($sidebar->route, $account_sections)) {
                $parent_id = Permission::where('route', 'accounts_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            if (in_array($sidebar->route, $utilities_sections)) {
                $parent_id = Permission::where('route', 'utilities_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            if (in_array($sidebar->route, $report_sections)) {
                $parent_id = Permission::where('route', 'report_section')
                    ->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }
            if (in_array($sidebar->route, $settings_sections)) {
                $parent_id = Permission::where('route', 'settings_section')->when(!$is_role_based_sidebar, function($q) use($user) {
                        $q->where('user_id', $user->id);
                    }, function ($q) use ($role_id) {
                        $q->where('role_id', $role_id)->whereNull('user_id');
                    })->value('id');
            }

            if (!$sidebar->route && !$sidebar->parent_route) {
                continue;
            }
            $this->storeSidebar($sidebar, $key, $parent_id, $role_id);
        }
        $ignorePermissionRoutes = ['reports', 'fees.fees-report', 'exam-setting'];
        $getIgnoreIds = Permission::whereIn('route', $ignorePermissionRoutes)->pluck('id')->toArray();
        Cache::forget(sidebar_cache_key($role_id));
        Sidebar::whereIn('permission_id', $getIgnoreIds)->when(!$is_role_based_sidebar, function($q) use($user) {
            $q->where('user_id', $user->id)->where('role_id', $user->role_id);
        }, function ($q) use ($role_id) {
            $q->where('role_id', $role_id)->whereNull('user_id');
        })->update(['active_status' => 0, 'ignore' => 1]);
        $this->deActiveForSaas();

         # Delete exra route menu from permission table
         $fees_setting    = Permission::where('route','fees_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $exam_settings   = Permission::where('route','exam_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $student_report  = Permission::where('route','students_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $exam_report     = Permission::where('route','exam_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $staff_report    = Permission::where('route','staff_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $fees_report     = Permission::where('route','fees_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
         $accounts_report = Permission::where('route','accounts_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
    }

    function permissions($role_id = null)
    {
        $is_role_based_sidebar = is_role_based_sidebar();

        if (!$is_role_based_sidebar) {
            $user = auth()->user();
            if ($user->role_id == 1) {
                $permissionInfos = Permission::where('is_admin', 1)->where('is_menu', 1)
                    ->where('is_saas', 0)
                    ->where(function ($q) {
                        $q->whereNull('role_id')->where(function($q){
                            $q->where('user_id', auth()->user()->id)->orWhereNull('user_id');
                        });
                    })
                    ->get(['id', 'name', 'type', 'route', 'parent_route', 'permission_section']);
            } else {
                $permissionInfos = Permission::where('is_menu', 1)
                    ->orderBy('position', 'ASC')
                    ->when(!in_array($user->role_id, [2, 3, GlobalVariable::isAlumni()]), function ($q) {
                        $q->where('is_admin', 1);
                    })->when($user->role_id == 4, function ($q) {
                        $q->orWhere('is_teacher', 1);
                    })->when($user->role_id == 2, function ($q) {
                        $q->where('is_student', 1);
                    })->when($user->role_id == 3, function ($q) {
                        $q->where('is_parent', 1);
                    })->where(function ($q) {
                        $q->whereNull('role_id')->where('user_id', auth()->user()->id)->orWhereNull('user_id');
                    })->when($user->role_id == GlobalVariable::isAlumni(), function ($q) {
                        $q->where('is_alumni', 1);
                    })
                    ->get(['id', 'name', 'type', 'route', 'parent_route', 'position', 'permission_section']);
            }
            return $permissionInfos;
        }

        if ($role_id == 1) {
            $permissionInfos = Permission::where('is_admin', 1)->where('is_menu', 1)
                ->where('is_saas', 0)
                ->where(function ($q) use ($role_id) {
                    $q->where('role_id', $role_id)->orWhere(function($q){
                        $q->whereNull('role_id')->whereNull('user_id');
                    });
                })
                ->get(['id', 'name', 'type', 'route', 'parent_route', 'permission_section']);
        } else {
            $permissionInfos = Permission::where('is_menu', 1)
                ->orderBy('position', 'asc')
                ->when(!in_array($role_id, [2, 3, GlobalVariable::isAlumni()]), function ($q) {
                    $q->where('is_admin', 1);
                })->when($role_id == 4, function ($q) {
                    $q->where(function($q){
                        $q->where('is_admin', 1)->orWhere('is_teacher', 1);
                    });
                })->when($role_id == 2, function ($q) {
                    $q->where('is_student', 1);
                })->when($role_id == 3, function ($q) {
                    $q->where('is_parent', 1);
                })->where(function ($q) use ($role_id) {
                    $q->where(function($q) use($role_id){
                        $q->where('role_id', $role_id);
                    })->orWhere(function($q){
                        $q->whereNull('role_id')->whereNull('user_id');
                    });
                })->when($role_id == GlobalVariable::isAlumni(), function ($q) {
                    $q->where('is_alumni', 1);
                })

                ->get(['id', 'name', 'type', 'route', 'parent_route', 'position', 'permission_section']);
        }
        return $permissionInfos;

    }

    function storeSidebar($sidebar, $key, $parent_id, $role_id)
    {
        $is_role_based_sidebar = is_role_based_sidebar();

        $user = auth()->user();

        Sidebar::updateOrCreate([
            'permission_id' => $sidebar->id,
            'user_id' => $is_role_based_sidebar ? null : $user->id,
            'role_id' => $role_id

        ], [
            'position' => $key + 1,
            'level' => $sidebar->type,
            'parent' => $parent_id,
        ]);
    }

    function modulePermissionSidebar($role_id = null)
    {

        $is_role_based_sidebar = is_role_based_sidebar();
        $user = auth()->user();

        if (!$role_id) {
            $role_id = $user->role_id;
        }

        $permissionIds = $this->permissions($role_id)->whereNotNull('route')->pluck('id')->toArray();

        $sidebarPermissionIds = Sidebar::when(!$is_role_based_sidebar, function($q) use($user){
            $q->where('user_id', $user->id)->where('role_id', $user->role_id);
        }, function($q) use($role_id){
            $q->where('role_id', $role_id)->orWhere(function($q){
                $q->whereNull('role_id')->whereNull('user_id');
            });
        })->pluck('permission_id')->toArray();

        $newPermissionIds = array_diff($permissionIds, $sidebarPermissionIds);

        if (empty($newPermissionIds)) return true;

        if (count($newPermissionIds) > 0) {
            $permissionInfos = Permission::whereIn('id', $newPermissionIds)->get(['id', 'name', 'type', 'route', 'parent_route', 'position', 'permission_section']);

            foreach ($permissionInfos as $key => $sidebar) {
                $parent_id = $this->parentId($sidebar, $role_id);

                if (!$sidebar->route && !$sidebar->parent_route) {
                    continue;
                }
                $this->storeSidebar($sidebar, $key, $parent_id, $role_id);
            }
            if($role_id == 2 || $role_id == 3){
                Sidebar::whereNull('parent')->when(!$is_role_based_sidebar, function ($q){
                    $q->where('user_id', auth()->user()->id);
                }, function ($q) use($role_id){
                    $q->where('role_id', $role_id);
                })->where('permission_id', '!=', 1)->update(['parent' => 1]);
            }
            Cache::forget(sidebar_cache_key($role_id));
        }

    }

    function parentId($sidebar, $role_id = null)
    {

        $is_role_based_sidebar = is_role_based_sidebar();
        if(!$role_id){
            $role_id = auth()->user()->role_id;
        }

        $parent = $sidebar->parent_route != null
            ? Permission::where('route', $sidebar->parent_route)
                ->when($role_id == 2, function ($q) {
                    $q->where('is_student', 1);
                })->when($role_id == 3, function ($q) {
                    $q->where('is_parent', 1);
                })->where('is_menu', 1)
                // ->where('menu_status', 1)
                ->first(['id', 'permission_section']) : null;

        if ($parent && $parent->permission_section == 1 && $sidebar->permission_section) {
            $parent_id = null;
        } elseif ($parent && $parent->permission_section == 1 && !$sidebar->permission_section) {
            $parent_id = $parent->id;
        } elseif ($parent) {
            $parent_id = $parent->id;
        } else {
            $parent_id = 1;
        }
        if ($sidebar->permission_section) {
            $parent_id = null;
        }

        if (in_array($sidebar->route, $this->paidModuleRoutes())) {
            $parent_id = Permission::where('route', 'module_section')->when(!$is_role_based_sidebar, function($q){
                $q->where('user_id', auth()->id());
            }, function ($q) use($role_id) {
                $q->where('role_id', $role_id)->whereNull('user_id');
            })->value('id');
        }


        return $parent_id;
    }

    function allActivePaidModules()
    {
        $activeModules = [];
        $modules = InfixModuleManager::whereNotNull('purchase_code')->where('is_default', false)->where('name', '!=', 'OnlineExam')->pluck('name')->toArray();
        foreach ($modules as $module) {
            if (moduleStatusCheck($module)) {
                $activeModules [] = $module;
            }
        }
        return $activeModules;
    }

    function paidModuleRoutes()
    {

        return Permission::whereIn('module', $this->allActivePaidModules())
            ->whereNotNull('route')
            ->whereNull('parent_route')
            ->when(auth()->user()->role_id == 1, function ($q) {
                $q->where('is_admin', 1);
            })->whereNotNull('module')->pluck('route')->toArray();
    }

    function deActiveForPgsql()
    {
        if (db_engine() != 'mysql') {
            Permission::whereIn('route', ['backup-settings'])->update(['is_menu' => 0, 'menu_status' => 0, 'status' => 0]);
        }
    }

    function deActiveForSaas()
    {
        if (moduleStatusCheck('Saas')) {
            $list = ['update-system', 'utility', 'manage-adons', 'backup-settings', 'utility', 'language-list'];
            Permission::whereIn('route', $list)->update(['is_menu' => 0, 'menu_status' => 0, 'status' => 0, 'is_saas' => 1]);
            $saasSettingsRoutes = SaasSettings::where('saas_status', 1)->pluck('route')->toArray();
            if ($saasSettingsRoutes) {
                Permission::whereIn('route', $saasSettingsRoutes)->update(['is_menu' => 1, 'menu_status' => 1, 'status' => 1, 'is_saas' => 0]);
            }
        }

    }
}
