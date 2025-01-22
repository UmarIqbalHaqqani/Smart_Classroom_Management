<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\ActiveStatusSchoolScope;
use Modules\RolePermission\Entities\InfixRole;
use App\Http\Resources\v2\Admin\IndividualStaffDetailsResource;

class StaffController extends Controller
{
    public function role()
    {
        $data['roles'] = InfixRole::/* where('school_id', auth()->user()->school_id)
            -> */when((generalSetting()->with_guardian != 1), function ($query) {
            $query->whereNot('id', 3);
        })
            ->where('active_status', 1)
            ->where(function ($q) {
                $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
            })
            ->whereNot('id', 1)
            ->whereNot('id', 2)
            ->whereNot('id', 3)
            ->orderBy('id', 'asc')
            ->select('id', 'name', 'type')
            ->get();
        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Staff role list'
            ];
        }
        return response()->json($response);
    }

    public function roleWiseStaffList(Request $request)
    {
        $data = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('role_id', $request->role_id)
            ->get()->map(function ($value) {
                return [
                    'id'                => (int)$value->id,
                    'first_name'        => (string)$value->first_name,
                    'last_name'         => (string)$value->last_name,
                    'mobile'            => (string)$value->mobile,
                    'current_address'   => (string)$value->current_address,
                    'permanent_address' => (string)$value->permanent_address,
                    'staff_photo'       => $value->staff_photo ? (string)asset($value->staff_photo) : (string)null
                ];
            });

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Role wise staff list'
            ];
        }
        return response()->json($response);
    }

    public function individualStaffDetails(Request $request)
    {
        $staff = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($request->staff_id);

        $data = new IndividualStaffDetailsResource($staff);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Staff detail'
            ];
        }
        return response()->json($response);
    }
}
