<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\Models\HomeSlider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmHomeSliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $homeSliders = HomeSlider::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.home_slider.home_slider', compact('homeSliders'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        if(config('app.app_sync')){
            Toastr::error('Restricted in demo mode');
            return back();
        }

        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/home_slider/';
            $image = fileUpload($request->image, $destination);
            $homeSlider = new HomeSlider();
            $homeSlider->image = $image;
            $homeSlider->link = $request->link;
            $homeSlider->school_id = app('school')->id;
            $result = $homeSlider->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $homeSliders = HomeSlider::where('school_id', app('school')->id)->get();
            $add_home_slider = HomeSlider::find($id);
            return view('backEnd.frontSettings.home_slider.home_slider', compact('add_home_slider', 'homeSliders'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        if(config('app.app_sync')){
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        if ($input['id']) {
            $validator = Validator::make($input, [
                'image' => "sometimes|nullable|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/home_slider/';
            $homeSlider = HomeSlider::find($request->id);
            $homeSlider->image = fileUpdate($homeSlider->image, $request->image, $destination);
            $homeSlider->link = $request->link;
            $homeSlider->school_id = app('school')->id;
            $result = $homeSlider->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('home-slider');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $homeSlider = HomeSlider::find($id);
            return view('backEnd.frontSettings.home_slider.home_slider_delete_modal', compact('homeSlider'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $homeSlider = HomeSlider::find($id);
            if($homeSlider && file_exists($homeSlider->image)){
                unlink($homeSlider->image);
            }
            $homeSlider->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
