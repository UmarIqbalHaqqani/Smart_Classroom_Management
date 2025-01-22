<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;

use App\SmStudent;
use App\SmSubject;
use App\SmHomework;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmHomeworkStudent;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Validation\Rule;
use App\Traits\NotificationSend;
use App\Scopes\GlobalAcademicScope;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use App\Models\SmStudentRegistrationField;
use Modules\University\Entities\UnSemesterLabel;
use App\Http\Resources\v2\Teacher\HomeworkListResource;
use App\Http\Resources\v2\Teacher\HomeworkEvaluationListResource;
use App\Http\Controllers\Admin\StudentInfo\SmStudentReportController;

class HomeworkController extends Controller
{
    use NotificationSend;
    public function homeworkList()
    {
        $all_homeworks = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)->with('classes', 'sections', 'subjects', 'users')
            ->where('school_id', auth()->user()->school_id)
            ->latest()
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->get();
        if (teacherAccess()) {
            $homeworkLists = $all_homeworks->where('created_by', auth()->user()->id);
        } else {
            $homeworkLists = $all_homeworks;
        }
        $data = HomeworkListResource::collection($homeworkLists);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Homework list'
            ];
        }
        return response()->json($response);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'class_id' => ['required', Rule::exists('sm_homeworks', 'class_id')->where('school_id', auth()->user()->school_id)],
            'subject_id' => ['nullable', 'required_with:section_id', Rule::exists('sm_homeworks', 'subject_id')->where('school_id', auth()->user()->school_id)],
            'section_id' => ['nullable', Rule::exists('sm_homeworks', 'section_id')->where('school_id', auth()->user()->school_id)],
        ]);

        $all_homeworks = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('class_id', $request->class_id)
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->when($request->section_id, function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
            })
            ->latest()
            ->get();

        if (teacherAccess()) {
            $homeworkLists = $all_homeworks->where('created_by', auth()->user()->id);
        } else {
            $homeworkLists = $all_homeworks;
        }

        $data = HomeworkListResource::collection($homeworkLists);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Your homework list'
            ];
        }
        return response()->json($response);
    }

    public function evaluationHomework(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'homework_id' => 'required',
            'search' => 'nullable|string'
        ]);

        $student_ids = $this->classSectionStudent($request->merge([
            'class' => $request->class_id,
            'section' => $request->section_id
        ]));

        $students = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['homeworkContents'=> function ($q) use ($request) {
                $q->where('school_id', auth()->user()->school_id)
                    ->when($request->homework_id, function ($q) use ($request) {
                        $q->where('homework_id', $request->homework_id);
                    });
            }, 'homeworks' => function ($q) use ($request) {
                $q->where('school_id', auth()->user()->school_id)
                    ->when($request->homework_id, function ($q) use ($request) {
                        $q->where('homework_id', $request->homework_id);
                    });
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('active_status', 1)
            ->whereIn('id', $student_ids);
        if ($search = $request->search) {
            $students->where(function ($q) use ($search) {
                $q->where('admission_no', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        $data = $students->get()->map(function ($student) {
            $homeworkContents = $student->homeworkContents->map(function ($homeworkContent) {
                $files = $homeworkContent->file;
                if ($files) {
                    $files = json_decode($files);
                    foreach ($files as $file) {
                        return (string)asset($file);
                    }
                    // return (string)asset(collect(json_decode($files))->last());
                    // $files = ;
                    // foreach ($files as $file) {
                    //     return (string)asset($file);
                    // }
                }
            });

            return [
                'student_id'        => (int)$student->id,
                'admission_no'      => (int)$student->admission_no,
                'evaluated'         => (bool)$student->homeworks->count() > 0,
                'student_name'      => (string)$student->full_name,
                'homework_files'    => $homeworkContents->count() > 0 ? $homeworkContents : null
            ];
        });

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Find search list'
            ];
        }
        return response()->json($response);
    }

    public function addHomeworkDropdownListForClasses()
    {
    if (teacherAccess()) {
    $teacher_info = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
        ->where('school_id', auth()->user()->school_id)
        ->where('user_id', auth()->user()->id)
        ->first();

        if ($teacher_info) {
            $data = $teacher_info->classes()
                ->select('sm_classes.id', 'sm_classes.class_name')
                ->get();
        }
        } else {
            $data = SmClass::withoutGlobalScopes([StatusAcademicSchoolScope::class])
                ->select('id', 'class_name')
                ->where('school_id', auth()->user()->school_id)->get();
        }

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Class list'
            ];
        }
        return response()->json($response);
    }

    public function addHomeworkDropdownListForSubjects(Request $request)
    {
        $this->validate($request, [
            'class_id' => 'required'
        ]);
        $subjects = SmAssignSubject::query();
        $subjectIds = $subjects->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->get()->pluck(['subject_id'])->toArray();
        $data = SmSubject::withoutGlobalScopes([GlobalAcademicScope::class, StatusAcademicSchoolScope::class])->where('school_id', auth()->user()->school_id)->whereIn('id', $subjectIds)->get(['id', 'subject_name']);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Subject list'
            ];
        }
        return response()->json($response);
    }

    public function addHomeworkDropdownListForSection(Request $request)
    {
        $this->validate($request, [
            'class_id' => 'required',
            'subject_id' => 'required'
        ]);
        if (teacherAccess()) {
            $sectionIds = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->class_id)
                ->where('subject_id', '=', $request->subject_id)
                ->where('teacher_id', Auth::user()->staff->id)
                ->where('school_id', Auth::user()->school_id)
                ->select('section_id')->groupBy('section_id')
                ->get();
        } else {
            $sectionIds = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->class_id)
                ->where('subject_id', '=', $request->subject_id)
                ->where('school_id', Auth::user()->school_id)
                ->select('section_id')->groupBy('section_id')
                ->get();
        }

        $promote_sections = [];
        foreach ($sectionIds as $sectionId) {
            if ($request->globalType) {
                $promote_sections[] = SmSection::withoutGlobalScopes([StatusAcademicSchoolScope::class, GlobalAcademicScope::class])
                    ->where('id', $sectionId->section_id)
                    ->where('school_id', auth()->user()->school_id)->select('id', 'section_name')->first();
            } else {
                $promote_sections[] = SmSection::withoutGlobalScopes([StatusAcademicSchoolScope::class, GlobalAcademicScope::class])
                    ->where('id', $sectionId->section_id)
                    ->where('school_id', auth()->user()->school_id)->select('id', 'section_name')->first();
            }
        }

        $response = [
            'success' => true,
            'data' => $promote_sections,
            'message' => 'Operation Successfull.'
        ];

        return response()->json($response, 200);
    }


    public function storeHomeWork(Request $request)
    {

        $maxFileSize = generalSetting()->file_size * 1024;

        $this->validate($request, [
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'marks' => "required|numeric|min:0",
            'description' => "required",
            'assign_date' => ["required", 'date'],
            'submission_date' => ["required", 'after:homework_date'],
            'homework_file' => "sometimes|nullable|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4,ogx,oga,ogv,ogg,webm,mp3|max:" . $maxFileSize
        ], [
            'class_id.required' => 'Class field is required.',
            'section_id.required' => 'Section field is required.',
            'subject_id.required' => 'Subject field is required.'
        ]);

        $destination = 'public/uploads/homeworkcontent/';
        $upload_file = fileUpload($request->homework_file, $destination);
        $homeworks = new SmHomework();
        $homeworks->class_id = $request->class_id;
        $homeworks->section_id = $request->section_id;
        $homeworks->subject_id = $request->subject_id;
        $homeworks->homework_date = date('Y-m-d', strtotime($request->assign_date));
        $homeworks->submission_date = date('Y-m-d', strtotime($request->submission_date));
        $homeworks->marks = $request->marks;
        $homeworks->description = $request->description;
        $homeworks->file = $upload_file;
        $homeworks->created_by = Auth()->user()->id;
        $homeworks->school_id = auth()->user()->school_id;
        $homeworks->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $homeworks->save();

        $data['class_id'] = $homeworks->class_id;
        $data['section_id'] = $homeworks->section_id;
        $data['subject'] = @$homeworks->subjects->subject_name;

        $records = $this->studentRecordInfo($data['class_id'], $data['section_id'])->pluck('studentDetail.user_id');
        $this->sent_notifications('Assign_homework', $records, $data, ['Student', 'Parent']);

        $responseData = [
            'id'                => (int)$homeworks->id,
            'class_id'          => (int)$homeworks->class_id,
            'subject_id'        => (int)$homeworks->subject_id,
            'section_id'        => (int)$homeworks->section_id,
            'homework_date'     => (string)date('Y-m-d', strtotime($homeworks->homework_date)),
            'homework_file'     => $homeworks->file ? (string)asset($homeworks->file) : null,
            'submission_date'   => (string)date('Y-m-d', strtotime($homeworks->submission_date)),
            'marks'             => (float)$homeworks->marks,
            'description'       => (string)$homeworks->description
        ];

        if (!$responseData) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$responseData],
                'message' => 'Homework created successfully'
            ];
        }
        return response()->json($response);
    }

    public function storeHomeWorkEvaluation(Request $request)
    {
        if (checkAdmin()) {
            SmHomeworkStudent::where('student_id', $request->student_id)
                ->where('homework_id', $request->homework_id)
                ->delete();
        } else {
            SmHomeworkStudent::where('student_id', $request->student_id)
                ->where('homework_id', $request->homework_id)
                ->where('school_id', auth()->user()->school_id)
                ->delete();
        }

        $homework = SmHomework::find($request->homework_id);

        $homeworkstudent = new SmHomeworkStudent();
        $homeworkstudent->homework_id = $request->homework_id;
        $homeworkstudent->student_id = $request->student_id;
        $homeworkstudent->marks = $request->marks;
        $homeworkstudent->teacher_comments = $request->teacher_comments;
        $homeworkstudent->complete_status = $request->homework_status;
        $homeworkstudent->created_by = auth()->user()->id;
        $homeworkstudent->school_id = auth()->user()->school_id;
        $homeworkstudent->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $homeworkstudent->save();

        $homework->evaluation_date = date('Y-m-d');
        $homework->evaluated_by = auth()->user()->id;
        $homework->update();

        $data['class_id'] = $homework->class_id;
        $data['section_id'] = $homework->section_id;
        $data['subject'] = $homework->subjects->subject_name;
        $records = $this->studentRecordInfo($data['class_id'], $data['section_id'])->pluck('studentDetail.user_id');
        $this->sent_notifications('Homework_Evaluation', $records, $data, ['Student']);

        if (!$homework) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Evaluation submited successfully'
            ];
        }
        return response()->json($response);
    }

    private static function classSectionStudent($request)
    {
        $student_ids = StudentRecord::when($request->academic_year, function ($query) use ($request) {
            $query->where('academic_id', $request->academic_year);
        })
            ->when($request->class, function ($query) use ($request) {
                $query->where('class_id', $request->class);
            })
            ->when($request->section, function ($query) use ($request) {
                $query->where('section_id', $request->section);
            })
            ->when(!$request->academic_year, function ($query) {
                $query->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR());
            })->where('school_id', auth()->user()->school_id)->where('is_promote', 0)->pluck('student_id')->unique();

        return $student_ids;
    }
}
