<?php

namespace Modules\BehaviourRecords\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\BehaviourRecords\Entities\AssignIncident;
use Modules\BehaviourRecords\Entities\AssignIncidentComment;
use Modules\BehaviourRecords\Http\Requests\IncidentCommentRequest;

class BehaviourCommentController extends Controller
{
    public function incidentComment($id)
    {
        try {
            $incident = AssignIncident::where('id', $id)->with('studentRecord.studentDetail', 'studentRecord.incidents')->first();
            return view('behaviourrecords::comment.behaviour_comment', compact('incident'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function getIncidentComment($id)
    {
        try {
            $incidentComments = AssignIncidentComment::where('incident_id', $id)->with('user', 'incident', 'user.roles')->get();
            return view('behaviourrecords::comment.behaviour_comment_list', compact('incidentComments'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function incidentCommentSave(IncidentCommentRequest $request)
    {
        try {
            $behaviourComment = new AssignIncidentComment();
            $behaviourComment->user_id = Auth::user()->id;
            $behaviourComment->comment = $request->comment;
            $behaviourComment->incident_id = $request->incident_id;
            $behaviourComment->save();
            return response()->json(['message' => 'Successful']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }
}
