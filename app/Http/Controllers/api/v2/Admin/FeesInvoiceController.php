<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\tableList;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesInvoice;
use Illuminate\Support\Facades\Validator;

class FeesInvoiceController extends Controller
{
    public function fees_invoice_index(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:sm_classes,id',
            'section_id' => 'nullable|exists:sm_sections,id',
        ]);
        $data['studentInvoices'] = FmFeesInvoice::withoutGlobalScope(AcademicSchoolScope::class)->where('type', 'fees')
            ->with('studentInfo', 'recordDetail')
            ->select('fm_fees_invoices.*')
            ->where('school_id', Auth::user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->when($request->name, function ($q) use ($request) {
                $q->whereHas('studentInfo', function ($q) use ($request) {
                    return $q->where(function ($q) use ($request) {
                        return $q->where('first_name', 'like', '%' . $request->name . '%')
                            ->orWhere('last_name', 'like', '%' . $request->name . '%')
                            ->orWhere('full_name', 'like', '%' . $request->name . '%');
                    });
                });
            })
            ->when($request->class_id, function ($q2) use ($request) {
                $q2->whereHas('recordDetail', function ($q2) use ($request) {
                    return $q2->where(function ($q2) use ($request) {
                        return $q2->where('class_id', $request->class_id);
                    });
                });
            })
            ->when($request->section_id, function ($q3) use ($request) {
                $q3->whereHas('recordDetail', function ($q3) use ($request) {
                    $q3->where(function ($q3) use ($request) {
                        return $q3->where('section_id', $request->section_id);
                    });
                });
            })
            ->latest('create_date')->get()->map(function ($value) {
                $balance = $value->Tamount + $value->Tfine - ($value->Tpaidamount + $value->Tweaver);
                $paid_amount = $value->Tpaidamount;
                if ($balance == 0) {
                    $status = __('fees.paid');
                } else {
                    if ($paid_amount > 0) {
                        $status = __('fees.partial');
                    } else {
                        $status = __('fees.unpaid');
                    }
                }
                return [
                    'id' => (int)$value->id,
                    'full_name' => (string)@$value->studentInfo->first_name . ' ' . @$value->studentInfo->last_name,
                    'class' => (string)@$value->recordDetail->class->class_name,
                    'section' => (string)@$value->recordDetail->section->section_name,
                    'date' => (string)dateConvert($value->create_date),
                    'amount' => (string)currency_format($value->Tamount),
                    'paid' => (string)currency_format($paid_amount),
                    'balance' => (string)currency_format($balance),
                    'status' => (string)$status
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
                'message' => 'Fees invoice list'
            ];
        }
        return response()->json($response);
    }

    /* public function fees_type_store(Request $request)
    {
        $this->validate($request, [
            'name' => "required|max:50|unique:sm_fees_types",
            'fees_group' => "required"
        ]);

        $fees_type = new FmFeesType();
        $fees_type->name = $request->name;
        $fees_type->fees_group_id = $request->fees_group;
        $fees_type->description = $request->description;
        $result = $fees_type->save();

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Fees invoice created',
        ];
        return response()->json($response, 200);
    }

    public function fees_type_edit(Request $request)
    {
        $data = FmFeesType::select('id', 'name', 'description', 'fees_group_id')->find($request->fees_type_id);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function fees_type_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:sm_fees_types,name,' . $request->id,
            'fees_group' => "required"
        ]);

        $fees_type = FmFeesType::find($request->fees_type_id);
        $fees_type->name = $request->name;
        $fees_type->fees_group_id = $request->fees_group;
        $fees_type->description = $request->description;
        $fees_type->save();

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function fees_type_delete(Request $request)
    {
        $id_key = 'fees_type_id';
        $tables = tableList::getTableList($id_key, $request->fees_type_id);
        try {
            $delete_query = FmFeesType::destroy($request->fees_type_id);
            if ($delete_query) {
                $response = [
                    'success' => true,
                    'data'    => null,
                    'message' => 'Operation successful',
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'success'  => false,
                    'data' => null,
                    'message' => 'Operation failed',
                ];
                return response()->json($response, 401);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'success' => false,
                'data'    => 'This data already used in  : ' . $tables . ' Please remove those data first',
                'message' => 'This item already used',
            ];
            return response()->json($response, 401);
        }
    } */
}
