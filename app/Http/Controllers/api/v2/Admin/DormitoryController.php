<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmRoomList;
use App\SmRoomType;
use App\SmAcademicYear;
use App\SmDormitoryList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\ActiveStatusSchoolScope;
use App\Http\Resources\v2\Admin\DormitoryRoomListResource;

class DormitoryController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'dormitory_name' => "required|max:200|unique:sm_dormitory_lists",
            'type' => "required",
            'address' => "required",
            'intake' => "required",
            'description' => "sometimes|nullable|max:200"
        ]);

        $dormitory_list = new SmDormitoryList();
        $dormitory_list->dormitory_name = $request->dormitory_name;
        $dormitory_list->type = $request->type;
        $dormitory_list->address = $request->address;
        $dormitory_list->intake = $request->intake;
        $dormitory_list->description = $request->description;
        $dormitory_list->school_id = auth()->user()->school_id;
        if (moduleStatusCheck('University')) {
            $dormitory_list->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        } else {
            $dormitory_list->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        }
        $dormitory_list->save();
        if (!$dormitory_list) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Dormitory stored successfully'
            ];
        }
        return response()->json($response);
    }

    public function index()
    {
        $room_lists = SmRoomList::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->with('dormitory', 'roomType')
            ->where('school_id', auth()->user()->school_id)
            ->get();

        $data = DormitoryRoomListResource::collection($room_lists);

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
                'message' => 'Dormitory room list'
            ];
        }
        return response()->json($response);
    }

    public function dormitoryRoomStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'dormitory_id' => "required|integer",
            'room_type_id' => "required|integer",
            'number_of_bed' => "required|max:2",
            'cost_per_bed' => "required|max:11",
            'description' => 'sometimes|nullable|max:200'
        ]);

        $room_list = new SmRoomList();
        $room_list->name = $request->name;
        $room_list->dormitory_id = $request->dormitory_id;
        $room_list->room_type_id = $request->room_type_id;
        $room_list->number_of_bed = $request->number_of_bed;
        $room_list->cost_per_bed = $request->cost_per_bed;
        $room_list->description = $request->description;
        $room_list->school_id = auth()->user()->school_id;
        if (moduleStatusCheck('University')) {
            $room_list->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        } else {
            $room_list->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        }
        $room_list->save();

        if (!$room_list) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Dormitory room stored successfully'
            ];
        }
        return response()->json($response);
    }

    public function roomType()
    {
        $data = SmRoomType::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'type')
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
                'message' => 'Room type list'
            ];
        }
        return response()->json($response);
    }

    public function dormitoryList()
    {
        $data = SmDormitoryList::withoutGlobalScope(ActiveStatusSchoolScope::class)
        ->where('school_id', auth()->user()->school_id)
        ->select('id', 'dormitory_name')
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
                'message' => 'Dormitory list'
            ];
        }
        return response()->json($response);
    }
}
