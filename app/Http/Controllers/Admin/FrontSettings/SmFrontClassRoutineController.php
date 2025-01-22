<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\FrontClassRoutine;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmFrontClassRoutineController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $frontClassRoutines = FrontClassRoutine::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.front_class_routine.front_class_routine', compact('frontClassRoutines'));
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
            $destination =  'public/uploads/front_class_routine/';
            $frontClassRoutine = new FrontClassRoutine();
            $frontClassRoutine->title = $request->title;
            $frontClassRoutine->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontClassRoutine->result_file = fileUpload($request->result_file, $destination);
            $frontClassRoutine->school_id = app('school')->id;
            $result = $frontClassRoutine->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-class-routine');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $frontClassRoutines = FrontClassRoutine::where('school_id', app('school')->id)->get();
            $add_front_class_routine = FrontClassRoutine::find($id);
            return view('backEnd.frontSettings.front_class_routine.front_class_routine', compact('frontClassRoutines', 'add_front_class_routine'));
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
            $destination =  'public/uploads/front_class_routine/';
            $frontClassRoutine = FrontClassRoutine::find($request->id);
            $frontClassRoutine->title = $request->title;
            $frontClassRoutine->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontClassRoutine->result_file = fileUpdate($frontClassRoutine->result_file, $request->result_file, $destination);
            $frontClassRoutine->school_id = app('school')->id;
            $result = $frontClassRoutine->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-class-routine');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $frontClassRoutine = FrontClassRoutine::find($id);
            return view('backEnd.frontSettings.front_class_routine.front_class_routine_delete_modal', compact('frontClassRoutine'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $frontClassRoutine = FrontClassRoutine::where('id', $id)->first();
            $frontClassRoutine->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
