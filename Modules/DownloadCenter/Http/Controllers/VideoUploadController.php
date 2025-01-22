<?php

namespace Modules\DownloadCenter\Http\Controllers;

use App\SmClass;
use App\SmSection;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\DownloadCenter\Entities\VideoUpload;

class VideoUploadController extends Controller
{
    public function videoList()
    {
        try {
            $student = SmStudent::where('user_id', auth()->user()->id)->with('studentRecord')->first();
            if (auth()->user()->role_id == 2) {
                $videos = VideoUpload::where('class_id', $student->studentRecord->class_id)
                    ->where('section_id', $student->studentRecord->section_id)
                    ->with('class', 'section', 'user')
                    ->get();
            } else {
                $videos = VideoUpload::with('class', 'section', 'user')->get();
            }
            $classes = SmClass::get();
            return view('downloadcenter::videoUpload.videoList', compact('classes', 'videos'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListSave(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
            'section_id' => "required",
            'title' => "required",
            'video_link' => "required",
        ], [
            'class_id' => "The class field is required.",
            'section_id' => "The section field is required.",
        ]);
        if ($validator->fails()) {
            Toastr::error('Please fill all the required fields', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (youtubeVideoLinkValidation($request->video_link) == 0) {
            Toastr::error('Only YouTube Video link accepted', 'Failed');
            return redirect()->back();
        }

        try {
            $newContent = new VideoUpload();
            $newContent->class_id = $request->class_id;
            $newContent->section_id = $request->section_id;
            $newContent->title = $request->title;
            $newContent->description = $request->description;
            $newContent->youtube_link = $request->video_link;
            $newContent->created_by = auth()->user()->id;
            $newContent->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.video-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
            'section_id' => "required",
            'title' => "required",
            'video_link' => "required",
        ], [
            'class_id' => "The class field is required.",
            'section_id' => "The section field is required.",
        ]);
        if ($validator->fails()) {
            Toastr::error('Please fill all the required fields', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (youtubeVideoLinkValidation($request->video_link) == 0) {
            Toastr::error('Only YouTube link accepted', 'Failed');
            return redirect()->back();
        }

        try {
            $editContent = VideoUpload::find($request->video_id);
            $editContent->class_id = $request->class_id;
            $editContent->section_id = $request->section_id;
            $editContent->title = $request->title;
            $editContent->description = $request->description;
            $editContent->youtube_link = $request->video_link;
            $editContent->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.video-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListDelete($id)
    {
        try {
            $content = VideoUpload::where('id', $id)->first();
            $content->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->route('download-center.video-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListSearch(Request $request)
    {
        try {
            $videos = VideoUpload::when($request->class_id, function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            })
                ->when($request->section_id, function ($q) use ($request) {
                    $q->where('section_id', $request->section_id);
                })
                ->when($request->title, function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->title . '%');
                })->get();
            $classes = SmClass::get();
            if ($videos->isEmpty()) {
                Toastr::error('No data found', 'Failed');
                return redirect()->back();
            } else {
                return view('downloadcenter::videoUpload.videoList', compact('classes', 'videos'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListViewModal($id)
    {
        try {
            $video = VideoUpload::with('class', 'section', 'user')->find($id);
            return view('downloadcenter::videoUpload.video_view_modal', compact('video'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function videoListEditModal($id)
    {
        try {
            $data['video'] = VideoUpload::with('class', 'section', 'user')->find($id);
            $data['classes'] = SmClass::get();
            $data['sections'] = SmSection::get();
            return view('downloadcenter::videoUpload.video_edit_modal', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentVideoList($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->with('studentRecord')->first();
            $records = studentRecords(null, $student_detail->id)->get();
            $videos = VideoUpload::where('class_id', $student_detail->studentRecord->class_id)
                ->where('section_id', $student_detail->studentRecord->section_id)
                ->with('class', 'section', 'user')
                ->get();
            $classes = SmClass::get();
            return view('downloadcenter::videoUpload.parentVideoList', compact('classes', 'videos', 'student_detail', 'records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
