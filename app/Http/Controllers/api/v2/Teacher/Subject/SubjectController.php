<?php

namespace App\Http\Controllers\api\v2\Teacher\Subject;

use App\SmSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Resources\v2\Teacher\Subject\SubjectListResource;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->latest('id')->get();
        $data = SubjectListResource::collection($subjects);
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
}
