<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Modules\Fees\Entities\FmFeesGroup;
use Illuminate\Support\Facades\Validator;

class FeesGroupController extends Controller
{
    public function fees_group_index(Request $request)
    {
        $data = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'name', 'description')
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
                'message' => 'Fees group list'
            ];
        }
        return response()->json($response);
    }

    public function fees_group_store(Request $request)
    {
        $request->validate([
            'name' => "required|unique:fm_fees_groups,name"
        ]);

        $visitor = new FmFeesGroup();
        $visitor->name = $request->name;
        $visitor->description = $request->description;
        $visitor->school_id = auth()->user()->school_id;
        $visitor->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $visitor->save();

        $data = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)->select('id', 'name', 'description')->findOrFail($visitor->id);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$data],
                'message' => 'Fees group stored'
            ];
        }
        return response()->json($response);
    }

    public function fees_group_edit(Request $request)
    {
        $request->validate([
            'fees_group_id' => 'required|exists:fm_fees_groups,id'
        ]);

        $data = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->fees_group_id)
            ->select('id', 'name', 'description')
            ->first();
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
                'message' => 'Fees group detail'
            ];
        }
        return response()->json($response);
    }

    public function fees_group_update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:fm_fees_groups,id',
            'name' => "required|max:200|unique:sm_fees_groups,name," . $request->id
        ]);
        $visitor = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)->where('id', $request->id)->where('school_id', auth()->user()->school_id)->first();
        $visitor->name = $request->name ?? $visitor->name;
        $visitor->description = $request->description;
        $visitor->save();
        $data = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)->select('id', 'name', 'description')->findOrFail($visitor->id);
        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$data],
                'message' => 'Fees group updated'
            ];
        }
        return response()->json($response);
    }

    public function fees_group_delete(Request $request)
    {
        $fees_group = FmFeesGroup::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->fees_group_id)
            ->delete();
        if (!$fees_group) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Fees group removed'
            ];
        }
        return response()->json($response);
    }
}
