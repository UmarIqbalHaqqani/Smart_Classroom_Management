<?php

namespace Modules\DownloadCenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\DownloadCenter\Entities\ContentType;

class ContentTypeController extends Controller
{
    public function contentType()
    {
        try {
            $types = ContentType::get();
            return view('downloadcenter::contentType.contentType', compact('types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentTypeSave(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $type = new ContentType();
            $type->name = $request->name;
            $type->description = $request->description;
            $type->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.content-type');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentTypeEdit(Request $request, $id)
    {
        try {
            $type = ContentType::find($id);
            $types = ContentType::get();
            return view('downloadcenter::contentType.contentType', compact('types', 'type'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentTypeUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
        ]);
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $type = ContentType::find($request->type_id);
            $type->name = $request->name;
            $type->description = $request->description;
            $type->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('download-center.content-type');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentTypeDelete($id)
    {
        try {
            $type = ContentType::where('id', $id)->first();
            $type->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->route('download-center.content-type');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
