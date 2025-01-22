<?php

namespace App\Http\Controllers\api\v2\Auth;

use App\User;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\SmVehicle;
use App\SmHomework;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmGeneralSettings;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\Scopes\SchoolScope;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\SmStudentResourse;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use App\Models\SmStudentRegistrationField;
use App\Http\Resources\StudentRecordResource;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\v2\AssignSubjectResource;
use App\Http\Resources\SmStudentTransportResourse;
use App\Http\Resources\v2\StudentTransportResource;
use App\Http\Resources\v2\Student\ShowPermisionResource;
use Modules\ParentRegistration\Entities\SmStudentRegistration;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => "required",
            'password' => "required"
        ]);

        $user = User::where('username', $request->email)->first();

        if (!$user) {
            $user = User::where('phone_number', $request->email)->first();
        }
        if (!$user) {
            $user = User::where('email', $request->email)->first();
        }
        if ($user) {

            if (!$user->access_status || !$user->active_status) {
                $response = [
                    'success' => false,
                    'data'    => null,
                    'message' => 'You are not allowed, Please contact with administrator.',
                ];
                return response()->json($response, 401);
            }

            if (Hash::check($request->password, $user->password)) {
                $data = [];
                $data['unread_notifications'] = (int)SmNotification::where('user_id', $user->id)->where('is_read', 0)->count();
                $data['user'] = [
                    'id' => (int)$user->id,
                    'full_name' => (string)$user->full_name,
                    'phone_number' => (string)$user->phone_number,
                    'email' => (string)$user->email,
                    'role_id' => (int)$user->role_id,
                    'school_id' => (int)$user->school_id,
                    'is_administrator' => (string)$user->is_administrator,
                    'rtl_ltl' => (int)$user->rtl_ltl,
                ];
                if ($user->role_id == 2) {
                    $student = $user->student;
                    if (!$student) {
                        throw ValidationException::withMessages(['message' => 'Student not found']);
                    }
                    $data['user'] += [
                        'student_id' => (int)$user->student->id
                    ];
                } else if ($user->role_id == 3) {
                    $parent = $user->parent;
                    if (!$parent) {
                        throw ValidationException::withMessages(['message' => 'Student not found']);
                    }
                    $data['user'] += [
                        'parent_id' => (int)$parent->id
                    ];
                } else {
                    $staff = $user->staff;
                    if (!$staff) {
                        throw ValidationException::withMessages(['message' => 'Staff not found']);
                    }
                    $data['user'] += [
                        'staff_id' => (int)$staff->id
                    ];
                }
                $data['TTL_RTL_status'] = (string)'1=RTL,2=TTL';
                $old_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
                $accessToken = $user->createToken('AuthToken')->accessToken;
                $token = $accessToken;
                $data['accessToken'] = (string)'Bearer ' . $token;


                $response = [
                    'success' => true,
                    'data'    => $data,
                    'message' => 'Logged in successfully.',
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'data'    => null,
                    'message' => 'These credentials do not match our records.',
                ];
                return response()->json($response, 401);
                // throw ValidationException::withMessages(['data' => $response]);
            }
        } else {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'These credentials do not match our records.',
            ];
            return response()->json($response, 401);
            // throw ValidationException::withMessages(['data' => $response]);
            // throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        }
    }

    public function logout(Request $request)
    {
        // $user = $request->user();
        // if (!$user) {
        //     throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        // }
        // $user->device_token = null;
        // $user->save();
        // $user->token()->revoke();
        // $data['message'] = "Successfully logged out.";

        // $response = [
        //     'success' => true,
        //     'data'    => $data,
        //     'message' => 'Successfully logged out.',
        // ];

        // return response()->json($response, 200);

        $user = $request->user();
        $user->token()->revoke();

        return response([
            'success' => true,
            'data'    => null,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function DemoUser(Request $request, $role_id)
    {
        $user = User::where(['active_status' => 1, 'role_id' => $role_id])->first();
        if ($user) {
            auth()->login($user);
            $old_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            $accessToken = $user->createToken('AuthToken')->accessToken;
            $token = $accessToken;
            $data['accessToken'] = 'Bearer ' . $token;
            $data['unread_notifications'] = SmNotification::where('user_id', $user->id)->where('is_read', 0)->count();
            $data['user'] = [
                'id' => (int)$user->id,
                'full_name' => (string)$user->full_name,
                'phone_number' => (string)$user->phone_number,
                'email' => (string)$user->email,
                'role_id' => (int)$user->role_id,
                'school_id' => (int)$user->school_id,
                'is_administrator' => (string)$user->is_administrator,
                'rtl_ltl' => (int)$user->rtl_ltl,
            ];
            if ($user->role_id == 2) {
                $data['user'] += [
                    'student_id' => (int)@$user->student->id
                ];
            } else if ($user->role_id == 3) {
                $data['user'] += [
                    'parent_id' => (int)@$user->parent->id
                ];
            } else {
                $data['user'] += [
                    'staff_id' => (int)@$user->staff->id
                ];
            }
            $data['TTL_RTL_status'] = (string)'1=RTL,2=TTL';

            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Logged in successfully.',
            ];

            return response()->json($response, 200);
        } else {
            throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        }
    }

    public function emailVerify(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $emailCheck = User::select('*')->where('email', $request->email)->first();
        if ($emailCheck == "") {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'These credentials do not match our records',
            ];
            return response()->json($response, 401);
            // throw ValidationException::withMessages(['message' => 'Something went wrong']);
        } else {
            $admissionNumber = '';
            $student = SmStudent::where('user_id', $emailCheck->id)->first();
            if ($student) {
                if ($emailCheck->role_id == 2) {
                    $admissionNumber = $student->admission_number;
                }
            }
            $random = Str::random(32);
            $user = User::where('email', $request->email)->first();
            $user->random_code = $random;
            $user->save();

            $data['user_email'] = (string)$request->email;
            $data['id'] = (int)$emailCheck->id;
            $data['random'] = (string)$user->random_code;
            $data['role_id'] = (int)$user->role_id;
            $data['admission_number'] = (int)$admissionNumber;
            @send_mail($user->email, $user->full_name, "password_reset", $data);
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Please check your email.',
            ];
            return response()->json($response, 200);
        }
    }

    public function studentProfile(Request $request, $id)
    {
        $data['student_detail'] = SmStudent::with(['bloodGroup' => function ($q1) {
            $q1->select('id', 'base_setup_name as bloodgroup_name');
        }, 'religion' => function ($q2) {
            $q2->select('id', 'base_setup_name as religion_name');
        }, 'parents' => function ($q3) {
            $q3->select('id', 'fathers_name', 'fathers_mobile', 'fathers_occupation', 'fathers_photo', 'mothers_name', 'mothers_mobile', 'mothers_occupation', 'mothers_photo', 'guardians_name', 'guardians_mobile', 'guardians_email', 'guardians_occupation', 'guardians_relation', 'guardians_photo');
        }, 'route' => function ($q4) {
            $q4->select('id', 'title');
        }, 'vehicle' => function ($q5) {
            $q5->select('id', 'vehicle_no');
        }, 'dormitory' => function ($q6) {
            $q6->select('id', 'dormitory_name');
        }, 'studentDocument' => function ($q7) {
            $q7->select('id', 'title', 'file');
        }])
            ->select('student_photo', 'first_name', 'last_name', 'admission_no', 'date_of_birth', 'age', 'mobile', 'email', 'current_address', 'permanent_address', 'bloodgroup_id', 'religion_id', 'parent_id', 'route_list_id', 'vechile_id', 'dormitory_id', 'height', 'weight', 'national_id_no', 'local_id_no', 'bank_name', 'bank_account_no')->findOrFail($id);
        $data['driver'] = SmVehicle::select('sm_staffs.full_name', 'sm_staffs.mobile')->where('sm_vehicles.id', '=', $data['student_detail']->vechile_id)
            ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
            ->first();

        $data['show_permission'] = SmStudentRegistrationField::where('school_id', app('school')->id)->pluck('is_show', 'field_name');

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function generalSettings()
    {
        $data['system_settings'] = SmGeneralSettings::where('school_id', auth()->user()->school_id ?? app('school')->id)->first();
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
                'message' => 'General setting'
            ];
        }
        return response()->json($response);
    }

    public function studentProfileEdit(Request $request)
    {
        $data['student_detail'] = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('student_photo', 'first_name', 'last_name', 'admission_no', 'date_of_birth', 'age', 'mobile', 'email', 'current_address')
            ->where('id', $request->student_id)->firstOrFail();

        $data['edit_permission'] = SmStudentRegistrationField::select('field_name', 'student_edit')->where('school_id', app('school')->id)->get();

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
                'message' => 'Profile edit'
            ];
        }
        return response()->json($response);
    }

    public function studentProfileUpdate(Request $request)
    {
        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)->first();
            
        $user = User::where('school_id', auth()->user()->school_id)->where('role_id', 2)
            ->where('id', $student->user->id)->first();

        if ($request->filled('first_name')) {
            $student->first_name = $request->first_name;
        }
        if ($request->filled('last_name')) {
            $student->last_name = $request->last_name;
        }
        if ($request->filled('first_name') && $request->filled('last_name')) {
            $student->full_name = $request->first_name . ' ' . $request->last_name;
            $user->full_name = $request->first_name . ' ' . $request->last_name;
        }
        if ($request->filled('date_of_birth')) {
            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        }
        if ($request->filled('current_address')) {
            $student->current_address = $request->current_address;
        }
        if ($request->filled('email_address')) {
            $student->email = $request->email_address;
            $user->email = $request->email_address;
        }
        if ($request->filled('phone_number')) {
            $student->mobile = $request->phone_number;
            $user->phone_number = $request->phone_number;
        }
        $student->save();
        $user->save();

        $data['profilePersonal'] = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'first_name', 'last_name', 'email', 'date_of_birth', 'current_address', 'student_photo', 'mobile')
            ->where('id', $request->student_id)->first();

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
                'message' => 'Profile updated successfully'
            ];
        }
        return response()->json($response);
    }

    public function studentProfileImgUpdate(Request $request)
    {
        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)->first();

        $student_file_destination = 'public/uploads/student/';

        $parent = SmParent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $student->parent_id)->first();

        $student->student_photo = fileUpdate($parent->student_photo, $request->photo, $student_file_destination);

        $student->save();

        $proifle_details = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['bloodGroup', 'religion', 'defaultClass', 'defaultClass.class', 'defaultClass.section'])
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', $student->user_id)->first();
        $data['profilePersonal'] = new SmStudentResourse($proifle_details);

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
                'message' => 'Profile updated successfully'
            ];
        }

        return response()->json($response);
    }

    public function profilePersonal()
    {
        $proifle_details = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->user()->id)->first();

        $data['profilePersonal'] = new SmStudentResourse($proifle_details);

        $data['show_permission'] = SmStudentRegistrationField::where('school_id', app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => 'Personal profile'
            ];
        }
        return response()->json($response);
    }

    public function profileParents()
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->user()->id)->first();

        $data['profileParents'] = SmParent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'fathers_name', 'fathers_mobile', 'fathers_occupation', 'fathers_photo', 'mothers_name', 'mothers_mobile', 'mothers_occupation', 'mothers_photo', 'guardians_name', 'guardians_mobile', 'guardians_email', 'guardians_occupation', 'guardians_relation', 'guardians_photo')
            ->where('id', $students->parent_id)->firstOrFail();
        $data['show_permission'] = SmStudentRegistrationField::where('school_id', app('school')->id)->pluck('is_show', 'field_name');

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
                'message' => "Parent's profile"
            ];
        }
        return response()->json($response);
    }

    public function profileTransport()
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with('route', 'dormitory', 'vehicle.driver')
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->user()->id)->first();
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
                'message' => 'Transport detail'
            ];
        }
        return response()->json($response);
    }

    public function profileOthers()
    {
        $data['profileOthers'] = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->user()->id)
            ->select('height', 'weight', 'national_id_no', 'local_id_no', 'bank_name', 'bank_account_no')
            ->first();
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
                'message' => "Profile's other detail"
            ];
        }
        return response()->json($response);
    }

    public function profileDocuments()
    {
        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', auth()->user()->id)->first();

        $data['profileDocuments'] = SmStudentDocument::where('school_id', auth()->user()->school_id)
            ->select('id', 'title', 'file')
            ->where('student_staff_id', $students->id)
            ->get()->map(function ($document) {
                return [
                    'id'    => (int)$document->id,
                    'title' => (string)$document->title,
                    'file'  => $document->file ? (string)asset($document->file) : (string)null
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
                'message' => 'Profile documents'
            ];
        }
        return response()->json($response);
    }

    public function studentRecord(Request $request)
    {
        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($request->student_id);

        $record = StudentRecord::with('class', 'section')
            ->where(['student_id' => $student->id, 'academic_id' => SmAcademicYear::API_ACADEMIC_YEAR($student->school_id)])
            ->where('school_id', auth()->user()->school_id)
            ->get();

        $data['studentRecords'] = StudentRecordResource::collection($record);

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
                'message' => 'Student record list'
            ];
        }
        return response()->json($response);
    }

    public function updatePassowrdStoreApi(Request $request)
    {
        $this->validate($request, [
            'current_password'  => "required",
            'new_password'      => "required|same:confirm_password|min:6|different:current_password",
            'confirm_password'  => 'required|min:6'
        ]);

        $user = User::where('school_id', auth()->user()->school_id)
            ->where('id', $request->id ?? auth()->id())->first();
        if (password_verify($request->current_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            $result = $user->save();
            if ($result) {
                $response = [
                    'success'  => true,
                    'data' => null,
                    'message' => 'Password has been changed successfully',
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'success'  => false,
                    'data' => null,
                    'message' => 'Something went wrong, please try again',
                ];
                return response()->json($response, 401);
            }
        } else {
            $response = [
                'success'  => false,
                'data' => null,
                'message' => 'Current password not match!',
            ];
            return response()->json($response, 401);
        }
    }

    public function deleteUser(Request $request)
    {
        $user = User::where('school_id', auth()->user()->school_id)
            ->where('id', $request->id)
            ->with('staff')
            ->first();
        if ($user) {
            if ($user->role_id == 2) {
                $user->active_status = 0;
                $student = $user->student;
                $student->active_status = 0;
                $student->save();
                $user->save();
            } elseif ($user->role_id == 3) {
                $user->active_status = 0;
                $parent = $user->parent;
                $parent->active_status = 0;
                $parent->save();
                $user->save();
            } elseif ($user->rol_id != 1) {
                $user->active_status = 0;
                $staff = $user->staff;
                $staff->active_status = 0;
                $staff->save();
                $user->save();
            }
            $response = [
                'success'  => true,
                'data' => null,
                'message' => 'User Deleted',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success'  => false,
                'data' => null,
                'message' => 'Operation Failed !',
            ];
            return response()->json($response, 401);
        }
    }

    public function studentSubject(Request $request)
    {
        $record = StudentRecord::where('school_id', auth()->user()->school_id)
            ->where('id', $request->record_id)
            ->firstOrFail();

        $assign_subject = SmAssignSubject::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('class_id', $record->class_id)
            ->where('section_id', $record->section_id)
            ->where('academic_id', $record->academic_id)
            ->where('school_id', auth()->user()->school_id)->get();

        $data = AssignSubjectResource::collection($assign_subject);

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
                'message' => 'Student subjects'
            ];
        }
        return response()->json($response);
    }

    public function profileDocumentsStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'photo' => "required|mimes:pdf,doc,docx,jpg,jpeg,png"
        ]);

        if ($request->file('photo') != "" && $request->title != "") {
            $document_photo = "";
            if ($request->file('photo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('photo');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    $response = [
                        'success'  => false,
                        'data' => null,
                        'message' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
                    ];
                    return response()->json($response, 401);
                }
                $file = $request->file('photo');
                $document_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/student/document/', $document_photo);
                $document_photo =  'public/uploads/student/document/' . $document_photo;
            }

            $document = new SmStudentDocument();
            $document->title = $request->title;
            $document->student_staff_id = $request->student_id;
            $document->type = 'stu';
            $document->file = $document_photo;
            $document->school_id = auth()->user()->school_id;
            $document->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $document->save();
            $doc = [
                'id'    => (int)$document->id,
                'title' => (string)$document->title,
                'file'  => (string)asset($document->file),
            ];
        }
        if (!$doc) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $doc,
                'message' => 'Document stored successfully'
            ];
        }
        return response()->json($response);
    }

    public function deleteDocument(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:sm_student_documents,id'
        ], [
            'document_id.exists' => 'The selected document is invalid.'
        ]);

        if (checkAdmin()) {
            $document = SmStudentDocument::find($request->document_id);
        } else {
            $document = SmStudentDocument::where('id', $request->document_id)
                ->where('school_id', auth()->user()->school_id)->first();
        }
        if ($document->file) {
            unlink($document->file);
        }
        $result = $document->delete();

        if (!$result) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Document deleted successfully'
            ];
        }
        return response()->json($response);
    }

    public function aboutApi(request $request)
    {
        $about = SmGeneralSettings::with('aboutPage')
            ->where('school_id', auth()->user()->school_id)
            ->first();
        $data = [
            'about_details' => (string)@$about->aboutPage->main_description,
            'address'       => (string)$about->address,
            'phone_no'      => (string)$about->phone,
            'email'         => (string)$about->email
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
                'data'    => $data,
                'message' => 'About school'
            ];
        }
        return response()->json($response);
    }
}
