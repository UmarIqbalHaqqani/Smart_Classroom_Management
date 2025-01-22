<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\SmClass;
use App\SmStudent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmStudentParentController extends Controller
{
    public function parentList()
    {
        try {
            $classes = SmClass::get();
            $parents = SmStudent::with('parents', 'studentRecord.class', 'studentRecord.section')->get();
            return view('backEnd.studentInformation.student_parent_list', compact('parents', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentListSearch(Request $request)
    {
        try {
            $classes = SmClass::get();
            $parents = SmStudent::with('parents', 'studentRecord.class', 'studentRecord.section')
                ->when($request->class_id, function ($q) use ($request) {
                    $q->whereHas('studentRecord.class', function ($query) use ($request) {
                        $query->where('class_id', $request->class_id);
                    });
                })
                ->when($request->section_id, function ($q) use ($request) {
                    $q->whereHas('studentRecord.section', function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    });
                })
                ->when($request->parent_name, function ($q) use ($request) {
                    $q->whereHas('parents', function ($query) use ($request) {
                        $query->where('guardians_name', 'like', '%' . $request->parent_name . '%')
                            ->orWhere('fathers_name', 'like', '%' . $request->parent_name . '%');
                    });
                })
                ->when($request->student_name, function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->student_name . '%');
                })->get();
            return view('backEnd.studentInformation.student_parent_list', compact('parents', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
