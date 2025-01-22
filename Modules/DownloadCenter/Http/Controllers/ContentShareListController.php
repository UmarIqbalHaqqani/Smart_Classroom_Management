<?php

namespace Modules\DownloadCenter\Http\Controllers;

use App\Role;
use App\User;
use App\SmStudent;
use App\SmClassSection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\DownloadCenter\Entities\Content;
use Modules\DownloadCenter\Entities\ContentShareList;
use Modules\DownloadCenter\Http\Requests\ContentShareRequest;

class ContentShareListController extends Controller
{
    public function contentShareList()
    {
        try {
            if (auth()->user()->role_id == 2) {
                $student = SmStudent::where('user_id', auth()->user()->id)->with('studentRecord')->first();
                $allSharedContents = ContentShareList::get();
                $sharedContents = [];
                foreach ($allSharedContents as $allSharedContent) {
                    if (
                        $allSharedContent->send_type == "G"
                        && in_array(2, $allSharedContent->gr_role_ids ?? [])
                    ) {
                        array_push($sharedContents, $allSharedContent);
                    }
                    if (
                        $allSharedContent->send_type == "I"
                        && in_array(auth()->user()->id, $allSharedContent->ind_user_ids ?? [])
                    ) {
                        array_push($sharedContents, $allSharedContent);
                    }
                    if (
                        $allSharedContent->send_type == "C"
                        && $allSharedContent->class_id == $student->studentRecord->class_id
                        && in_array($student->studentRecord->section_id, $allSharedContent->section_ids ?? [])
                    ) {
                        array_push($sharedContents, $allSharedContent);
                    }
                }
            } else {
                $sharedContents = ContentShareList::with('user')->get();
            }
            return view('downloadcenter::contentShareList.contentShareList', compact('sharedContents'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentShareListSave(ContentShareRequest $request)
    {
        try {
            $newContent = new ContentShareList();
            $newContent->title = $request->title;
            $newContent->share_date = date('Y-m-d', strtotime($request->shareDate));
            $newContent->valid_upto = date('Y-m-d', strtotime($request->validUpto));
            $newContent->description = $request->description;
            $newContent->send_type = $request->selectTab;
            $newContent->content_ids = $request->content_ids;
            if ($request->selectTab == "G") {
                $newContent->gr_role_ids = $request->role;
            }
            if ($request->selectTab == "I") {
                $newContent->ind_user_ids = $request->individual_content_user;
            }
            if ($request->selectTab == "C") {
                $newContent->class_id = $request->class_id;
                if ($request->section_ids) {
                    $newContent->section_ids = $request->section_ids;
                }
            }
            $newContent->shared_by = auth()->user()->id;
            $newContent->save();

            return response()->success(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }
    public function contentGenarteUrlSave(ContentShareRequest $request)
    {
        try {
            $newContent = new ContentShareList();
            $newContent->title = $request->title;
            $newContent->share_date = date('Y-m-d', strtotime($request->shareDate));
            $newContent->valid_upto = date('Y-m-d', strtotime($request->validUpto));
            $newContent->content_ids = $request->content_ids;
            $newContent->send_type = "P";
            $newContent->url = generateRandomString(30);
            $newContent->shared_by = auth()->user()->id;
            $newContent->save();

            return response()->success(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }
    public function contentShareListDelete($id)
    {
        try {
            $content = ContentShareList::where('id', $id)->first();
            $content->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->route('download-center.content-share-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentShareLinkModal($url)
    {
        try {
            $sharedLink = ContentShareList::find($url);
            return view('downloadcenter::contentShareList.shared_content_modal', compact('sharedLink'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentViewLinkModal($id)
    {
        try {
            $viewContent = ContentShareList::find($id);
            $contents = Content::whereIn('id', $viewContent->content_ids)->get();
            $roles = ($viewContent->gr_role_ids) ? Role::whereIn('id', $viewContent->gr_role_ids)->get() : null;
            $individuals = ($viewContent->ind_user_ids) ? User::whereIn('id', $viewContent->ind_user_ids)->get() : null;
            $classSections = ($viewContent->class_id) ? SmClassSection::where('class_id', $viewContent->class_id)
                ->when($viewContent->section_ids, function ($q) use ($viewContent) {
                    $q->whereIn('section_id', $viewContent->section_ids);
                })
                ->with('className', 'sectionName')
                ->get() : null;
            return view('downloadcenter::contentShareList.view_content_modal', compact('viewContent', 'contents', 'roles', 'classSections', 'individuals'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentShareLink($url)
    {
        try {
            $sharedContent = ContentShareList::where('url', $url)->first();
            $contents = Content::whereIn('id', $sharedContent->content_ids)->get();
            return view('downloadcenter::contentShareList.sharedFilePage', compact('sharedContent', 'contents'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentContentShareList($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->with('studentRecord')->first();
            $records = studentRecords(null, $student_detail->id)->get();
            $allSharedContents = ContentShareList::get();
            $sharedContents = [];
            foreach ($allSharedContents as $allSharedContent) {
                if (
                    $allSharedContent->send_type == "G"
                    && in_array(2, $allSharedContent->gr_role_ids ?? [])
                ) {
                    array_push($sharedContents, $allSharedContent);
                }
                if (
                    $allSharedContent->send_type == "I"
                    && in_array($student_detail->user_id, $allSharedContent->ind_user_ids ?? [])
                ) {
                    array_push($sharedContents, $allSharedContent);
                }
                if (
                    $allSharedContent->send_type == "C"
                    && $allSharedContent->class_id == $student_detail->studentRecord->class_id
                    && in_array($student_detail->studentRecord->section_id, $allSharedContent->section_ids ?? [])
                ) {
                    array_push($sharedContents, $allSharedContent);
                }
            }
            return view('downloadcenter::contentShareList.parentContentShareList', compact('sharedContents', 'student_detail', 'records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
