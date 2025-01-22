<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Admin\LeaveListResource;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\SmLeaveRequest;
use App\SmNotification;
use Exception;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{

    public function allPendingList(Request $request)
    {
        $pendingRequest = SmLeaveRequest::with('leaveDefine', 'user', 'leaveType')
            ->where('sm_leave_requests.active_status', 1)
            ->where('sm_leave_requests.approve_status', 'P')
            ->where('sm_leave_requests.school_id', $request->user()->school_id)
            ->orderBy('id', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $data = LeaveListResource::collection($pendingRequest);

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
                'message' => 'Pending leave list'
            ];
        }

        return response()->json($response);
    }
    public function allAprroveList(Request $request)
    {
        $aprroveRequest = SmLeaveRequest::with('leaveDefine', 'user', 'leaveType')
            ->where('sm_leave_requests.active_status', 1)
            ->where('sm_leave_requests.approve_status', 'A')
            ->where('sm_leave_requests.school_id', $request->user()->school_id)
            ->orderBy('id', 'DESC')
            ->get();
        $data = LeaveListResource::collection($aprroveRequest);
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
                'message' => 'Approved leave list'
            ];
        }
        return response()->json($response);
    }
    public function allRejectedList(Request $request)
    {
        $rejectedRequest = SmLeaveRequest::with('leaveDefine', 'user', 'leaveType')
            ->where('sm_leave_requests.active_status', 1)
            ->where('sm_leave_requests.approve_status', 'C')
            ->where('sm_leave_requests.school_id', $request->user()->school_id)
            ->orderBy('id', 'DESC')
            ->get();
        $data = LeaveListResource::collection($rejectedRequest);

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
                'message' => 'Rejected leave list'
            ];
        }

        return response()->json($response);
    }

    public function updateApproveLeave(Request $request)
    {
        $leave_request_data = SmLeaveRequest::find($request->leave_id);
        if (!$leave_request_data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No data found',
            ];
            $responseStatus = 301;
        }

        if ($request->approve_status == 'P' || $request->approve_status == 'A' || $request->approve_status == 'C') {
            $role_id = $leave_request_data->role_id;
            $leave_request_data->approve_status = $request->approve_status;
            $leave_request_data->save();

            try {
                $notification = new SmNotification;
                $notification->user_id = @$leave_request_data->user->id;
                $notification->role_id = $role_id;
                $notification->school_id = $leave_request_data->school_id;
                $notification->academic_id = $leave_request_data->academic_id;
                $notification->date = date('Y-m-d');
                $notification->message = 'Leave status updated';
                $notification->save();

                if ($leave_request_data->approve_status == 'A') {
                    $status = 'Leave_Approved';
                } elseif ($leave_request_data->approve_status == 'C') {
                    $status = 'Leave_Declined';
                }

                $data['to_date'] = $leave_request_data->leave_to;
                $data['name'] = $leave_request_data->user->full_name;
                $data['from_date'] = $leave_request_data->leave_from;
                $data['teacher_name'] = $leave_request_data->user->full_name;
                if ($leave_request_data->role_id == 2) {
                    $this->sent_notifications($status, (array)$leave_request_data->user->id, $data, ['Student', 'Parent']);
                }
                if ($leave_request_data->role_id == 4) {
                    $this->sent_notifications($status, (array)$leave_request_data->user->id, $data, ['Teacher']);
                }
            } catch (Exception $e) {
                //
            }

            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Leave status updated',
            ];
            $responseStatus = 200;
        } else {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Invalid status',
            ];
            $responseStatus = false;
        }
        return response()->json($response, $responseStatus);
    }
}
