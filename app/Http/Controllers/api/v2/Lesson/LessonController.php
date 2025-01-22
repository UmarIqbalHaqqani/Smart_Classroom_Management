<?php

namespace App\Http\Controllers\api\v2\Lesson;

use App\SmStaff;
use App\SmStudent;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmAcademicYear;
use App\SmAssignSubject;
use Carbon\CarbonPeriod;
use App\SmGeneralSettings;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmClassRoutineUpdate;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Entities\SmLesson;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\Lesson\Entities\LessonPlanner;
use App\Http\Resources\v2\LessonPlanResource;
use App\Http\Resources\v2\LessonPlanDetailsResource;
use Exception;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        try {
            $student_id = $request->student_id;
            $record_id  = $request->record_id;
            $next_date  = $request->next_date;
    
            $student_detail = SmStudent::withoutGlobalScope(SchoolScope::class)
                ->where('id', $student_id)
                ->first(['id', 'school_id']);
    
            if (!$student_detail) {
                return response()->json([
                    'success' => false,
                    'data'    => null,
                    'message' => 'Student not found',
                ]);
            }
    
            $week_start_id = SmGeneralSettings::where('school_id', $student_detail->school_id)->value('week_start_id');
            $week_end_name = SmWeekend::withoutGlobalScope(SchoolScope::class)
                ->where('school_id', $student_detail->school_id)
                ->where('id', $week_start_id)
                ->value('name');
    
            $start_day  = WEEK_DAYS_BY_NAME[$week_end_name ?? 'Monday'];
            $end_day    = $start_day == 0 ? 6 : $start_day - 1;
    
            if ($next_date) {
                $date = Carbon::parse($next_date);
    
                if ($date->isPast()) {
                    $end_date = $date->subDays(1);
                    $start_date = $end_date->subDays(6);
                } elseif ($date->isFuture()) {
                    $start_date = $date->addDay(1);
                    $end_date = $start_date->addDays(6);
                } else {
                    $start_date = Carbon::now()->startOfWeek($start_day);
                    $end_date = Carbon::now()->endOfWeek($end_day);
                }
            } else {
                $start_date = Carbon::now()->startOfWeek($start_day);
                $end_date = Carbon::now()->endOfWeek($end_day);
            }
    
            $data['this_week'] = $start_date->weekOfYear;
            $period = CarbonPeriod::create($start_date, $end_date);
    
            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
    
            $student_record = StudentRecord::where('school_id', $student_detail->school_id)->findOrFail($record_id);
    
            $data['weeks'] = SmWeekend::withoutGlobalScope(SchoolScope::class)
                ->with(['classRoutine' => function ($q) use ($student_record) {
                    $q->withoutGlobalScope(StatusAcademicSchoolScope::class)
                        ->where('class_id', $student_record->class_id)
                        ->where('section_id', $student_record->section_id)
                        ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                        ->where('school_id', auth()->user()->school_id);
                }])
                ->where('active_status', 1)
                ->where('school_id', $student_detail->school_id)
                ->orderBy('order', 'ASC')
                ->get()
                ->map(function ($weekend, $index) use ($dates) {
                    return [
                        'id'          => (int) $weekend->id,
                        'name'        => (string) $weekend->name,
                        'isWeekend'   => (int) $weekend->is_weekend,
                        'date'        => $dates[$index] ?? null,
                        'classRoutine' => LessonPlanResource::collection($weekend->classRoutine),
                    ];
                });
    
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Lesson plan list',
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ]);
        }
    }


    public function ViewlessonPlannerLesson(Request $request)
    {
        $lessonPlanDetail = LessonPlanner::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->with(['topics', 'subject' => function ($q) {
                $q->withoutGlobalScopes([StatusAcademicSchoolScope::class, GlobalAcademicScope::class])->where('school_id', auth()->user()->school_id);
            }, 'topicName' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }, 'class' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }, 'lessonName' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
            ->where('school_id', auth()->user()->school_id)
            ->find($request->lesson_plan_id);

        $lessonPlanDetail = new LessonPlanDetailsResource($lessonPlanDetail);

        if (!$lessonPlanDetail) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $lessonPlanDetail,
                'message' => 'Lesson plan detail'
            ];
        }
        return response()->json($response);
    }
}
