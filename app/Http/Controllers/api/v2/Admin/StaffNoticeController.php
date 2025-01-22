<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmNoticeBoard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\StatusAcademicSchoolScope;

class StaffNoticeController extends Controller
{
    public function noticeList()
    {
        $allNotices = SmNoticeBoard::whereJsonContains('inform_to', 5)
            ->where('school_id', auth()->user()->school_id)
            ->where('publish_on', '<=', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->get(['id', 'notice_title', 'notice_message', 'notice_date']);
        if (!$allNotices) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $allNotices,
                'message' => 'Notice list successful'
            ];
        }
        return response()->json($response);
    }
}