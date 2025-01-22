<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\FrontAcademicCalendar;
use Illuminate\Support\Facades\Validator;

class SmAcademicCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $frontAcademicCalendars = FrontAcademicCalendar::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.acadmic_calendar.acadmic_calendar', compact('frontAcademicCalendars'));
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
            'calendar_file' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/academic_calendar/';
            $frontAcademicCalendar = new FrontAcademicCalendar();
            $frontAcademicCalendar->title = $request->title;
            $frontAcademicCalendar->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontAcademicCalendar->calendar_file = fileUpload($request->calendar_file, $destination);
            $frontAcademicCalendar->school_id = app('school')->id;
            $result = $frontAcademicCalendar->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-academic-calendar');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $frontAcademicCalendars = FrontAcademicCalendar::where('school_id', app('school')->id)->get();
            $add_front_academic_calendar = FrontAcademicCalendar::find($id);
            return view('backEnd.frontSettings.acadmic_calendar.acadmic_calendar', compact('frontAcademicCalendars', 'add_front_academic_calendar'));
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
                'calendar_file' => "sometimes|nullable|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'title' => "required",
                'publish_date' => "required",
                'calendar_file' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/academic_calendar/';
            $frontAcademicCalendar = FrontAcademicCalendar::find($request->id);
            $frontAcademicCalendar->title = $request->title;
            $frontAcademicCalendar->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $frontAcademicCalendar->calendar_file = fileUpdate($frontAcademicCalendar->calendar_file, $request->calendar_file, $destination);
            $frontAcademicCalendar->school_id = app('school')->id;
            $result = $frontAcademicCalendar->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('front-academic-calendar');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $frontAcademicCalendar = FrontAcademicCalendar::find($id);
            return view('backEnd.frontSettings.acadmic_calendar.acadmic_calendar_delete_modal', compact('frontAcademicCalendar'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $frontAcademicCalendar = FrontAcademicCalendar::where('id', $id)->first();
            $frontAcademicCalendar->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
