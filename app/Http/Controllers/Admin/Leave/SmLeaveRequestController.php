<?php

namespace App\Http\Controllers\Admin\Leave;

use App\User;
use Exception;
use App\SmStaff;
use App\tableList;
use App\ApiBaseMethod;
use App\SmLeaveDefine;
use App\SmLeaveRequest;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Traits\NotificationSend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveApprovedNotification;
use App\Http\Requests\Admin\Leave\SmLeaveRequest as FormRequest;

class SmLeaveRequestController extends Controller
{
    use NotificationSend;
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }


    public function index(Request $request)
    {

        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::with('leaveType')->where('user_id', $user->id)->where('role_id', $user->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::with('leaveDefine')->where('role_id', $user->role_id)->where('active_status', 1)
                    ->where('school_id', Auth::user()->school_id)->has('leaveDefine')->where('staff_id', Auth::user()->id)->get();

                $leave_types = $my_leaves->where('active_status', 1);
            }

            return view('backEnd.humanResource.apply_leave', compact('apply_leaves', 'leave_types', 'my_leaves'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(FormRequest $request)
    {
        try {
            $leaveDefine = SmLeaveDefine::where('id', $request->leave_define_id)->first();
            $path = 'public/uploads/leave_request/';
            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = auth()->user()->id;
            $apply_leave->role_id = auth()->user()->role_id;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_define_id;
            $apply_leave->type_id = $leaveDefine->type_id;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($request->file('attach_file') != "") {
                $apply_leave->file = fileUpload($request->attach_file, $path);
            }
            $apply_leave->school_id = auth()->user()->school_id;
            $apply_leave->academic_id = getAcademicId();
            $apply_leave->save();

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

            try {
                $user = User::where('role_id', 1)->first();
                $notification = new SmNotification;
                $notification->user_id = $user->id;
                $notification->role_id = $user->role_id;
                $notification->date = date('Y-m-d');
                $notification->message = app('translator')->get('leave.leave_request');
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();
                Notification::send($user, new LeaveApprovedNotification($notification));
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (Exception $e) {

            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }


    public function show(Request $request, $id)
    {


        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::where('user_id', $user->id)->where('role_id', $user->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::with('leaveDefine')->where('role_id', $user->role_id)->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)->has('leaveDefine')->where('staff_id', Auth::user()->id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $user->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            }

            $apply_leave = SmLeaveRequest::find($id);
            return view('backEnd.humanResource.apply_leave', compact('apply_leave', 'apply_leaves', 'leave_types', 'my_leaves'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(FormRequest $request)
    {
        try {
            $path = 'public/uploads/leave_request/';
            $apply_leave = SmLeaveRequest::find($request->id);
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($request->file != "") {
                $apply_leave->file = fileUpdate($apply_leave->file, $request->file, $path);
            }
            $apply_leave->save();
            Toastr::success('Operation successful', 'Success');
            return redirect('apply-leave');
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewLeaveDetails(Request $request, $id)
    {
        try {
            $leaveDetails = SmLeaveRequest::find($id);
            $apply = "";
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leaveDetails'] = $leaveDetails->toArray();
                $data['apply'] = $apply;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.viewLeaveDetails', compact('leaveDetails', 'apply'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function destroy(Request $request, $id)
    {

        $tables = tableList::getTableList('leave_request_id', $id);

        try {
            if ($tables == null) {
                $apply_leave = SmLeaveRequest::find($id);

                if ($apply_leave->file != "" && file_exists($apply_leave->file)) {
                    unlink($apply_leave->file);
                }

                $apply_leave->delete();

                Toastr::success('Operation successful', 'Success');
                if (Auth::user()->role_id == 1) {
                    return redirect('pending-leave');
                } else {
                    return redirect('apply-leave');
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (Exception $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
        }
    }
    public function deleteLeave(Request $request)
    {
        $id = $request->id;
        $tables = tableList::getTableList('leave_request_id', $id);

        try {
            if ($tables == null) {
                $apply_leave = SmLeaveRequest::find($id);

                if ($apply_leave->file != "" && file_exists($apply_leave->file)) {
                    unlink($apply_leave->file);
                }

                $apply_leave->delete();

                Toastr::success('Operation successful', 'Success');
                if (Auth::user()->role_id == 1) {
                    return redirect('pending-leave');
                } else {
                    return redirect('apply-leave');
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (Exception $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
        }
    }
}
