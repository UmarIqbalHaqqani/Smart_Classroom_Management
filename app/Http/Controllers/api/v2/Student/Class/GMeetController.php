<?php

namespace App\Http\Controllers\api\v2\Student\Class;

use App\User;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Gmeet\Entities\GmeetVirtualClass;
use App\Http\Resources\v2\Class\Student\GMeet\MeetingResource;
use Modules\Gmeet\Entities\GmeetVirtualMeeting;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;

class GMeetController extends Controller
{
    protected $virtualClassRepository;
    public function __construct(
        GmeetVirtualClassRepositoryInterface $virtualClassRepository
    ) {
        $this->virtualClassRepository = $virtualClassRepository;
    }

    public function index()
    {
        /* $data =  $this->virtualClassRepository->index();
        return $data['meetings']; */

        if (Auth::user()->role_id == 4) {
            $meetings = GmeetVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
            $meetings = GmeetVirtualClass::orderBy('id', 'DESC')->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
        } elseif (Auth::user()->role_id == 2) {
            $user = User::where('id', auth()->id())->first();
            $id = $user->student->id;
            $studentRecord = StudentRecord::where('student_id', $id)->first();
            $class_id = $studentRecord->class_id;
            $section_id = $studentRecord->section_id;
            $meetings = GmeetVirtualClass::orderBy('id', 'DESC')->where('class_id', $class_id)->where('section_id', $section_id)->orwhere('section_id', null)->get();


            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
            $meetings = GmeetVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) {
                return  $query->whereHas('students', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                });
            })->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
        if (Auth::user()->role_id == 4) {
            $meetings =   GmeetVirtualMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })
                ->orWhere('created_by', Auth::user()->id)

                ->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
            $meetings =  GmeetVirtualMeeting::orderBy('id', 'DESC')->get();

            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
            $meetings = GmeetVirtualMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return  $query->where('user_id', Auth::user()->id);
            })

                ->get();
            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
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
        $meetings = MeetingResource::collection($data['meetings']);
        $response = [
            'success' => true,
            'data' => $meetings,
            'message' => 'Operation successful.'
        ];
        return response()->json($response, 200);
    }
}
