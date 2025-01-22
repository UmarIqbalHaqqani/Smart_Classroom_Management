<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmPage;
use App\SmNewsPage;
use App\Models\FrontendExamResult;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\FrontSettings\SmPageRequest;
use App\Http\Requests\Admin\FrontSettings\ExamResultPageRequest;

class SmPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {
        try {
            $pages = SmPage::where('school_id', app('school')->id)->where('is_dynamic', 1)->get();
            return view('backEnd.frontSettings.pageList', compact('pages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function create()
    {
        return view('backEnd.frontSettings.createPage');
    }

    public function store(SmPageRequest $request)
    {
        try {

            $destination = 'public/uploads/pages/';
            $fileName = fileUpload($request->file, $destination);
            $data = new SmPage();
            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->slug = $request->slug;
            $data->details = $request->details;
            $data->header_image = $fileName;
            $data->school_id = app('school')->id;
            $data->save();
            Toastr::success('Operation successfull', 'Success');
            return redirect('create-page');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $editData = SmPage::find($id);
            return view('backEnd.frontSettings.createPage', compact('editData'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmPageRequest $request)
    {
        try {
            $destination = 'public/uploads/pages/';

            $data = SmPage::find($request->id);
            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->slug = $request->slug;
            $data->details = $request->details;
            $data->school_id = app('school')->id;
            $data->header_image = fileUpdate($data->header_image, $request->file, $destination);
            $data->save();

            Toastr::success('Operation successfull', 'Success');
            return redirect('page-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examResultPage()
    {
        try {
            $exam_page = FrontendExamResult::where('school_id', app('school')->id)->first();
            $update = "";
            return view('backEnd.frontSettings.examResultPage', compact('exam_page', 'update'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examResultPageUpdate(ExamResultPageRequest $request)
    {
        try {
            $path = 'public/uploads/about_page/';
            $newsHeading = FrontendExamResult::where('school_id', app('school')->id)->first();

            if ($newsHeading) {
                $newsHeading->image  = fileUpdate($newsHeading->image, $request->image, $path);
                $newsHeading->main_image  = fileUpdate($newsHeading->main_image, $request->main_image, $path);
            } else {
                $newsHeading = new FrontendExamResult();
                $newsHeading->image  = fileUpload($request->image, $path);
                $newsHeading->main_image  = fileUpload($request->main_image, $path);
                $newsHeading->school_id = app('school')->id;
            }

            $newsHeading->title = $request->title;
            $newsHeading->description = $request->description;
            $newsHeading->main_title = $request->main_title;
            $newsHeading->main_description = $request->main_description;
            $newsHeading->button_text = $request->button_text;
            $newsHeading->button_url = $request->button_url;
            $newsHeading->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
       
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}

