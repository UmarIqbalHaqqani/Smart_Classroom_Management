<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\SmNoticeBoard;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Scopes\StatusAcademicSchoolScope;


class NoticeController extends Controller
{
    public function noticeList()
    {
        $roleInfo = auth()->user()->roles;

        $data = SmNoticeBoard::whereJsonContains('inform_to', 4)
        ->where('school_id', auth()->user()->school_id)
        ->where('publish_on', '<=', date('Y-m-d'))
        ->orderBy('id', 'DESC')
        ->get(['id', 'notice_title', 'notice_message', 'notice_date']);

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
                'message' => 'Notice list'
            ];
        }
        return response()->json($response);
    }
}
