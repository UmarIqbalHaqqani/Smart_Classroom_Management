<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStaff;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\Models\TeacherEvaluation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeacherEvaluationReportController extends Controller
{
    public function getAssignSubjectTeacher(Request $request)
    {
        $staffs = SmAssignSubject::where('class_id', $request->class_id)->where('subject_id', $request->subject_id)->whereIn('section_id', $request->section_ids)->with('teacher')->select('teacher_id')->distinct('teacher_id')->get();
        return response()->json($staffs);
    }
    public function teacherApprovedEvaluationReport()
    {
        try {
            $classes = SmClass::get();
            $teacherEvaluations = TeacherEvaluation::with('studentRecord.studentDetail.parents', 'staff')->get();
            return view('backEnd.teacherEvaluation.report.teacher_approved_evaluation_report', compact('classes', 'teacherEvaluations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherPendingEvaluationReport()
    {
        $classes = SmClass::get();
        $teacherEvaluations = TeacherEvaluation::with('studentRecord.studentDetail.parents', 'staff')->get();
        return view('backEnd.teacherEvaluation.report.teacher_pending_evaluation_report', compact('classes', 'teacherEvaluations'));
    }
    public function teacherWiseEvaluationReport()
    {
        $classes = SmClass::get();
        $teachers = SmStaff::where('role_id', 4)->get();
        $teacherEvaluations = TeacherEvaluation::with('studentRecord.studentDetail.parents', 'staff')->get();
        return view('backEnd.teacherEvaluation.report.teacher_wise_evaluation_report', compact('classes', 'teacherEvaluations', 'teachers'));
    }
    public function teacherApprovedEvaluationReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $classes = SmClass::get();
            $staffs = SmAssignSubject::where('class_id', $request->class_id)
                ->when($request->subject_id, function ($query) use ($request) {
                    $query->where('subject_id', $request->subject_id);
                })
                ->when($request->section_id, function ($query) use ($request) {
                    $query->whereIn('section_id', [$request->section_id]);
                })
                ->when($request->teacher_id, function ($query) use ($request) {
                    $query->where('teacher_id', $request->teacher_id);
                })->get();

            $teacherEvaluations = TeacherEvaluation::when($request->class_id, function ($q) use ($request) {
                $q->whereHas('studentRecord', function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            })
                ->when($request->subject_id, function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                })
                ->when($request->section_id, function ($q) use ($request) {
                    $q->whereHas('studentRecord', function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    });
                })
                ->when($request->teacher_id, function ($q) use ($staffs) {
                    foreach ($staffs as $staff) {
                        $q->where('teacher_id', $staff->teacher_id);
                    }
                })
                ->when($request->submitted_by, function ($q) use ($request) {
                    $q->where('role_id', $request->submitted_by);
                })
                ->with('studentRecord.studentDetail.parents', 'staff')->get();

            return view('backEnd.teacherEvaluation.report.teacher_approved_evaluation_report', compact('classes', 'teacherEvaluations'));
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherPendingEvaluationReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $classes = SmClass::get();
            $staffs = SmAssignSubject::where('class_id', $request->class_id)
                ->when($request->subject_id, function ($query) use ($request) {
                    $query->where('subject_id', $request->subject_id);
                })
                ->when($request->section_id, function ($query) use ($request) {
                    $query->whereIn('section_id', [$request->section_id]);
                })
                ->when($request->teacher_id, function ($query) use ($request) {
                    $query->where('teacher_id', $request->teacher_id);
                })->get();

            $teacherEvaluations = TeacherEvaluation::when($request->class_id, function ($q) use ($request) {
                $q->whereHas('studentRecord', function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            })
                ->when($request->subject_id, function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                })
                ->when($request->section_id, function ($q) use ($request) {
                    $q->whereHas('studentRecord', function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    });
                })
                ->when($request->teacher_id, function ($q) use ($staffs) {
                    foreach ($staffs as $staff) {
                        $q->where('teacher_id', $staff->teacher_id);
                    }
                })
                ->when($request->submitted_by, function ($q) use ($request) {
                    $q->where('role_id', $request->submitted_by);
                })
                ->with('studentRecord.studentDetail.parents', 'staff')->get();

            return view('backEnd.teacherEvaluation.report.teacher_pending_evaluation_report', compact('classes', 'teacherEvaluations'));
        } catch (\Exception $e) {
         
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherWiseEvaluationReportSearch(Request $request)
    {
        try {
            $classes = SmClass::get();
            $teachers = SmStaff::where('role_id', 4)->get();
            $teacherEvaluations = TeacherEvaluation::query();
            if ($request->teacher_id) {
                $teacherEvaluations->where('teacher_id', $request->teacher_id);
            }
            if ($request->submitted_by) {
                $teacherEvaluations->where('role_id', $request->submitted_by);
            }
            $teacherEvaluations = $teacherEvaluations->with('studentRecord.studentDetail.parents', 'staff')->get();
            return view('backEnd.teacherEvaluation.report.teacher_wise_evaluation_report', compact('classes', 'teacherEvaluations', 'teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherEvaluationApproveSubmit($id)
    {
        try {
            $teacherEvaluations = TeacherEvaluation::find($id);
            $teacherEvaluations->status = 1;
            $teacherEvaluations->update();
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherEvaluationApproveDelete($id)
    {
        try {
            $teacherEvaluations = TeacherEvaluation::find($id);
            $teacherEvaluations->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherPanelEvaluationReport()
    {
        try {
            $staffId = SmStaff::where('user_id', auth()->user()->id)->select('id')->first();
            $teacherEvaluations = TeacherEvaluation::where('teacher_id', $staffId->id)->with('studentRecord')->get();
            return view('backEnd.teacherEvaluation.report.teacher_panel_evaluation_report', compact('teacherEvaluations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
