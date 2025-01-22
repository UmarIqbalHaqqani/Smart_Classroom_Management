<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\SmVideoGallery;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmVideoGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $videoGalleries = SmVideoGallery::where('school_id', app('school')->id)->orderBy('position', 'asc')->get();
            return view('backEnd.frontSettings.video_gallery.video_gallery', compact('videoGalleries'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'description' => "required",
            'video_link' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if (youtubeVideoLinkValidation($request->video_link) == 0) {
            Toastr::error('Only YouTube Video link accepted', 'Failed');
            return redirect()->back();
        }
        try {
            $videoGallery = new SmVideoGallery();
            $videoGallery->name = $request->name;
            $videoGallery->description = $request->description;
            $videoGallery->video_link = $request->video_link;
            $videoGallery->school_id = app('school')->id;
            $result = $videoGallery->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('video-gallery');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $videoGalleries = SmVideoGallery::where('school_id', app('school')->id)->orderBy('position', 'asc')->get();
            $add_video_gallery = SmVideoGallery::find($id);
            return view('backEnd.frontSettings.video_gallery.video_gallery', compact('videoGalleries', 'add_video_gallery'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'description' => "required",
            'video_link' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if (youtubeVideoLinkValidation($request->video_link) == 0) {
            Toastr::error('Only YouTube Video link accepted', 'Failed');
            return redirect()->back();
        }
        try {
            $videoGallery = SmVideoGallery::find($request->id);
            $videoGallery->name = $request->name;
            $videoGallery->description = $request->description;
            $videoGallery->video_link = $request->video_link;
            $videoGallery->school_id = app('school')->id;
            $result = $videoGallery->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('video-gallery');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $videoGallery = SmVideoGallery::find($id);
            return view('backEnd.frontSettings.video_gallery.video_gallery_delete_modal', compact('videoGallery'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $videoGallery = SmVideoGallery::where('id', $id)->first();
            $videoGallery->delete();

            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewModal($id)
    {
        try {
            $videoGallery = SmVideoGallery::find($id);
            return view('backEnd.frontSettings.video_gallery.video_gallery_view_modal', compact('videoGallery'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
