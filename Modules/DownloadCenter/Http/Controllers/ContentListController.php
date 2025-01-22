<?php

namespace Modules\DownloadCenter\Http\Controllers;

use App\SmClass;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\DownloadCenter\Entities\Content;
use Modules\RolePermission\Entities\InfixRole;
use Modules\DownloadCenter\Entities\ContentType;

class ContentListController extends Controller
{
    public function contentList()
    {
        try {
            $editContent = null;
            $contents = Content::with('contentType', 'user')->get();
            $contentTpyes = ContentType::all();
            $roles = InfixRole::select('*')->where('is_saas',0)->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get();
            return view('downloadcenter::contentList.contentList', compact('contentTpyes', 'contents', 'editContent', 'roles', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentListSave(Request $request)
    {
        if($request->content_file != null && $request->youtube_link != null){
            Toastr::error('Can\'t upload File and Youtube link at the same time', 'Failed');
            return redirect()->back();
        }
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'content_type_id' => "required",
            'youtube_link' => "required_without:content_file",
            'content_file' => "required_without:youtube_link|mimes:jpg,png,jpeg,pdf,doc,docx,txt,xlsx,rar,zip|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $destination = 'public/uploads/content_list/';
            $newContent = new Content();
            $newContent->content_type_id = $request->content_type_id;
            if ($request->content_file) {
                $newContent->file_name = $request->file('content_file')->getClientOriginalName();
                $newContent->file_size = $request->file('content_file')->getSize();
                $newContent->upload_file = fileUpload($request->content_file, $destination);
            }
            if ($request->youtube_link) {
                $newContent->file_name = getYoutubeName($request->youtube_link);
                $newContent->youtube_link = $request->youtube_link;
            }
            $newContent->uploaded_by = auth()->user()->id;
            $newContent->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.content-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentListEdit($id)
    {
        try {
            $contents = Content::with('contentType', 'user')->get();
            $editContent = Content::find($id);
            $contentTpyes = ContentType::all();
            $roles = InfixRole::select('*')->where('is_saas',0)->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get();
            return view('downloadcenter::contentList.contentList', compact('contentTpyes', 'contents', 'editContent', 'roles', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentListUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'file_name' => "required",
            'content_type_id' => "required",
        ]);
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $newContent = Content::find($request->content_id);
            $newContent->file_name = $request->file_name;
            $newContent->content_type_id = $request->content_type_id;
            $newContent->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.content-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentListDelete($id)
    {
        try {
            $content = Content::where('id', $id)->first();
            $content->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->route('download-center.content-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentListSearch(Request $request)
    {
        $name = $request->name;
        if ($name == null) {
            return redirect()->route('download-center.content-list')->withInput();
        }
        try {
            $contents = Content::where('file_name', 'LIKE', '%' . $request->name . '%')->with('contentType', 'user')->get();
            $contentTpyes = ContentType::get();
            $editContent = null;
            $roles = InfixRole::select('*')->where('is_saas',0)->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get();
            return view('downloadcenter::contentList.contentList', compact('contentTpyes', 'contents', 'editContent', 'roles', 'classes', 'name'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
