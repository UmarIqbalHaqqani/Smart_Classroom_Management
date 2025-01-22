<?php

namespace App\Http\Controllers\api\v2\Leave;

use App\Http\Controllers\Controller;
use App\Http\Resources\RemainingLeaveResource;
use App\Http\Resources\v2\ApplyLeaveResource;
use App\Models\User;
use App\Notifications\LeaveApprovedNotification;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\SchoolScope;
use App\SmAcademicYear;
use App\SmGeneralSettings;
use App\SmLeaveRequest;
use App\SmLeaveDefine;
use App\SmNotification;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Traits\NotificationSend;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveController extends Controller
{
    use NotificationSend;

    public function remainingLeave(Request $request)
    {
        $user = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['user' => function ($q) {
                $q->where('school_id', auth()->user()->school_id);
            }])
            ->where('id', $request->student_id)->firstOrFail();

        if ($user) {
            $my_leaves = SmLeaveDefine::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])
                ->where('role_id', $user->user->role_id)
                ->where('user_id', $user->user->id)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)->get();

            $data = RemainingLeaveResource::collection($my_leaves);
        }
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
                'message' => 'Remaining leave list'
            ];
        }
        return response()->json($response);
    }

    public function applyLeave(Request $request)
    {
        $user = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['user' => function ($q) {
                $q->where('school_id', auth()->user()->school_id);
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)
            ->firstOrFail();

        if ($user) {
            $pending = SmLeaveRequest::with(['leaveDefine' => function ($q) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])->where('school_id', auth()->user()->school_id);
            }])->where('staff_id', $user->user->id)
                ->where('approve_status', 'P')
                ->where('role_id', $user->user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)->orderBy('id','DESC')->get();

            $approved = SmLeaveRequest::with(['leaveDefine' => function ($q) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])->where('school_id', auth()->user()->school_id);
            }])->where('staff_id', $user->user->id)
                ->where('approve_status', 'A')
                ->where('role_id', $user->user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)->orderBy('id','DESC')->get();

            $rejected = SmLeaveRequest::with(['leaveDefine' => function ($q) {
                $q->withoutGlobalScope(ActiveStatusSchoolScope::class)->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])->where('school_id', auth()->user()->school_id);
            }])->where('staff_id', $user->user->id)
                ->where('approve_status', 'C')
                ->where('role_id', $user->user->role_id)
                ->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)->orderBy('id','DESC')->get();

            $data['pending'] = ApplyLeaveResource::collection($pending);
            $data['approved'] = ApplyLeaveResource::collection($approved);
            $data['rejected'] = ApplyLeaveResource::collection($rejected);
        }

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed',
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Applyed leave list',
            ];
        }

        return response()->json($response);
    }

    public function leaveStore(Request $request)
    {
        try {
            $this->validate($request, [
                'apply_date'    => "required",
                'leave_type'    => "required",
                'leave_from'    => 'nullable|required_with:leave_to|before_or_equal:leave_to',
                'leave_to'      => "nullable|after_or_equal:leave_from",
                'attach_file'   => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt"
            ]);
    
            $maxFileSize = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first('file_size')->file_size;
            $file = $request->file('attach_file');
    
            if ($file) {
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    return response()->json([
                        'success' => false,
                        'data'    => null,
                        'message' => 'Max upload file size ' . $maxFileSize . ' MB is set in system',
                    ], 401);
                }
            }
    
            $fileName = "";
            if ($request->file('attach_file')) {
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }
    
            $student = SmStudent::withoutGlobalScope(SchoolScope::class)
                ->with(['user' => function ($q) {
                    $q->where('school_id', auth()->user()->school_id);
                }])
                ->where('school_id', auth()->user()->school_id)
                ->where('id', $request->student_id)
                ->firstOrFail();
    
            $user = $student->user ?? null;
            $login_id = $user ? $user->id : $request->login_id;
            $role_id = $user ? $user->role_id : $request->role_id;
    
            $leaveDefine = SmLeaveDefine::withoutGlobalScopes([ActiveStatusSchoolScope::class, AcademicSchoolScope::class])
                ->with('leaveType:id')
                ->find($request->leave_type, ['id', 'type_id']);
    
            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id       = $login_id;
            $apply_leave->role_id        = $role_id;
            $apply_leave->apply_date     = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->type_id        = @$leaveDefine->leaveType->id;
            $apply_leave->leave_from     = $request->leave_from ? date('Y-m-d', strtotime($request->leave_from)) : null;
            $apply_leave->leave_to       = $request->leave_to ? date('Y-m-d', strtotime($request->leave_to)) : null;
            $apply_leave->approve_status = 'P';
            $apply_leave->reason         = $request->reason;
            $apply_leave->file           = $fileName;
            $apply_leave->academic_id    = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $apply_leave->school_id      = auth()->user()->school_id;
            $result = $apply_leave->save();
    
            try {
                $data['name'] = $apply_leave->user->full_name;
                $data['class_id'] = $apply_leave->student->studentRecord->class_id;
                $data['section_id'] = $apply_leave->student->studentRecord->section_id;
                $records = $this->studentRecordInfo($request->class, $request->section)->pluck('studentDetail.user_id');
                $this->sent_notifications('Leave_Apply', $records, $data, ['Student']);
    
                $user = User::where('role_id', 1)->first();
                $notification = new SmNotification();
                $notification->user_id = $user->id;
                $notification->role_id = $user->role_id;
                $notification->date = date('Y-m-d');
                $notification->message = app('translator')->get('leave.leave_request');
                $notification->school_id = auth()->user()->school_id;
                $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $notification->save();
                Notification::send($user, new LeaveApprovedNotification($notification));
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'data'    => null,
                    'message' => 'Notification failed to send.',
                ], 500);
            }
    
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'data'    => null,
                    'message' => 'Operation Failed',
                ], 500);
            } else {
                return response()->json([
                    'success' => true,
                    'data'    => null,
                    'message' => 'Leave applied successfully',
                ]);
            }
    
        } catch (ValidationException $e) {
            // Handle Validation Errors
            return response()->json([
                'success' => false,
                'message' => 'Validation error occurred',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            // Handle General Errors
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function studentLeaveEdit(Request $request)
    {
        $data['apply_leave'] = SmLeaveRequest::select('id', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'leave_define_id')
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->leave_request_id)
            ->firstOrFail();
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
                'message' => 'Edit leave'
            ];
        }
        return response()->json($response);
    }

    public function update(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $request->validate([
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => 'nullable|required_with:leave_to|before:leave_to',
            'leave_to' => "nullable|after:leave_from",
            'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt"
        ]);

        $maxFileSize = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first('file_size')->file_size;
        $file = $request->file('attach_file');
        $fileSize = filesize($file);
        $fileSizeKb = ($fileSize / 1000000);
        if ($fileSizeKb >= $maxFileSize) {
            $response = [
                'status'  => false,
                'data' => null,
                'message' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
            ];
            return response()->json($response, 401);
        }
        $fileName = "";
        if ($request->file('attach_file') != "") {
            $apply_leave = SmLeaveRequest::where('school_id', auth()->user()->school_id)->where('id', $request->id)->first();
            if (file_exists($apply_leave->file)) {
                unlink($apply_leave->file);
            }

            $file = $request->file('attach_file');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/leave_request/', $fileName);
            $fileName = 'public/uploads/leave_request/' . $fileName;
        }

        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['user' => function ($q) {
                $q->where('school_id', auth()->user()->school_id);
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)
            ->firstOrFail();

        $user = $student->user;
        if ($user) {
            $login_id = $user->id;
            $role_id = $user->role_id;
        } else {
            $login_id = $request->login_id;
            $role_id = $request->role_id;
        }

        $apply_leave = SmLeaveRequest::where('school_id', auth()->user()->school_id)->where('id', $request->id)->first();
        $apply_leave->staff_id = $login_id;
        $apply_leave->role_id = $role_id;
        $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
        $apply_leave->leave_define_id = $request->leave_type;
        $apply_leave->leave_from = $request->leave_from ? date('Y-m-d', strtotime($request->leave_from)) : null;
        $apply_leave->leave_to = $request->leave_to ? date('Y-m-d', strtotime($request->leave_to)) : null;
        $apply_leave->approve_status = 'P';
        $apply_leave->reason = $request->reason;
        if ($fileName != "") {
            $apply_leave->file = $fileName;
        }
        $result = $apply_leave->save();

        if (!$result) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $apply_leave,
                'message' => 'Leave updated successfully'
            ];
        }
        return response()->json($response);
    }

    public function leaveType(Request $request)
    {
        if ($request->role_id == 3) {
            $roleId = 2;
            $student_id =  $request->student_id;

            if (!$student_id) {
                return response()->json([
                    'success' => false,
                    'data'    => null,
                    'message' => 'Student id is required'
                ]);
            }
            
            $user_id = SmStudent::withoutGlobalScope(SchoolScope::class)
                ->where('id', $student_id)
                ->firstOrFail()->user_id;

            $allLeaveType = SmLeaveDefine::withoutGlobalScopes([ActiveStatusSchoolScope::class])
            ->with(['leaveType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
            ->where('role_id', $roleId)
            ->when(auth()->id(), function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })
            ->where('active_status', 1)
            ->where('school_id', auth()->user()->school_id)->get();
        } else {
            $roleId = $request->role_id ?? auth()->user()->role_id;
            $allLeaveType = SmLeaveDefine::withoutGlobalScopes([ActiveStatusSchoolScope::class])
                ->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])
                ->where('role_id', $roleId)
                ->when(auth()->id(), function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('active_status', 1)
                ->where('school_id', auth()->user()->school_id)->get();
        }


        /* if ($user) {
        } else {
            $allLeaveType = SmLeaveDefine::withoutGlobalScopes([ActiveStatusSchoolScope::class])
                ->with(['leaveType' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
                }])
                ->where('role_id', $request->role_id)
                ->where('active_status', 1)
                ->where('school_id', auth()->user()->school_id)->get();
        } */
        $leave_type = [];
        if ($allLeaveType) {
            foreach ($allLeaveType as $item) {
                $leave_type[] =  [
                    'id' => (int)$item->id,
                    'leave_type' => (string)@$item->leaveType->type,
                ];
            }
        }
        $data['leave_type'] = $leave_type;

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
                'message' => 'Your leave type list'
            ];
        }
        return response()->json($response);
    }
}
