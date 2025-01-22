<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmAcademicYear;
use App\SmClass;
use App\SmStudent;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Modules\Alumni\Entities\Alumni;
use Yajra\DataTables\DataTables;

class GraduateListController extends Controller
{
    public function index()
    {
        $graduates = Graduate::where('school_id',auth()->user()->school_id)->with('student','section','smClass','unFaculty','unDepartment')->get();
        $sessions = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
        $classes  = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();
        return view('backEnd.graduate.graduate_list',compact('graduates','sessions','classes'));
    }
    public function search(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->withoutGlobalScope(StatusAcademicSchoolScope::class)->get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $academic_year = $request->academic_year;
            $class_id = $request->class_id;
            $name = $request->name;
            $section = $request->section_id;
            $data['un_session_id'] = $request->un_session_id;
            $data['un_academic_id'] = $request->un_academic_id;
            $data['un_faculty_id'] = $request->un_faculty_id;
            $data['un_department_id'] = $request->un_department_id;
            $data['un_semester_id'] = $request->un_semester_id;
            $data['un_semester_label_id'] = $request->un_semester_label_id;
            $data['un_section_id'] = $request->un_section_id;

            $graduates = Graduate::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.graduate.graduate_list', compact('classes', 'class_id', 'name', 'sessions', 'section', 'academic_year', 'data', 'graduates'));
        }
    }
    public function gradauateDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = Graduate::query();
            $records->where('school_id', auth()->user()->school_id);

            $records->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                $u_query->where('un_faculty_id', $request->un_faculty_id);
            }, function ($query) use ($request) {
                $query->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                });
            })
            ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                $u_query->where('un_department_id', $request->un_department_id);
            }, function ($query) use ($request) {
                $query->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                });
            })
            ->when(!$request->academic_year && moduleStatusCheck('University') == false && Schema::hasColumn('graduates', 'academic_id'), function ($query) use ($request) {
                $query->where('academic_id', getAcademicId());
            })
            
            ->when(moduleStatusCheck('University') && $request->filled('un_session_id'), function ($query) use ($request) {
                $query->where('un_session_id', $request->un_session_id);
            })
            ->when(moduleStatusCheck('University') && $request->filled('un_semester_label_id'), function ($query) use ($request) {
                $query->where('un_semester_label_id', $request->un_semester_label_id);
            });

            $records->when($request->filled('name'), function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->name . '%');
                });
                $query->orWhereHas('student', function ($q) use ($request) {
                    $q->where('admission_no', 'like', '%' . $request->name . '%');
                });
            });

            return DataTables::of($records)
                ->addColumn('admission_no', function ($row) {
                    $admission_no = '<a target="_blank" href="' . route('student_view', [$row->id]) . '">' . $row->student->admission_no . '</a>';
                    return $admission_no;
                })
                ->addColumn('full_name', function ($row) {
                    $full_name_link = '<a target="_blank" href="' . route('student_view', [$row->id]) . '">' . $row->student->full_name .'</a>';
                    return $full_name_link;
                })
                ->filterColumn('full_name', function ($query, $keyword) {
                    $query->whereHas('student', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', '%' . $keyword . '%')
                            ->orWhere('admission_no', 'like', '%' . $keyword . '%')
                            ->orWhere(function ($query) use ($keyword) {
                                $formattedKeyword = dateConvert($keyword);
                                $formattedKeyword = str_replace(['st', 'nd', 'rd', 'th'], '', $formattedKeyword);
                                $query->whereRaw("DATE_FORMAT(date_of_birth, '%D %b, %Y') LIKE ?", ['%' . $formattedKeyword . '%']);
                            })
                            ->orWhere('mobile', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('smClass', function ($q) use ($keyword) {
                            $q->where('class_name', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('section', function ($q) use ($keyword) {
                            $q->where('section_name', 'like', '%' . $keyword . '%');
                        });
                })
                ->addColumn('date_of_birth', function ($row) {
                    $date_of_birth =dateConvert($row->student->date_of_birth) ;
                    return $date_of_birth;
                })
                ->addColumn('class_sec', function ($row) {
                    $class_sec = [];

                    if (moduleStatusCheck('University') && $row->unFaculty && $row->unDepartment) {
                        $class_sec[] = $row->unFaculty->name . '(' . $row->unDepartment->name . ')';
                    } else {
                        if ($row->student && $row->student->studentRecords) {
                            $uniqueClassSections = collect();
                            foreach ($row->student->studentRecords as $classSec) {
                                $classSection = '';
                    
                                if (moduleStatusCheck('University') && $classSec->unFaculty && $classSec->unDepartment) {
                                    $classSection = $classSec->unFaculty->name . '(' . $classSec->unDepartment->name . ')';
                                } elseif ($classSec->class && $classSec->section) {
                                    $classSection = $classSec->class->class_name . '(' . $classSec->section->section_name . ')';
                                }
                                if (!empty($classSection) && !$uniqueClassSections->contains($classSection)) {
                                    $uniqueClassSections->push($classSection);
                                }
                            }
                            $class_sec = $uniqueClassSections->count() > 1 ? $uniqueClassSections->toArray() : [$row->smClass->class_name . '(' . $row->section->section_name . ')'];
                        } else {
                            $class_sec[] = $row->smClass->class_name . '(' . $row->section->section_name . ')';
                        }
                    }
                    
                    return implode(', ', $class_sec);
                    
                })->addColumn('mobile', function ($row) {
                    $mobile = $row->student->mobile ;
                    return $mobile;
                })
                ->addColumn('gender', function ($row) {
                    $gender = $row->student->gender;
                    return $gender ? $gender->base_setup_name : null;
                })

                ->addColumn('action', function ($row) {
                    $view = view('backEnd.graduate.graduateAction', compact('row'));
                    return (string)$view;
                })
                ->rawColumns(['action', 'full_name', 'date_of_birth', 'class_sec','admission_no','gender','mobile'])
                ->make(true);    
        }
    }
    
    public function viewTranscript ($id)
    {
        try{
            $graduate = Graduate::where('id',$id)->first();
            if($graduate != null) {
                $studentDetails = $graduate->student;
            } else {
                $alumni = Alumni::where('student_id',$id)->first();
                $studentDetails = $alumni->student;
            }
            $studentRecords = StudentRecord::where('student_id',$studentDetails->id)->distinct('un_academic_id')->get(); 
            $studentRecordDetails =  StudentRecord::where('student_id',$studentDetails->id);
            $tabPrint = 1;
            $semesterLabel = '';
            return view('backEnd.graduate.transcript.studentTranscript',
            compact('studentRecordDetails',
                'studentDetails',
                'studentRecords',
                'tabPrint',
                'semesterLabel',
                'graduate'
            ));
        }
        catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function printTranscript ($id)
    {
        try{
            $graduate = Graduate::find($id);
            $studentDetails = $graduate->student;
            $studentRecords = StudentRecord::where('student_id',$studentDetails->id)->distinct('un_academic_id')->get(); 
            $studentRecordDetails =  StudentRecord::where('student_id',$studentDetails->id);
            $tabPrint = 1;
            $semesterLabel = '';
            return view('backEnd.graduate.transcript.studentTranscriptPrint',
            compact('studentRecordDetails',
                'studentDetails',
                'studentRecords',
                'tabPrint',
                'semesterLabel',
                'graduate'
            ));
        }
        catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editRevertAsStudent($id)
    {
        try{
            $graduate = Graduate::find($id);
            if ($graduate) {
                return view('backEnd.graduate.inc._revert_as_student_modal',compact('graduate'));
            }
            else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

        }
        catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function revertAsStudent(Request $request)
    {
        try{
            $graduate = Graduate::find($request->id);
            $student = SmStudent::find($graduate->student_id);
            $studentRecord = StudentRecord::where('student_id',$student->id)->where('school_id', Auth::user()->school_id)->first();
            $studentRecord->is_graduate = 0;
            $studentRecord->is_promote  = 0;
            $studentRecord->save();
            $graduate->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }
        catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
