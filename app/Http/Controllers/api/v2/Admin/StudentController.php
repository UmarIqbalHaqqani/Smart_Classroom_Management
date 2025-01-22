<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmClass;
use App\SmParent;
use App\SmStudent;
use App\SmAcademicYear;
use App\SmStudentDocument;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use App\Http\Resources\SmStudentResourse;
use App\Scopes\StatusAcademicSchoolScope;
use App\Models\SmStudentRegistrationField;
use App\Http\Resources\SmStudentTransportResourse;
use App\Http\Resources\v2\StudentTransportResource;
use App\Http\Resources\v2\Admin\StudentListResource;

class StudentController extends Controller
{
    public function studentDetailsSearch(Request $request)
    {
        try {
            $this->validate($request, [
                'class'   => 'required',
                'roll_no' => 'nullable|integer|not_in:0',
            ], [
                'roll_no.not_in' => 'The selected roll is invalid.',
            ]);
    
            $records = StudentRecord::query();
            $records->where('is_promote', 0)
                ->where('school_id', auth()->user()->school_id)
                ->where('class_id', $request->class)
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                })
                ->when($request->roll_no, function ($query) use ($request) {
                    $query->where('roll_no', $request->roll_no);
                });
    
            $student_records = $records->where('is_promote', 0)->whereHas('student')->get(['student_id'])->unique('student_id')->toArray();
    
            $all_students = SmStudent::withoutGlobalScope(SchoolScope::class)
                ->with('studentRecords', 'studentRecords.class', 'studentRecords.section')
                ->whereIn('id', $student_records)
                ->where('active_status', 1)
                ->where('school_id', auth()->user()->school_id)
                ->with(['parents' => function ($query) {
                    $query->select('id', 'fathers_name');
                }])
                ->with(['gender' => function ($query) {
                    $query->select('id', 'base_setup_name');
                }])
                ->with(['category' => function ($query) {
                    $query->select('id', 'category_name');
                }])
                ->when($request->roll_no, function ($query) use ($request) {
                    $query->where('roll_no', 'like', "%$request->roll_no%");
                })->when($request->name, function ($query) use ($request) {
                    $query->where('full_name', 'like', "%$request->name%");
                })->get();
    
            $data = StudentListResource::collection($all_students);
    
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Student search result'
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    
        return response()->json($response);
    }
    public function profilePersonal(Request $request)
    {
        $school_id = auth()->user()->school_id;

        $proifle_details = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['bloodGroup' => function ($q) use ($school_id) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', $school_id);
            }, 'religion' => function ($q) use ($school_id) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', $school_id);
            }, 'defaultClass' => function ($q) {
                $q->with(['class' => function ($q) {
                    $q->withoutGlobalScopes([GlobalAcademicScope::class, StatusAcademicSchoolScope::class])->where('school_id', auth()->user()->school_id);
                }, 'section' => function ($q) {
                    $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }]);
            }])
            ->where('school_id', $school_id)
            ->where('id', $request->student_id)->firstOrFail();

        $data['profilePersonal'] = new SmStudentResourse($proifle_details);

        $data['show_permission'] = SmStudentRegistrationField::where('school_id', auth()->user()->school_id ?? app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => 'Student profile'
            ];
        }

        return response()->json($response);
    }

    public function profileParents(Request $request)
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)->firstOrFail();

        $data['profileParents'] = SmParent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $students->parent_id)
            ->select('id', 'fathers_name', 'fathers_mobile', 'fathers_occupation', 'fathers_photo', 'mothers_name', 'mothers_mobile', 'mothers_occupation', 'mothers_photo', 'guardians_name', 'guardians_mobile', 'guardians_email', 'guardians_occupation', 'guardians_relation', 'guardians_photo')
            ->firstOrFail();

        $data['show_permission'] = SmStudentRegistrationField::where('school_id', auth()->user()->school_id ?? app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => 'Parent profile'
            ];
        }

        return response()->json($response);
    }

    public function profileTransport(Request $request)
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['route' => function ($q) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }, 'dormitory', 'vehicle', 'vehicle.driver'])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)->firstOrFail();

        $data['profileTransport'] = new StudentTransportResource($students);
        
        $data['show_permission'] = SmStudentRegistrationField::where('school_id', auth()->user()->school_id ?? app('school')->id)->pluck('is_show', 'field_name');
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
                'message' => 'Student transport detail'
            ];
        }
        return response()->json($response);
    }

    public function profileOthers(Request $request)
    {
        $data['profileOthers'] = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('height', 'weight', 'national_id_no', 'local_id_no', 'bank_name', 'bank_account_no')
            ->where('id', $request->student_id)->firstOrFail();
        $data['show_permission'] = SmStudentRegistrationField::where('school_id', auth()->user()->school_id ?? app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => 'Student other detail'
            ];
        }

        return response()->json($response);
    }

    public function profileDocuments(Request $request)
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)->firstOrFail();

        $data['profileDocuments'] = SmStudentDocument::where('student_staff_id', $students->id)
            ->where('school_id', auth()->user()->school_id)
            ->get()->map(function ($value) {
                return [
                    'id'    => (int)$value->id,
                    'title' => (string)$value->title,
                    'file'  => $value->file ? (string)asset($value->file) : (string)null
                ];
            });

        $data['show_permission'] = SmStudentRegistrationField::where('school_id', auth()->user()->school_id ?? app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => 'Student profile document'
            ];
        }

        return response()->json($response);
    }
}
