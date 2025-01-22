<?php

namespace App\Http\Controllers\Admin\OnlineExam;

use App\SmQuestionGroup;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\OnlineExam\SmQuestionGroupRequest;
use App\tableList;
use Exception;

class SmQuestionGroupController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index()
    {
        try {
            $groups = SmQuestionGroup::select('id', 'title')->get();

            return view('backEnd.examination.question_group', compact('groups'));
        } catch (Exception $e) {
            toastrError();
           return redirect()->back();
        }
    }

    public function store(SmQuestionGroupRequest $request)
    {
        try{
            $group              = new SmQuestionGroup();
            $group->title       = $request->title;
            $group->school_id   = Auth::user()->school_id;
            $group->academic_id = getAcademicId();
            $group->save();
         
            toastrSuccess();
            return redirect()->back();
           
        } catch (\Exception $e) {
            toastrError();
            return redirect()->back();
        }
    }

    public function show($id)
    {
        try {
            $groups = SmQuestionGroup::select('id', 'title')->get();
            $group  = $groups->firstWhere('id', $id);

            return view('backEnd.examination.question_group', compact('groups', 'group'));
        } catch (Exception $e) {
            toastrError();
            return redirect()->back();
        }
    }
    public function update(SmQuestionGroupRequest $request, $id)
    {
        try{
            $group = SmQuestionGroup::find($request->id);             
            $group->title = $request->title;
            $group->save();

            toastrSuccess();
            return redirect('question-group');
        }catch (\Exception $e) {
            toastrError();
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $tables = tableList::getTableList('q_group_id', $id);

        try{
            if ($tables==null) {
                 SmQuestionGroup::destroy($id);

                 toastrSuccess();
                 return redirect('question-group');

            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';

                toastrError($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            toastrError($msg, 'Failed');
            return redirect()->back();
        }
    }
}