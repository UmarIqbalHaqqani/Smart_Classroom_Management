<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmClassSection;
use App\SmClassTeacher;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmAssignClassTeacher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Events\ClassTeacherGetAllStudent;
use App\Http\Requests\Admin\Academics\SmAssignClassTeacherRequest;
use App\Traits\NotificationSend;

class SmAssignClassTeacherController extends Controller
{
    use NotificationSend;
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {
        try {
            $classes = SmClass::get();
            $teachers = SmStaff::status()->where(function ($q) {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->get();
            $assign_class_teachers = SmAssignClassTeacher::with('class', 'section', 'classTeachers')->where('academic_id', getAcademicId())->status()->orderBy('class_id', 'ASC')->orderBy('section_id', 'ASC')->get();

            return view('backEnd.academics.assign_class_teacher', compact('classes', 'teachers', 'assign_class_teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmAssignClassTeacherRequest $request)
    {
        DB::beginTransaction();
        try {
            $assigned_class_teacher = SmAssignClassTeacher::where('active_status', 1)
                ->where('class_id', $request->class)
                ->where('section_id', $request->section)
                ->where('academic_id', getAcademicId())
                ->where('school_id', auth()->user()->school_id)
                ->first();

            if (empty($assigned_class_teacher)) {
                $assign_class_teacher = new SmAssignClassTeacher();
                $assign_class_teacher->class_id = $request->class;
                $assign_class_teacher->section_id = $request->section;
                $assign_class_teacher->school_id = auth()->user()->school_id;
                $assign_class_teacher->academic_id = getAcademicId();
                $assign_class_teacher->save();

                $class_teacher = new SmClassTeacher();
                $class_teacher->assign_class_teacher_id = $assign_class_teacher->id;
                $class_teacher->teacher_id = $request->teacher;
                $class_teacher->school_id = auth()->user()->school_id;
                $class_teacher->academic_id = getAcademicId();
                $class_teacher->save();
                event(new ClassTeacherGetAllStudent($assign_class_teacher, $class_teacher));
                DB::commit();

                $data['class_id'] = $request->class;
                $data['section_id'] = $request->section;
                $data['teacher_name'] = $class_teacher->teacher->full_name;
                $this->sent_notifications('Assign_Class_Teacher', (array)$class_teacher->teacher->user_id, $data, ['Teacher']);

                $records = $this->studentRecordInfo($request->class, $request->section)->pluck('studentDetail.user_id');
                $this->sent_notifications('Assign_Class_Teacher', $records, $data, ['Student', 'Parent']);
            } else {
                Toastr::warning('Class Teacher already assigned.', 'Warning');
                return redirect()->back();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try {
            $classes = SmClass::get();
            $teachers = SmStaff::status()->where(function ($q) {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->get();
            $assign_class_teachers = SmAssignClassTeacher::with('class', 'section', 'classTeachers')->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $assign_class_teacher = SmAssignClassTeacher::find($id);
            $sections = SmSection::get();

            $teacherId = array();
            foreach ($assign_class_teacher->classTeachers as $classTeacher) {
                $teacherId[] = $classTeacher->teacher_id;
            }

            return view('backEnd.academics.assign_class_teacher', compact('assign_class_teacher', 'classes', 'teachers', 'assign_class_teachers', 'sections', 'teacherId'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmAssignClassTeacherRequest $request, $id)
    {

        $is_duplicate = SmAssignClassTeacher::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('class_id', $request->class)->where('section_id', $request->section)->where('id', '!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::warning('Duplicate entry found!', 'Warning');
            return redirect()->back();
        }
        DB::beginTransaction();

        try {
            SmClassTeacher::where('assign_class_teacher_id', $request->id)->delete();

            $assign_class_teacher = SmAssignClassTeacher::find($request->id);
            $assign_class_teacher->class_id = $request->class;
            $assign_class_teacher->academic_id = getAcademicId();
            $assign_class_teacher->section_id = $request->section;
            $assign_class_teacher->save();
            $assign_class_teacher_collection = $assign_class_teacher;
            $assign_class_teacher->toArray();

            $class_teacher = new SmClassTeacher();
            $class_teacher->assign_class_teacher_id = $assign_class_teacher->id;
            $class_teacher->teacher_id = $request->teacher;
            $class_teacher->school_id = auth()->user()->school_id;
            $class_teacher->academic_id = getAcademicId();
            $class_teacher->save();

            event(new ClassTeacherGetAllStudent($assign_class_teacher_collection, $class_teacher, 'update'));
            
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('assign-class-teacher');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        try {
            $id_key = 'assign_class_teacher_id';
            $tables = \App\tableList::getTableList($id_key, $id);

            try {
                DB::beginTransaction();

                $delete_query = SmClassTeacher::where('assign_class_teacher_id', $id)->delete();
                $delete_query = SmAssignClassTeacher::destroy($id);

                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
