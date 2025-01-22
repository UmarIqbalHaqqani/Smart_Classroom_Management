<?php

namespace App\Http\Controllers\api\v2\Student\Class;

use App\User;
use App\SmParent;
use Carbon\Carbon;
use App\ApiBaseMethod;
use App\SmClassSection;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\DB;
use MacsiDigital\Zoom\Facades\Zoom;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Zoom\Entities\ZoomMeeting;
use Modules\Zoom\Entities\ZoomSetting;
use App\Scopes\ActiveStatusSchoolScope;
use Modules\Zoom\Entities\VirtualClass;
use Modules\Lms\Entities\LessonComplete;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Zoom\Http\Requests\VirtualClassRequest;
use App\Http\Resources\v2\Class\Student\Zoom\ClassResource;
use App\Http\Resources\v2\Class\Student\Zoom\MeetingResource;
use App\Http\Resources\v2\Class\Student\Zoom\TeacherResource;
use Modules\Zoom\Repositories\Interfaces\VirtualClassRepositoryInterface;

class ZoomController extends Controller
{
    protected $virtualClassRepository;
    public function __construct(
        VirtualClassRepositoryInterface $virtualClassRepository
    ) {
        $this->virtualClassRepository = $virtualClassRepository;
    }

    public function makeMeeting(Request $request)
    {
        $id = $request->meeting_id;

        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', auth()->user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);

        $meeting = VirtualClass::where('meeting_id', $id)->first();
        $data['url'] = $meeting->url;
        $data['topic'] = $meeting->topic;
        $data['password'] = $meeting->password;

        if (moduleStatusCheck('Lms')) {
            if (!is_null($meeting->course_id)) {
                $checkExist = LessonComplete::when(!is_null($meeting->chapter_id), function ($q) use ($meeting) {
                    $q->where('chapter_id', $meeting->chapter_id);
                })

                    ->when(!is_null($meeting->lesson_id), function ($q) use ($meeting) {
                        $q->where('lesson_id', $meeting->lesson_id);
                    })
                    ->where('virtual_class_id', $meeting->id)
                    ->where('course_id', $meeting->course_id)
                    ->where('student_id', auth()->user()->id)
                    ->first();

                if (is_null($checkExist)) {
                    $new = new LessonComplete();
                    $new->course_id = $meeting->course_id;
                    $new->chapter_id = $meeting->chapter_id;
                    $new->lesson_id = $meeting->lesson_id;
                    $new->virtual_class_id = $meeting->id;
                    $new->student_id = auth()->user()->id;
                    $new->active_status = 1;
                    $new->save();
                }
            }
        }

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Operation successful.'
        ];

        return response()->json($response, 200);
    }

    public function memberList(Request $request)
    {
        $user_role = $request->user_role_id;

        $data['users_list'] = User::where('role_id', $user_role)->select('id', 'full_name', 'username', 'email', 'is_administrator', 'role_id')->get();
        return ApiBaseMethod::sendResponse($data, null);
    }

    public function storeMeeting(VirtualClassRequest $request)
    {
        $class = $this->virtualClassRepository->classStore($request);

        return $class;
    }

    public function editMeeting(Request $request)
    {
        $meeting_id = $request->meeting_id;
        $user_id = $request->user_id;

        $user_info = User::find($user_id);
        return 'success';
        $data['default_settings'] =  ZoomSetting::first()->makeHidden('api_key', 'secret_key', 'created_at', 'updated_at');
        $data['roles'] = InfixRole::where(function ($q) use ($user_info) {
            $q->where('school_id',  $user_info->school_id)->orWhere('type', 'System');
        })->whereNotIn('id', [1, 2])->get();

        if ($user_info->role_id == 4) {
            $data['default_settings'] = ZoomSetting::first();
            $data['meetings'] = ZoomMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) use ($user_info) {
                return $query->where('user_id',  $user_info->id);
            })
                ->orWhere('created_by',  $user_info->id)
                ->where('status', 1)
                ->get();
        } elseif ($user_info->role_id == 1) {
            $data['meetings'] = ZoomMeeting::orderBy('id', 'DESC')->get();
        } else {
            $data['meetings'] = ZoomMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query)  use ($user_info) {
                return  $query->where('user_id',  $user_info->id);
            })
                ->where('status', 1)
                ->get();
        }

        $data['editdata'] = ZoomMeeting::findOrFail($meeting_id);
        $data['user_type'] = $data['editdata']->participates[0]['role_id'];
        $data['userList'] = User::where('role_id', $data['user_type'])
            ->where('school_id', $user_info->school_id)
            ->select('id', 'full_name', 'role_id', 'school_id')->get();
        if ($user_info->role_id != 1) {
            if ($user_info->id != $data['editdata']->created_by) {
                return ApiBaseMethod::sendError('Meeting is created by other, you could not modify !');
            }
        }

        return ApiBaseMethod::sendResponse($data, null);
    }

    public function updateMeeting(Request $request)
    {
        $this->validate($request, [
            'participate_ids' => 'required|array',
            'member_type' => 'required',
            'meeting_id' => 'required',
            'creator_id' => 'required',
            'topic' => 'required',
            'description' => 'nullable',
            'password' => 'required',
            'attached_file' => 'nullable|mimes:jpeg,png,jpg,doc,docx,pdf,xls,xlsx',
            'time' => 'required',
            'durration' => 'required',
            'join_before_host' => 'required',
            'host_video' => 'required',
            'participant_video' => 'required',
            'mute_upon_entry' => 'required',
            'waiting_room' => 'required',
            'audio' => 'required',
            'auto_recording' => 'nullable',
            'approval_type' => 'required',
            'is_recurring' => 'required',
            'recurring_type' => 'required_if:is_recurring,1',
            'recurring_repect_day' => 'required_if:is_recurring,1',
            'recurring_end_date' => 'required_if:is_recurring,1'
        ]);

        $creator_info = User::find($request->creator_id);
        $system_meeting = ZoomMeeting::findOrFail($request->meeting_id);

        if ($this->isTimeAvailableForMeeting($request, $id = $request->meeting_id)) {
            return ApiBaseMethod::sendError('Time is not available !');
        }

        $users = Zoom::user()->where('status', 'active')->setPaginate(false)->setPerPage(300)->get()->toArray();
        $profile = $users['data'][0];
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));
        $GSetting = SmGeneralSettings::where('school_id', $creator_info->school_id)->first();
        $meeting = Zoom::meeting()->find($system_meeting->meeting_id)->make([
            "topic" => $request['topic'],
            "type" => $request['is_recurring'] == 1 ? 8 : 2,
            "duration" => $request['durration'],
            "timezone" => $GSetting->timeZone->time_zone,
            "start_time" => new Carbon($start_date),
            "password" => $request['password'],
        ]);

        $meeting->settings()->make([
            'join_before_host'  => $this->setTrueFalseStatus($request['join_before_host']),
            'host_video'        => $this->setTrueFalseStatus($request['host_video']),
            'participant_video' => $this->setTrueFalseStatus($request['participant_video']),
            'mute_upon_entry'   => $this->setTrueFalseStatus($request['mute_upon_entry']),
            'waiting_room'      => $this->setTrueFalseStatus($request['waiting_room']),
            'audio'             => $request['audio'],
            'auto_recording'    => $request->has('auto_recording') ? $request['auto_recording'] : 'none',
            'approval_type'     => $request['approval_type'],
        ]);

        if ($request['is_recurring'] == 1) {
            $end_date  = Carbon::parse($request['recurring_end_date'])->endOfDay();
            $meeting->recurrence()->make([
                'type' =>  $request['recurring_type'],
                'repeat_interval' => $request['recurring_repect_day'],
                'end_date_time' => $end_date
            ]);
        }

        $meeting_details  = Zoom::user()->find($profile['id'])->meetings()->save($meeting);

        DB::beginTransaction();

        $system_meeting->update([
            'topic' =>  $request['topic'],
            'description' =>  $request['description'],
            'date_of_meeting' =>  $request['date'],
            'time_of_meeting' =>  $request['time'],
            'meeting_duration' =>  $request['durration'],
            'password' =>  $request['password'],

            'host_video' => $request['host_video'],
            'participant_video' => $request['participant_video'],
            'join_before_host' => $request['join_before_host'],
            'mute_upon_entry' => $request['mute_upon_entry'],
            'waiting_room' => $request['waiting_room'],
            'audio' => $request['audio'],
            'auto_recording' => $request->has('auto_recording') ? $request['auto_recording'] : 'none',
            'approval_type' => $request['approval_type'],

            'is_recurring' =>  $request['is_recurring'],
            'recurring_type' =>   $request['is_recurring'] == 1 ? $request['recurring_type'] : null,
            'recurring_repect_day' =>   $request['is_recurring'] == 1 ? $request['recurring_repect_day'] : null,
            'recurring_end_date' =>  $request['is_recurring'] == 1 ?  $request['recurring_end_date'] : null,

            'password' =>  $meeting_details->password,
            'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
            'end_time' =>  Carbon::parse($start_date)->addMinute($request['durration'])->toDateTimeString(),
            'created_by' =>  $creator_info->id,
        ]);

        if ($request->file('attached_file') != "") {
            if (file_exists($system_meeting->attached_file)) {
                unlink($system_meeting->attached_file);
            }
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/zoom-meeting/', $fileName);
            $fileName = 'public/uploads/zoom-meeting/' . $fileName;
            $system_meeting->update([
                'attached_file' =>  $fileName
            ]);
        }

        if ($creator_info->role_id == 1) {
            $system_meeting->participates()->detach();
            $system_meeting->participates()->attach($request['participate_ids']);
        }
        $this->setNotificaiton($request['participate_ids'], $request['member_type'], $updateStatus = 1, $creator_info);

        DB::commit();
        return ApiBaseMethod::sendResponse(null, 'Meeting updated');
    }

    public function classes()
    {
        $data = $this->virtualClassRepository->index();

        $classes = ClassResource::collection($data['classes']);

        $response = [
            'success' => true,
            'data' => $classes,
            'message' => 'Operation successful.',
        ];

        return response()->json($response, 200);
    }

    public function sections(Request $request)
    {
        $sections = SmClassSection::with('sectionName')->where('class_id', $request->class_id)
            ->where('school_id', auth()->user()->school_id)
            ->get()->map(function ($section) {
                return [
                    'section_id' => $section->id,
                    'section_name' => @$section->sectionName->section_name
                ];
            });

        $response = [
            'success' => true,
            'data' => $sections,
            'message' => 'Operation successful.',
        ];

        return response()->json($response, 200);
    }

    public function teachers()
    {
        $data = $this->virtualClassRepository->index();

        $teachers = TeacherResource::collection($data['teachers']);

        $response = [
            'success' => true,
            'data' => $teachers,
            'message' => 'Operation successful.',
        ];

        return response()->json($response, 200);
    }

    public function classList()
    {
        if (Auth::user()->role_id == 4) {

            $meetings = VirtualClass::whereNull('course_id')->orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                return $query->withoutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('user_id', Auth::user()->id);
            })
                ->where('status', 1)
                ->get();
            foreach ($meetings as $meeting) {
                $teahcer_id = DB::table('zoom_virtual_class_teachers')->where('meeting_id', $meeting->id)->first();
                // if (Auth::user()->role_id == 1) {
                //     if (!is_null($teahcer_id)) {
                //         $teahcer_id = $teahcer_id->user_id;
                //     }
                // }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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
            $meetings = MeetingResource::collection($data['meetings']);

            $response = [
                'success' => true,
                'data' => $meetings,
                'message' => 'Operation successful.'
            ];
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $meetings = VirtualClass::whereNull('course_id')->orderBy('id', 'DESC')->get();
            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('zoom_virtual_class')->where('meeting_id', $meeting->id)->first(/* ['id', 'user_id'] */);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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
            $meetings = MeetingResource::collection($data['meetings']);

            $response = [
                'success' => true,
                'data' => $meetings,
                'message' => 'Operation successful.'
            ];
        } elseif (Auth::user()->role_id == 2) {
            $user = User::where('id', auth()->id())->first();
            $id = $user->student->id;
            $studentRecord = StudentRecord::with('student')->where('student_id', $id)->first();
            $class_id = $studentRecord->class_id;
            $section_id = $studentRecord->section_id;
            $meetings = VirtualClass::whereNull('course_id')->where('class_id', $class_id)->where('section_id', $section_id)->orderBy('id', 'DESC')->get();
            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('zoom_virtual_class')->where('meeting_id', $meeting->id)->first(/* ['id', 'user_id'] */);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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
            $meetings = MeetingResource::collection($data['meetings']);

            $response = [
                'success' => true,
                'data' => $meetings,
                'message' => 'Operation successful.'
            ];
        } else {
            $response = [
                'success' => false,
                'data' => null,
                'message' => 'Operation failed.'
            ];
        }

        return response()->json($response, 200);
    }

    public function store(VirtualClassRequest $request)
    {
        $this->virtualClassRepository->classStore($request);

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Operation successful.'
        ];

        return response()->json($response, 200);
    }

    public function show(Request $request)
    {
        $id = $request->meeting_id;

        $meeting = $this->virtualClassRepository->show($id);

        $localData = $meeting['localMeetingData'];
        $localResults = $meeting['results'];

        $data = [
            'class' => (int)$localData->class_id,
            'section' => (int)$localData->section_id,
            'topic' => $localData->topic,
            // 'teacher' => $meeting,
            'file' => $localData->attached_file ? asset($localData->attached_file) : null,
            'start_time' => date('d-M-Y h:i A', strtotime($localData->start_time)),
            'virtual_class_id' => $localData->meeting_id,
            'password' => $localData->password,
            'video_link' => $localData->vedio_link,
            'recorded_video' => $localData->local_video,
            // 'host_id' => $meeting,
            'description' => $localData->description,
            'status' => $localData->status,
            // 'timezone' => $meeting,
            'created_at' => date('d-M-Y h:i A', strtotime($localData->created_at))
        ];

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Operation successfull'
        ];

        return response()->json($response, 200);
    }

    public function update(VirtualClassRequest $request)
    {
        $meeting = VirtualClass::where('meeting_id', $request->meeting_id)->first();

        $this->virtualClassRepository->classUpdate($request, $meeting->id);

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Operation successful.'
        ];

        return response()->json($response, 200);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'meeting_id' => 'required'
        ]);

        $meeting = VirtualClass::where('meeting_id', $request->meeting_id)->first();
        $this->virtualClassRepository->deleteById($meeting->id);

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Operation successful.'
        ];

        return response()->json($response, 200);
    }

    public function meetings()
    {
        if (Auth::user()->role_id == 4) {
            $meetings =   ZoomMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })
                ->orWhere('created_by', Auth::user()->id)

                ->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('zoom_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $meetings =  ZoomMeeting::orderBy('id', 'DESC')->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('zoom_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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
            $meetings = ZoomMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return  $query->where('user_id', Auth::user()->id);
            })

                ->get();
            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('zoom_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

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

        $response = [
            'success' => true,
            'data' => MeetingResource::collection($data['meetings']),
            'message' => 'Operation successful.'
        ];

        return response()->json($response, 200);
    }
}
