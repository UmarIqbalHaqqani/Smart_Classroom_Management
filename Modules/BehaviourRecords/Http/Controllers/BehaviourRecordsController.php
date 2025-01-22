<?php

namespace Modules\BehaviourRecords\Http\Controllers;

use DataTables;
use App\SmClass;
use App\SmStudent;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BehaviourRecords\Entities\Incident;
use Modules\BehaviourRecords\Entities\AssignIncident;
use Modules\BehaviourRecords\Entities\BehaviourRecordSetting;

class BehaviourRecordsController extends Controller
{
    // assign incident
    public function assignIncident()
    {
        try {
            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $students = SmStudent::where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $sessions = SmAcademicYear::where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $incidents = Incident::get();
            return view('behaviourrecords::assignIncident.assignIncident', compact('classes', 'students', 'sessions', 'incidents'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function assignIncidentSearch(Request $request)
    {
        if(!moduleStatusCheck('University')) {
            $validator = Validator::make($request->all(), [
                'academic_year' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error('Validation Failed', 'Failed');
                return redirect()->route('behaviour_records.assign-incident')->withErrors($validator)->withInput();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->withoutGlobalScope(StatusAcademicSchoolScope::class)->get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $academic_year = $request->academic_year;
            $class_id = $request->class_id;
            $name = $request->name;
            $roll_no = $request->roll_no;
            $section = $request->section_id;
            $data['un_session_id'] = $request->un_session_id;
            $data['un_academic_id'] = $request->un_academic_id;
            $data['un_faculty_id'] = $request->un_faculty_id;
            $data['un_department_id'] = $request->un_department_id;
            $data['un_semester_id'] = $request->un_semester_id;
            $data['un_semester_label_id'] = $request->un_semester_label_id;
            $data['un_section_id'] = $request->un_section_id;
            
            
            $data['academic_year'] = $request->academic_year;
            $data['class_id'] = $request->class_id;
            $data['section_id'] = $request->section_id;
            $data['name'] = $request->name;
            $data['roll_no'] = $request->roll_no;

            $incidents = Incident::get();
            return view('behaviourrecords::assignIncident.assignIncident', compact('classes', 'class_id', 'name', 'roll_no', 'sessions', 'section', 'academic_year', 'data', 'incidents'));
        }
    }
    public function assignIncidentDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = StudentRecord::query();
            $records->where('is_promote', 0)->where('school_id', auth()->user()->school_id);
            $records->when(moduleStatusCheck('University') && $request->filled('un_academic_id'), function ($u_query) use ($request) {
                $u_query->where('un_academic_id', $request->un_academic_id);
            }, function ($query) use ($request) {
                $query->when($request->academic_year, function ($query) use ($request) {
                    $query->where('academic_id', $request->academic_year);
                });
            })
                ->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                    $u_query->where('un_faculty_id', $request->un_faculty_id);
                }, function ($query) use ($request) {
                    $query->when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                    });
                })
                ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                    $u_query->where('un_department_id', $request->un_department_id);
                }, function ($query) use ($request) {
                    $query->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                    });
                })
                ->when(!$request->academic_year && moduleStatusCheck('University') == false, function ($query) use ($request) {
                    $query->where('academic_id', getAcademicId());
                })
                ->when(moduleStatusCheck('University') && $request->filled('un_session_id'), function ($query) use ($request) {
                    $query->where('un_session_id', $request->un_session_id);
                })
                ->when(moduleStatusCheck('University') && $request->filled('un_semester_label_id'), function ($query) use ($request) {
                    $query->where('un_semester_label_id', $request->un_semester_label_id);
                });

            $student_records = $records->where('is_promote', 0)->whereHas('student')->get(['student_id'])->unique('student_id')->toArray();
            $all_students =  SmStudent::with('studentRecords', 'studentRecords.class', 'studentRecords.section')->withCount('incidents')->withSum('incidents', 'point')->whereIn('id', $student_records)->with('incidents')
                ->where('active_status', 1)
                ->with(array('parents' => function ($query) {
                    $query->select('id', 'fathers_name');
                }))
                ->with(array('gender' => function ($query) {
                    $query->select('id', 'base_setup_name');
                }))
                ->with(array('category' => function ($query) {
                    $query->select('id', 'category_name');
                }))
                ->when($request->name, function ($query) use ($request) {
                    $query->where('full_name', 'like', '%' . $request->name . '%');
                });

            $students = SmStudent::with(['gender', 'studentRecords' => function ($q) use ($request) {
                return $q->when(moduleStatusCheck('University') && $request->filled('un_academic_id'), function ($u_query) use ($request) {
                    $u_query->where('un_academic_id', $request->un_academic_id);
                }, function ($query) use ($request) {
                    $query->when($request->academic_year, function ($query) use ($request) {
                        $query->where('academic_id', $request->academic_year);
                    });
                })
                    ->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                        $u_query->where('un_faculty_id', $request->un_faculty_id);
                    }, function ($query) use ($request) {
                        $query->when($request->class, function ($query) use ($request) {
                            $query->where('class_id', $request->class);
                        });
                    })
                    ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                        $u_query->where('un_department_id', $request->un_department_id);
                    }, function ($query) use ($request) {
                        $query->when($request->section, function ($query) use ($request) {
                            $query->where('section_id', $request->section);
                        });
                    })
                    ->where('is_promote', 0)
                    ->when(!$request->academic_year && moduleStatusCheck('University') == false, function ($query) use ($request) {
                        $query->where('academic_id', getAcademicId());
                    });
            }])->select('sm_students.*');
            $students->where('sm_students.active_status', 1);

            $students = $students->with('studentRecords', 'studentRecords.class', 'studentRecords.section')->where('sm_students.school_id', Auth::user()->school_id)
                ->with(array('parents' => function ($query) {
                    $query->select('id', 'fathers_name');
                }))
                ->with(array('gender' => function ($query) {
                    $query->select('id', 'base_setup_name');
                }))
                ->with(array('category' => function ($query) {
                    $query->select('id', 'category_name');
                }));

            // return $all_students->get();
            return Datatables::of($all_students)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $full_name_link = '<a target="_blank" href="' . route('student_view', [$row->id]) . '">' . $row->first_name . ' ' . $row->last_name . '</a>';
                    return $full_name_link;
                })
                ->addColumn('mobile', function ($row) {
                    $mobile = '<a href="tel:' . $row->mobile . '">' . $row->mobile . '</a>';
                    return $mobile;
                })
                ->addColumn('class_sec', function ($row) {
                    $class_sec = [];
                    foreach ($row->studentRecords as $classSec) {
                        if (moduleStatusCheck('University')) {
                            $class_sec[] = $classSec->unFaculty->name . '(' . $classSec->unDepartment->name . ')';
                        } else {
                            $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . ')';
                        }
                    }
                    return implode(', ', $class_sec);
                })
                ->addColumn('action', function ($row) {
                    $view = view('behaviourrecords::assignIncident.assignIncidentAction', compact('row'));
                    return (string)$view;
                })
                ->rawColumns(['action', 'full_name', 'mobile', 'class_sec'])
                ->make(true);
        }
    }
    public function assignIncidentSave(Request $request)
    {
        try {
            foreach ($request->incident_ids as  $incident_id) {
                $incident = Incident::find($incident_id);
                if ($incident) {
                    $assignIncident = new AssignIncident();
                    $assignIncident->point = $incident->point;
                    $assignIncident->incident_id = $incident_id;
                    $assignIncident->student_id = $request->student_id;
                    $assignIncident->record_id = $request->record_id;
                    $assignIncident->added_by = Auth::user()->id;
                    $assignIncident->academic_id = getAcademicId();
                    $assignIncident->save();
                }
            }
            return response()->json(['message' => 'Successful']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function assignIncidentDelete($id)
    {
        try {
            $assignIncidentDelete = AssignIncident::find($id);
            $assignIncidentDelete->delete();
            return response()->json(['message' => 'Successful']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function getStudentIncident(Request $request)
    {
        $student = SmStudent::find($request->studentId);
        return view('behaviourrecords::assignIncident.assign_incident_list', compact('student'));
    }

    // incident
    public function incident()
    {
        try {
            $incidents = Incident::get();
            return view('behaviourrecords::incidents.incident', compact('incidents'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function incidentCreate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => "required",
            'point' => "required",
        ]);
        if ($validator->fails()) {
            Toastr::error('Empty Submission', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $incidentStore = new Incident();
            $incidentStore->title = $request->title;
            if ($request->negativePoint == 1) {
                $incidentStore->point = -$request->point;
            } else {
                $incidentStore->point = $request->point;
            }
            $incidentStore->description = $request->description;
            $incidentStore->save();
            return redirect()->route('behaviour_records.incident');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function incidentUpdate(Request $request)
    {
        try {
            $incidentUpdate = Incident::find($request->id);
            $incidentUpdate->title = $request->title;
            if ($request->editNegativePoint == 1) {
                $incidentUpdate->point = -$request->point;
            } else {
                $incidentUpdate->point = $request->point;
            }
            $incidentUpdate->description = $request->description;
            $incidentUpdate->save();
            return redirect()->route('behaviour_records.incident');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function incidentDelete(Request $request, $id)
    {
        try {
            $incidentDelete = Incident::where('id', $id)->first();
            $incidentDelete->destroy($request->id);
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->route('behaviour_records.incident');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // report
    public function studentIncidentReport()
    {
        try {
            $classes = SmClass::get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            return view('behaviourrecords::reports.student_incident_report', compact('classes', 'sessions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function studentIncidentReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'academic_year' => "required",
            'class_id' => "required",
            'section_id' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $classes = SmClass::get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $student_records = StudentRecord::with('student', 'student.gender', 'student.incidents', 'class', 'section')
                ->withCount('incidents')
                ->withSum('incidents', 'point')
                ->where('active_status', 1)
                ->where('academic_id', $request->academic_year)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->get();
            return view('behaviourrecords::reports.student_incident_report', compact('student_records', 'classes', 'sessions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewStudentAllIncidentModal($id)
    {
        try {
            $student = SmStudent::find($id);
            if ($student) {
                $all_incident = $student->incidents->load('incident', 'user', 'academicYear');
            }
            return view('behaviourrecords::reports.student_incident_report_modal', compact('all_incident'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentBehaviourRankReport()
    {
        try {
            $classes = SmClass::get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            return view('behaviourrecords::reports.student_behaviour_rank_report', compact('classes', 'sessions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function studentBehaviourRankReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'academic_year' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $classes = SmClass::get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $student_records = StudentRecord::with('student')
                ->where('active_status', 1)
                ->when($request->academic_year, function ($q) use ($request) {
                    $q->where('academic_id', $request->academic_year);
                })
                ->when($request->class_id, function ($q) use ($request) {
                    $q->where('class_id', $request->class_id);
                })
                ->when($request->section_id, function ($q) use ($request) {
                    $q->where('section_id', $request->section_id);
                });
            $student_ids = $student_records->pluck('student_id');
            $students = SmStudent::whereIn('id', $student_ids)
                ->whereHas('incidents')
                ->with('gender', 'studentRecords', 'studentRecords.class', 'studentRecords.section')
                ->withSum('incidents', 'point')
                ->when($request->point != null, function ($q) use ($request) {
                    $q->when($request->type == 'lesser_than_or_equal', function ($query) use ($request) {
                        $query->having('incidents_sum_point', '<=', $request->point);
                    })
                        ->when($request->type == 'greater_than_or_equal', function ($query) use ($request) {
                            $query->having('incidents_sum_point', '>=', $request->point);
                        });
                })
                ->orderBy('incidents_sum_point', 'DESC')->get();
            return view('behaviourrecords::reports.student_behaviour_rank_report', compact('students', 'classes', 'sessions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewBehaviourRankModal($id)
    {
        try {
            $student = SmStudent::find($id);
            if ($student) {
                $all_incident = $student->incidents->load('incident', 'user', 'academicYear');
            }
            return view('behaviourrecords::reports.student_behaviour_rank_report_modal', compact('all_incident'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classSectionWiseRankReport()
    {
        try {
            $classes = SmClass::with('groupclassSections')->withCount('records')->withSum('allIncident', 'point')->orderBy('all_incident_sum_point', 'DESC')->get();
            return view('behaviourrecords::reports.class_section_wise_rank_report', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewClassSectionWiseModal($id)
    {
        try {
            $class = SmClass::with('records.studentDetail.incidents.incident', 'records.class', 'records.section')->where('id', $id)->firstOrFail();
            return view('behaviourrecords::reports.class_section_wise_rank_report_modal', compact('class'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function incidentWiseReport()
    {
        try {
            $incidents = Incident::with('incidents')->get();
            return view('behaviourrecords::reports.incident_wise_report', compact('incidents'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewIncidentWiseReportModal($id)
    {
        try {
            $studentRecords = AssignIncident::where('incident_id', $id)->with('studentRecord.studentDetail', 'studentRecord.class', 'studentRecord.section')->get();
            return view('behaviourrecords::reports.incident_wise_report_modal', compact('studentRecords'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // setting
    public function setting()
    {
        try {
            $setting = BehaviourRecordSetting::where('id', 1)->first();
            return view('behaviourrecords::setting.setting', compact('setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function settingUpdate(Request $request)
    {
        try {
            $settingUpdate = BehaviourRecordSetting::find(1);
            if ($request->type == 'comment') {
                $settingUpdate->student_comment = $request->studentComment;
                $settingUpdate->parent_comment = $request->parentComment;
            }
            if ($request->type == 'view') {
                $settingUpdate->student_view = $request->studentView;
                $settingUpdate->parent_view = $request->parentView;
            }
            $settingUpdate->update();
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
