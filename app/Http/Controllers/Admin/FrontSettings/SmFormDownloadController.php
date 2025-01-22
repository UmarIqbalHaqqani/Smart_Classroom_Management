<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\SmFormDownload;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmFormDownloadController extends Controller
{
    public function index()
    {
        try {
            $froms = SmFormDownload::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.form_download.form_download', compact('froms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $desitnation = 'public/uploads/theme/edulia/form_download/';
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'short_description' => 'required',
            'publish_date' => 'required',
            'link' => $request->file == null ? "required" : "nullable",
            'file' => $request->link == null ? "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize : "nullable",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $formDownload = new SmFormDownload();
            $formDownload->title = $request->title;
            $formDownload->short_description = $request->short_description;
            $formDownload->publish_date = formatedDate($request->publish_date);
            if ($request->link) {
                $formDownload->link = $request->link;
            } else {
                $formDownload->file = fileUpload($request->file, $desitnation);
            }
            $formDownload->show_public = $request->show_public;
            $formDownload->school_id = app('school')->id;
            $result = $formDownload->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('form-download');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $froms = SmFormDownload::where('school_id', app('school')->id)->get();
            $add_form_download = SmFormDownload::where('id', $id)->where('school_id', app('school')->id)->first();
            return view('backEnd.frontSettings.form_download.form_download', compact('froms', 'add_form_download'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $desitnation = 'public/uploads/theme/edulia/form_download/';
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'short_description' => 'required',
            'publish_date' => 'required',
            'link' => $request->file == null ? "required" : "nullable",
            'file' => $request->link == null ? "required|mimes:jpg,png,jpeg,pdf|max:" . $maxFileSize : "sometimes|nullable",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $formDownload = SmFormDownload::find($request->id);
            $formDownload->title = $request->title;
            $formDownload->short_description = $request->short_description;
            $formDownload->publish_date = formatedDate($request->publish_date);
            if ($request->link) {
                $formDownload->link = $request->link;
            } else {
                $formDownload->file = fileUpdate($formDownload->file, $request->file, $desitnation);
            }
            $formDownload->show_public = $request->show_public;
            $formDownload->school_id = app('school')->id;
            $result = $formDownload->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('form-download');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $formDownload = SmFormDownload::find($id);
            return view('backEnd.frontSettings.form_download.form_download_delete_modal', compact('formDownload'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $formDownload = SmFormDownload::find($id);
            $formDownload->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
