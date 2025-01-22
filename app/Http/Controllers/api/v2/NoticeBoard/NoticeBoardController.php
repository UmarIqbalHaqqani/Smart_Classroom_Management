<?php

namespace App\Http\Controllers\api\v2\NoticeBoard;

use App\SmNoticeBoard;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;

class NoticeBoardController extends Controller
{
    public function studentNoticeboard(Request $request)
    {
        $data = [];
        if (auth()->user()->role_id == 2) {
            $data['allNotices'] = SmNoticeBoard::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->select('id', 'notice_title', 'notice_message', 'publish_on')
            ->where('active_status', 1)
            ->where('inform_to', 'LIKE', '%2%')
            ->orderBy('id', 'DESC')
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();
        } elseif(auth()->user()->role_id == 3) {
            $data['allNotices'] = SmNoticeBoard::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->select('id', 'notice_title', 'notice_message', 'publish_on')
            ->where('active_status', 1)
            ->where('inform_to', 'LIKE', '%3%')
            ->orderBy('id', 'DESC')
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();
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
                'message' => 'Notice board list'
            ];
        }
        return response()->json($response);
    }

    public function studentSingleNoticeboard(Request $request)
    {
        $data = [];

        if (auth()->user()->role_id == 2) {
            $data = SmNoticeBoard::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->select('id', 'notice_title', 'notice_message', 'publish_on')
            ->where('active_status', 1)
            ->where('inform_to', 'LIKE', '%2%')
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($request->notice_board_id);
        } elseif(auth()->user()->role_id == 3) {
            $data = SmNoticeBoard::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->select('id', 'notice_title', 'notice_message', 'publish_on')
            ->where('active_status', 1)
            ->where('inform_to', 'LIKE', '%3%')
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($request->notice_board_id);
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
                'message' => 'View single notice'
            ];
        }
        return response()->json($response);
    }
}
