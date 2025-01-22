<?php

namespace App\Http\Controllers;

use IntlChar;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;

class SmSearchController extends Controller
{
  public function __construct()
  {
    $this->middleware('PM');
    // User::checkAuth();
  }
  function search(Request $r)
  {
    try {
      if ($r->ajax()) {
        $output = '';
        $query = $r->get('search');
        if ($query != '') {
          $permissionIds = Permission::where('name', 'like', '%' . $query . '%')
            ->roleWise()->where('is_menu', 1)->where('permission_section', 0)
            ->pluck('id')->toArray();
          if ($permissionIds) {
            return Sidebar::where('user_id', auth()->user()->id)
              ->whereIn('permission_id', $permissionIds)->get()->map(function ($value) {
                return [
                  'name' => $value->permissionInfo->name,
                  'route' => $value->permissionInfo->route ?  Route::has($value->permissionInfo->route) ? route($value->permissionInfo->route) : route('admin-dashboard') : route('admin-dashboard'),
                ];
              });
          }
        } else {
          return response()->json(['not found' => 'Not Foound'], 404);
        }
      }
    } catch (\Exception $e) {
      Toastr::error('Operation Failed', 'Failed');
      return redirect()->back();
    }
  }
  public function dashboardStudentSearch(Request $request)
  {
    try {
      if(is_string($request->search)){
        $nameOrAdmissionNo = $request->search;
      }
      if(preg_match('~[0-9]+~', $request->search)){
        $nameOrAdmissionNo = (int)$request->search;
      }
      return SmStudent::when(is_numeric($nameOrAdmissionNo), function ($q) use ($nameOrAdmissionNo) {
        $q->where('admission_no', $nameOrAdmissionNo);
      })
      ->when(is_string($nameOrAdmissionNo), function ($q) use ($nameOrAdmissionNo) {
        $q->where('full_name', 'like', '%' . $nameOrAdmissionNo . '%');
      })
      ->get()
        ->map(function ($value) {
          return [
            'name' => $value->full_name,
            'route' => route('student_view', $value->id),
          ];
        });
    } catch (\Exception $e) {
      Toastr::error('Operation Failed', 'Failed');
      return redirect()->back();
    }
  }
}
