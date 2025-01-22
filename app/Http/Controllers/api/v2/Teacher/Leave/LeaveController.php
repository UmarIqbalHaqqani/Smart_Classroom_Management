<?php

namespace App\Http\Controllers\api\v2\Teacher\Leave;

use App\User;
use Exception;
use App\SmStaff;
use App\SmLeaveDefine;
use App\SmAcademicYear;
use App\SmLeaveRequest;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Traits\NotificationSend;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveApprovedNotification;
use App\Http\Resources\v2\Teacher\Leave\AppliedLeveList;
use App\Http\Resources\v2\Teacher\Leave\AppliedLeveListResource;

class LeaveController extends Controller
{
    use NotificationSend;

    public function list()
    {
        $user = auth()->user();

        if ($user) {
            $pending = SmLeaveRequest::with(['leaveType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
                ->where('approve_status', 'P')
                ->where('staff_id', $user->id)
                ->where('role_id', $user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'DESC')
                ->get();

            $approved = SmLeaveRequest::with(['leaveType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
                ->where('approve_status', 'A')
                ->where('staff_id', $user->id)
                ->where('role_id', $user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'DESC')
                ->get();

            $rejected = SmLeaveRequest::with(['leaveType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
                ->where('approve_status', 'C')
                ->where('staff_id', $user->id)
                ->where('role_id', $user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'DESC')
                ->get();
        }

        $data['pending'] = AppliedLeveListResource::collection($pending);
        $data['approved'] = AppliedLeveListResource::collection($approved);
        $data['rejected'] = AppliedLeveListResource::collection($rejected);

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
                'message' => 'Leave list'
            ];
        }
        return response()->json($response);
    }

    public function types()
    {
        $user = auth()->user();
        if ($user) {
            $my_leaves = SmLeaveDefine::withoutGlobalScope(ActiveStatusSchoolScope::class)->with(['leaveType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
                ->where('user_id', $user->id)
                ->where('role_id', $user->role_id)
                ->where('school_id', auth()->user()->school_id)
                ->where('active_status', 1)
                ->get()
                ->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'type' => @$type->leaveType->type,
                    ];
                });
        }
        $response = [
            'success' => true,
            'data' => $my_leaves,
            'messege' => 'Operation Successfull.',
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type_id' => 'required'
        ]);

        $leaveDefine = SmLeaveDefine::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('id', $request->type_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->id())
            ->where('role_id', auth()->user()->role_id)
            ->where('active_status', 1)->first();
        $path = 'public/uploads/leave_request/';
        $apply_leave = new SmLeaveRequest();
        $apply_leave->staff_id = auth()->user()->id;
        $apply_leave->role_id = auth()->user()->role_id;
        $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
        $apply_leave->leave_define_id = $request->type_id;
        $apply_leave->type_id = $leaveDefine->type_id;
        $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
        $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
        $apply_leave->approve_status = 'P';
        $apply_leave->reason = $request->reason;
        if ($request->file('attach_file')) {
            $apply_leave->file = fileUpload($request->attach_file, $path);
        }
        $apply_leave->school_id = auth()->user()->school_id;
        $apply_leave->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $apply_leave->save();

        try {
            $data['to_date'] = $apply_leave->leave_to;
            $data['name'] = $apply_leave->user->full_name;
            $data['from_date'] = $apply_leave->leave_from;
            $data['teacher_name'] = $apply_leave->user->full_name;
            $this->sent_notifications('Leave_Apply', (array)$apply_leave->user->id, $data, ['Teacher']);

            $staffInfo = SmStaff::where('user_id', auth()->user()->id)->first();
            $compact['slug'] = 'staff';
            $compact['user_email'] = auth()->user()->email;
            $compact['staff_name'] = auth()->user()->full_name;
            @send_sms($staffInfo->mobile, 'staff_leave_appllication', $compact);


            $user = User::where('role_id', 1)->first();
            $notification = new SmNotification;
            $notification->user_id = $user->id;
            $notification->role_id = $user->role_id;
            $notification->date = date('Y-m-d');
            $notification->message = app('translator')->get('leave.leave_request');
            $notification->school_id = auth()->user()->school_id;
            $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $notification->save();
            Notification::send($user, new LeaveApprovedNotification($notification));
        } catch (Exception $e) {
            //
        }

        $data = [
            'id' => (int)$apply_leave->id,
            'type_id' => (int)$apply_leave->leave_define_id,
            'apply_date' => (string)$apply_leave->apply_date,
            'leave_from' => (string)$apply_leave->leave_from,
            'leave_to' => (string)$apply_leave->leave_to,
            'reason' => (string)$apply_leave->reason,
            'attach_file' => $apply_leave->file ? (string)asset($apply_leave->file) : (string)null
        ];

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
                'message' => 'Leave store successfully'
            ];
        }
        return response()->json($response);
    }
}
