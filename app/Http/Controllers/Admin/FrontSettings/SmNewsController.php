<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmNews;
use App\SmNewsCategory;
use Illuminate\Http\Request;
use App\Models\SmNewsComment;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmNewsRequest;
use DataTables;

class SmNewsController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
    }

    public function index()
    {

        try{
            $news = SmNews::where('school_id', app('school')->id)->get();
            $news_category = SmNewsCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.news.news_page', compact('news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmNewsRequest $request)
    {
        try{
            $destination='public/uploads/news/';
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);

            $news = new SmNews(); 
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;
            $news->image = fileUpload($request->image,$destination);
            $news->news_body = $request->description;
            $news->school_id = app('school')->id;
            $news->status = $request->status ?? 0;
            $news->mark_as_archive = $request->mark_as_archive ?? 0;
            if($request->is_global == 1){
                $news->is_global = $request->is_global;
                $news->auto_approve = 0;
                $news->is_comment = 0;
            }else{
                $news->is_global = 0;
                $news->auto_approve = $request->auto_approve ?? 0;
                $news->is_comment = $request->is_comment ?? 0;
            }
            $news->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function edit($id)
    {
        try{
            $news = SmNews::where('school_id', app('school')->id)->get();
            $add_news = SmNews::find($id);
            $news_category = SmNewsCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.news.news_page', compact('add_news', 'news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);
            $destination='public/uploads/news/'; 

            $news = SmNews::find($request->id);
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;           
            $news->image = fileUpdate($news->image,$request->image,$destination);           
            $news->news_body = $request->description;
            $news->school_id = app('school')->id;
            $news->status = $request->status ?? 0;
            $news->mark_as_archive = $request->mark_as_archive ?? 0;
            if($request->is_global == 1){
                $news->is_global = $request->is_global;
                $news->auto_approve = 0;
                $news->is_comment = 0;
            }else{
                $news->is_global = 0;
                $news->auto_approve = $request->auto_approve ?? 0;
                $news->is_comment = $request->is_comment ?? 0;
            }
            $news->save();
            Toastr::success('Operation successful', 'Success');
            return redirect('news');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function newsDetails($id)
    {
        try{
            $news = SmNews::find($id);
            return view('backEnd.frontSettings.news.news_details', compact('news'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function forDeleteNews($id)
    {
        try{
            return view('backEnd.frontSettings.news.delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function delete($id)
    {
        try{
            SmNews::destroy($id);
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function commentList()
    {
        try{
            return view('backEnd.frontSettings.news.news_comment_page');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function commentListDatatable()
    {
        if(request()->comment_filter_type){
            if(request()->comment_filter_type == 'approve'){
                $news_comments = SmNewsComment::with('news', 'user')
                        ->where('news_id', request()->comment_news_id)
                        ->where('status', 1);
            }else{
                $news_comments = SmNewsComment::with('news', 'user')
                        ->where('news_id', request()->comment_news_id)
                        ->where('status', 0);
            }
        }else{
            $news_comments = SmNewsComment::with('news', 'user');
        }
        
        return Datatables::of($news_comments)
                ->addIndexColumn()
                ->addColumn('user_name', function ($comment) {
                        return view('backEnd.frontSettings.news._news_author_view', compact('comment'));
                })
                ->addColumn('message', function ($comment) {
                        return view('backEnd.frontSettings.news._news_message_view', compact('comment'));
                })
                ->addColumn('post_info', function ($comment) {
                        return view('backEnd.frontSettings.news._news_response_view', compact('comment'));
                })
                ->addColumn('created_at', function ($comment) {
                        return dateConvert($comment->created_at);
                })
                ->rawColumns(['user_name', 'message', 'post_info'])
                ->make(true);
    }

    public function commentUpdate(Request $request)
    {
        try{
            $commentData = SmNewsComment::find($request->comment_id);
            $commentData->message = $request->message;
            $commentData->update();
            return response()->json(['success' => true]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function commentDelete(Request $request)
    {
        try{
            $parentDatas = SmNewsComment::where('parent_id', $request->comment_id)->get();
            if($parentDatas->count() > 0){
                foreach($parentDatas as $parentData){
                    $parentData->delete();
                }
            }
            SmNewsComment::destroy($request->comment_id);
            Toastr::success('Comment Deleted Successful', 'Success');
            if($request->type == 'frontend'){
                return redirect()->route('frontend.news-details', $request->news_id);
            }else{
                return redirect()->route('news-comment-list');
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function commentStatus($id, $news_id, $type)
    {
        try{
            $data = SmNewsComment::find($id);
            if($data->status == 1){
                $data->status = 0;
                $data->update();
            }else{
                $data->status = 1;
                $data->update();
            }
            Toastr::success('Comment Status Update Successful', 'Success');
            if($type == 'frontend'){
                return redirect()->route('frontend.news-details', $news_id);
            }else{
                return redirect()->route('news-comment-list');
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function commentStatusBackend($id)
    {
        try{
            $data = SmNewsComment::find($id);
            if($data->status == 1){
                $data->status = 0;
                $data->update();
            }else{
                $data->status = 1;
                $data->update();
            }
            return response()->json(['success' => true]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function viewNewsCategory($id)
    {
        try {
            $category_id = SmNewsCategory:: find($id);
            $newsCtaegories = SmNews::where('category_id',$category_id->id)
                        ->where('school_id', app('school')->id)
                        ->get();
            return view('frontEnd.home.category_news', compact('category_id','newsCtaegories'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
