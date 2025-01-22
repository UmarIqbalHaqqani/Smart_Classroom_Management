<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\Models\SpeechSlider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SpeechSliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $speechSliders = SpeechSlider::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.speech_slider.speech_slider', compact('speechSliders'));
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
            'name' => "required",
            'designation' => "required",
            'speech' => "required",
            'image' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/speech_slider/';
            $speechSlider = new SpeechSlider();
            $speechSlider->name = $request->name;
            $speechSlider->designation = $request->designation;
            $speechSlider->title = $request->title;
            $speechSlider->speech = $request->speech;
            $speechSlider->image = fileUpload($request->image, $destination);
            $speechSlider->school_id = app('school')->id;
            $result = $speechSlider->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('speech-slider');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $speechSliders = SpeechSlider::where('school_id', app('school')->id)->get();
            $add_speech_slider = SpeechSlider::find($id);
            return view('backEnd.frontSettings.speech_slider.speech_slider', compact('speechSliders', 'add_speech_slider'));
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
                'image' => "sometimes|nullable|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'name' => "required",
                'designation' => "required",
                'speech' => "required",
                'image' => "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/speech_slider/';
            $speechSlider = SpeechSlider::find($request->id);
            $speechSlider->name = $request->name;
            $speechSlider->designation = $request->designation;
            $speechSlider->title = $request->title;
            $speechSlider->speech = $request->speech;
            $speechSlider->image = fileUpdate($speechSlider->image, $request->image, $destination);
            $speechSlider->school_id = app('school')->id;
            $result = $speechSlider->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('speech-slider');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $speechSlider = SpeechSlider::find($id);
            return view('backEnd.frontSettings.speech_slider.speech_slider_delete_modal', compact('speechSlider'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $speechSlider = SpeechSlider::where('id', $id)->first();
            $speechSlider->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
