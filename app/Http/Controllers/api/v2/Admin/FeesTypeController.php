<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\tableList;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use Illuminate\Support\Facades\Validator;

class FeesTypeController extends Controller
{
    public function fees_type_index(Request $request)
    {
        $data['fees_types'] = FmFeesType::with(['fessGroup' => function ($fessGroup) {
            $fessGroup->withoutGlobalScope(AcademicSchoolScope::class);
        }])->withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)->get()
            ->map(function ($value) {
                return [
                    'id' => (int)$value->id,
                    'name' => (string)$value->name,
                    'description' => (string)$value->description,
                    'fees_group' => (string)@$value->fessGroup->name,
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
                'message' => 'Fees type list'
            ];
        }
        return response()->json($response);
    }

    public function fees_type_store(Request $request)
    {
        $this->validate($request, [
            'name' => "required|max:50|unique:fm_fees_types,name",
            'fees_group' => "required|exists:fm_fees_groups,id"
        ]);
        $fees_type = new FmFeesType();
        $fees_type->name = $request->name;
        $fees_type->fees_group_id = $request->fees_group;
        $fees_type->description = $request->description;
        $fees_type->school_id = auth()->user()->school_id;
        $fees_type->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $fees_type->save();
        $data = FmFeesType::withoutGlobalScope(AcademicSchoolScope::class)
        ->where('school_id', auth()->user()->school_id)
        ->select('id', 'name', 'fees_group_id', 'description')->find($fees_type->id);

        if (empty($data)) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [
                    'fees_types' => [$data]
                ],
                'message' => 'New fees type created'
            ];
        }
        return response()->json($response);
    }

    public function fees_type_edit(Request $request)
    {
        $data = FmFeesType::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'name', 'description', 'fees_group_id')->find($request->fees_type_id);
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
                'message' => 'Fees type detail'
            ];
        }
        return response()->json($response);
    }

    public function fees_type_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:fm_fees_types,name,' . $request->fees_type_id,
            'fees_group' => "required|exists:fm_fees_groups,id"
        ]);
        $fees_type = FmFeesType::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->find($request->fees_type_id);
        $fees_type->name = $request->name ?? $fees_type->name;
        $fees_type->fees_group_id = $request->fees_group;
        $fees_type->description = $request->description;
        $fees_type->save();
        $data = FmFeesType::withoutGlobalScope(AcademicSchoolScope::class)->select('id', 'name', 'fees_group_id as fees_group', 'description')->findOrFail($fees_type->id);

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
                'message' => 'Fees type updated'
            ];
        }
        return response()->json($response);
    }

    public function fees_type_delete(Request $request)
    {
        $request->validate([
            'fees_type_id' => 'required|exists:fm_fees_types,id'
        ]);
        $id_key = 'fees_type_id';
        $tables = tableList::getTableList($id_key, $request->fees_type_id);
        try {
            $delete_query = FmFeesType::withoutGlobalScope(AcademicSchoolScope::class)
                ->where('id', $request->fees_type_id)
                ->where('school_id', auth()->user()->school_id)->delete();
            if (!$delete_query) {
                $response = [
                    'success' => false,
                    'data'    => null,
                    'message' => 'Operation failed'
                ];
            } else {
                $response = [
                    'success' => true,
                    'data'    => null,
                    'message' => 'Fees type deleted'
                ];
            }
            return response()->json($response);
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'success' => false,
                'data'    => 'This data already used in  : ' . $tables . ' Please remove those data first',
                'message' => 'This item already used',
            ];
            return response()->json($response, 401);
        }
    }
}
