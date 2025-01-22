<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\FrontExamRoutine;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmFrontExamRoutineController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $frontExamRoutines = FrontExamRoutine::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.front_exam_routine.front_exam_routine', compact('frontExamRoutines'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => "required",
            'publish_date' => "required",
            'result_file' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/front_exam_routine/';
            $frontExamRoutine = new FrontExamRoutine();
            $frontExamRoutine->title = $request->title;
            $frontExamRoutine->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontExamRoutine->result_file = fileUpload($request->result_file, $destination);
            $frontExamRoutine->school_id = app('school')->id;
            $result = $frontExamRoutine->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-exam-routine');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $frontExamRoutines = FrontExamRoutine::where('school_id', app('school')->id)->get();
            $add_front_exam_routine = FrontExamRoutine::find($id);
            return view('backEnd.frontSettings.front_exam_routine.front_exam_routine', compact('frontExamRoutines', 'add_front_exam_routine'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        if ($input['id']) {
            $validator = Validator::make($input, [
                'result_file' => "sometimes|nullable|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'title' => "required",
                'publish_date' => "required",
                'result_file' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/front_exam_routine/';
            $frontExamRoutine = FrontExamRoutine::find($request->id);
            $frontExamRoutine->title = $request->title;
            $frontExamRoutine->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontExamRoutine->result_file = fileUpdate($frontExamRoutine->result_file, $request->result_file, $destination);
            $frontExamRoutine->school_id = app('school')->id;
            $result = $frontExamRoutine->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-exam-routine');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $frontExamRoutine = FrontExamRoutine::find($id);
            return view('backEnd.frontSettings.front_exam_routine.front_exam_routine_delete_modal', compact('frontExamRoutine'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $frontExamRoutine = FrontExamRoutine::where('id', $id)->first();
            $frontExamRoutine->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
