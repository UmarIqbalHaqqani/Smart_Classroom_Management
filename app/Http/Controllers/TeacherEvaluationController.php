<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherEvaluation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherEvaluationSetting;
use Illuminate\Support\Facades\Validator;

class TeacherEvaluationController extends Controller
{
    public function teacherEvaluationSetting()
    {
        $teacherEvaluationSetting = TeacherEvaluationSetting::where('id', 1)->first();
        return view('backEnd.teacherEvaluation.setting.teacherEvaluationSetting', compact('teacherEvaluationSetting'));
    }
    public function teacherEvaluationSettingUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'endDate' => 'after:startDate',
        ]);
        if ($validator->fails()) {
            Toastr::error('End Date cannot be before Start Date', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $teacherEvaluationSetting = TeacherEvaluationSetting::find(1);
            if ($request->type == 'evaluation') {
                $teacherEvaluationSetting->is_enable = $request->is_enable;
                $teacherEvaluationSetting->auto_approval = $request->auto_approval;
            }
            if ($request->type == 'submission') {
                $teacherEvaluationSetting->submitted_by = $request->submitted_by ? $request->submitted_by : $teacherEvaluationSetting->submitted_by;
                $teacherEvaluationSetting->rating_submission_time = $request->rating_submission_time;
                $teacherEvaluationSetting->from_date = date('Y-m-d', strtotime($request->startDate));
                $teacherEvaluationSetting->to_date = date('Y-m-d', strtotime($request->endDate));
            }
            $teacherEvaluationSetting->update();
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function teacherEvaluationSubmit(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'rating' => "required",
            'comment' => "required",
        ]);
        if ($validator->fails()) {
            Toastr::error('Empty Submission', 'Failed');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $teacherEvaluationSetting = TeacherEvaluationSetting::find(1);
            $teacherEvaluation = new TeacherEvaluation();
            $teacherEvaluation->rating = $request->rating;
            $teacherEvaluation->comment = $request->comment;
            $teacherEvaluation->record_id = $request->record_id;
            $teacherEvaluation->subject_id = $request->subject_id;
            $teacherEvaluation->teacher_id = $request->teacher_id;
            $teacherEvaluation->student_id = $request->student_id;
            $teacherEvaluation->parent_id = $request->parent_id;
            $teacherEvaluation->role_id = Auth::user()->role_id;
            $teacherEvaluation->academic_id = getAcademicId();
            if ($teacherEvaluationSetting->auto_approval == 0) {
                $teacherEvaluation->status = 1;
            }
            $teacherEvaluation->save();
            Toastr::success('Operation Successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
