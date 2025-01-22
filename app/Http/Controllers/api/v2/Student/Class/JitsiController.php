<?php

namespace App\Http\Controllers\api\v2\Student\Class;

use App\User;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Jitsi\Entities\JitsiMeeting;
use Modules\Jitsi\Entities\JitsiVirtualClass;
use App\Http\Resources\v2\Class\Student\Jitsi\MeetingResource;
use Modules\Jitsi\Entities\JitsiSetting;

class JitsiController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id == 4) {
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')
                ->whereHas('teachers', function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })->get();

            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } elseif (auth()->user()->role_id == 1 || auth()->user()->role_id == 5) {
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->get();

            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } elseif (auth()->user()->role_id == 2) {
            $user = User::where('id', auth()->id())->first();
            $id = $user->student->id;
            $studentRecord = StudentRecord::where('student_id', $id)->first();
            $class_id = $studentRecord->class_id;
            $section_id = $studentRecord->section_id;
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->where('class_id', $class_id)->where('section_id', $section_id)->orwhere('section_id', null)->get();


            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } else {
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) {
                return  $query->whereHas('students', function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                });
            })->get();

            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        }
        $meetings = MeetingResource::collection($data['meetings']);
        $response = [
            'success' => true,
            'data' => $meetings,
            'message' => 'Operation successful.'
        ];
        return response()->json($response, 200);
    }

    public function meetings()
    {
        if (auth()->user()->role_id == 4) {
            $meetings =   JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
                ->orWhere('created_by', auth()->user()->id)

                ->get();

            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } elseif (auth()->user()->role_id == 1 || auth()->user()->role_id == 5) {
            $meetings =  JitsiMeeting::orderBy('id', 'DESC')->get();

            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } else {
            $meetings = JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return  $query->where('user_id', auth()->user()->id);
            })

                ->get();
            foreach ($meetings as $meeting) {
                if (auth()->user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (auth()->user()->role_id == 1 || auth()->user()->id == $meeting->created_by || $teahcer_id == auth()->user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        }
        $meetings = MeetingResource::collection($data['meetings']);
        $response = [
            'success' => true,
            'data' => $meetings,
            'message' => 'Operation successful.'
        ];
        return response()->json($response, 200);
    }

    public function settings()
    {
        try {
            $data = JitsiSetting::first();
            return ApiBaseMethod::sendResponse($data, null);
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
}
