<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Forum\Entities\CommentVote;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumCommentReply;
use Modules\Forum\Entities\ForumTitle;
use Modules\Forum\Entities\ForumTopic;
use Modules\Forum\Entities\ForumUserVote;
use Modules\Forum\Entities\ReplyVote;
use Modules\RolePermission\Entities\InfixRole;

class UserForumController extends Controller
{
    public function index () 
    {
        $forum_categories = ForumCategory::select('id','title')->get();
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;
        
        $forums = ForumTitle::with([
            'forumCategory',
            'forumCategory.schools',
            'forumTopics.forumComments'
            ])
            ->orderBy('id', 'desc')
            ->get();

        $filtered_forum_items = $forums->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_items = $filtered_forum_items;

        $forums = ForumTitle::with(['forumCategory', 'forumCategory.schools'])
        ->orderBy('id', 'desc')
        ->get();

        $filtered_forum_items = $forums->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_titles = $filtered_forum_items;

        return view('backEnd.userForum.title_index',compact('forum_categories','forum_items','forum_titles'));
    }

    public function userTitleSearch(Request $request)
    {
        $forum_categories = ForumCategory::select('id', 'title')->get();

        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;

        $query = ForumTitle::with(['forumCategory', 'forumCategory.schools'])
            ->orderBy('id', 'desc')
            ->when($request->forum_category_id, function ($query) use ($request) {
                $query->whereHas('forumCategory', function ($q) use ($request) {
                    $q->where('id', $request->forum_category_id);
                });
            })
            ->when($request->forum_title_id, function ($query) use ($request) {
                $query->where('id', $request->forum_title_id);
            });

        $forum_items = $query->get()->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_titles = $forum_items;

        return view('backEnd.userForum.title_index', compact('forum_categories', 'forum_items', 'forum_titles'));
    }

    public function forumTopicIndex($id)
    {
        $forum_categories = ForumCategory::select('id','title')->get();

        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;

        $forums = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('forum_title_id', $id)
            ->orderBy('id', 'desc')
            ->with('createdBy')
            ->get();

        $filtered_forum_items = $forums->filter(function ($item) use ($role_id, $school_id, $user) {
            #$available_for                  = json_decode($item->available_for, true);
            $school_ids                     = $item->forumTitle->forumCategory->schools->pluck('id')->toArray();
            #$is_in_available_for_and_school = in_array($role_id, $available_for) && in_array($school_id, $school_ids);
            $is_in_available_for_and_school = in_array($school_id, $school_ids);
            $is_created_by_user = $item->created_by == $user->id;
            return $is_in_available_for_and_school || $is_created_by_user;
        });

        $forum_topic_items = $filtered_forum_items;
        $roles = InfixRole::where('is_saas', 0)
            ->where('active_status', 1)
            ->where(function ($query) use ($school_id) {
                $query->where('school_id', $school_id)
                    ->orWhere('type', 'System');
            })
            ->where('id', '!=', 1)
            ->get();  
            
        $forum_title = ForumTitle::where('id', $id)->first();
        $forum_topics = $forums;
        $forums = ForumTitle::with(['forumCategory', 'forumCategory.schools'])
        ->orderBy('id', 'desc')
        ->get();

        $filtered_forum_items = $forums->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_titles = $filtered_forum_items;

        return view('backEnd.userForum.topic_index',compact('forum_topics','forum_categories','forum_topic_items', 'roles','forum_title','forum_titles'));
    }

    public function userTopicSearch(Request $request)
    {
        $forum_categories = ForumCategory::select('id', 'title')->get();
    
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;
    
        $roles = InfixRole::where('is_saas', 0)
            ->where('active_status', 1)
            ->where(function ($query) use ($school_id) {
                $query->where('school_id', $school_id)
                    ->orWhere('type', 'System');
            })
            ->where('id', '!=', 1)
            ->get();
    
        $forum_topic_items = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('forum_title_id', $request->forum_title_id)
            ->when($request->forum_topic_id, function ($query) use ($request) {
                $query->where('id', $request->forum_topic_id);
            })
            ->orderBy('id', 'desc')
            ->get();
    
        $forum_titles = ForumTitle::with(['forumCategory', 'forumCategory.schools'])
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($item) use ($role_id, $school_id) {
                $available_for = json_decode($item->available_for, true);
                $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
                return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
            });
    
        $forum_title = ForumTitle::find($request->forum_title_id);
    
        $forum_topics = ForumTopic::where('forum_title_id', $request->forum_title_id)
            ->orderBy('id', 'desc')
            ->get();
    
        return view('backEnd.userForum.topic_index', compact('forum_title', 'forum_categories', 'forum_topic_items', 'roles', 'forum_titles', 'forum_topics'));
    }
    
    public function forumTopicStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'role'              => 'required|array|min:1',
            // 'role.*'            => 'exists:infix_roles,id',
            'forum_title_id'    => 'required|exists:forum_titles,id',
            'title'             => 'required|string',
            'description'       => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $error) {
                Toastr::error($error);
            }
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try {
            $user = Auth::user();

            $forum = new  ForumTopic();
            $forum->forum_title_id = $request->forum_title_id;
            $forum->title          = $request->title;
            $forum->description    = $request->description;
            #$forum->available_for  = json_encode($request->role);
            $forum->created_by     = $user->id;
            $forum->save();

            Toastr::success('Operation Success', 'Success');
            return redirect()->back();
        }
        catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function forumTopicUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'role'              => 'required|array|min:1',
            // 'role.*'            => 'exists:infix_roles,id',
            'forum_title_id'    => 'required|exists:forum_titles,id',
            'title'             => 'required|string',
            'description'       => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $error) {
                Toastr::error($error);
            }
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try {
            $user = Auth::user();

            $forum = ForumTopic::find($request->forum_topic_id);
            $forum->forum_title_id = $request->forum_title_id;
            $forum->title          = $request->title;
            $forum->description    = $request->description;
            #$forum->available_for  = json_encode($request->role);
            $forum->created_by     = $user->id;
            $forum->save();

            Toastr::success('Operation Success', 'Success');
            return redirect()->back();
        }
        catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function forumTopicDestroy($id)
    {
        try {
            $forum = ForumTopic::find($id);
            $forum->delete();

            Toastr::success('Operation Success', 'Success');
            return redirect()->back();
        }
        catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function forumTopicView($id)
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;

        $forum = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('id', $id)
            ->first();

        $recent_forum = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('forum_title_id', $forum->forum_title_id)
            ->orderBy('id', 'desc')
            ->where('id', '!=', $id)
            ->limit(5)
            ->get();

        if ($forum) {
            #$available_for = json_decode($forum->available_for, true);

            $school_ids = $forum->forumTitle->forumCategory->schools->pluck('id')->toArray();

            #$is_in_available_for_and_school = in_array($role_id, $available_for) && in_array($school_id, $school_ids);
            $is_in_available_for_and_school =  in_array($school_id, $school_ids);

            $is_created_by_user = $forum->created_by == $user->id;

            if (!$is_in_available_for_and_school && !$is_created_by_user) {
                abort(403, 'You do not have permission to view this topic.');
            }

            if($forum->created_by != $user->id) {
                $forum->increment('total_views');
            }
        } else {
            abort(404, 'Topic not found.');
        }

        return view('backEnd.userForum.topic_view', compact('forum', 'recent_forum'));
    }

    public function userVote(Request $request)
    {
        $forumId = $request->forumId;
        $voteType = $request->voteType;
        $user = Auth::user();
        $forum = ForumTopic::find($forumId);

        if (!$forum) {
            return response()->json(['error' => 'Forum topic not found.'], 404);
        }

        $existingVote = ForumUserVote::where('user_id', $user->id)
                                    ->where('forum_topic_id', $forumId)
                                    ->first();

        if ($existingVote) {
            if ($existingVote->vote != $voteType) {
                if ($voteType === 'upvote') {
                    if ($forum->downvotes > 0) {
                        $forum->decrement('downvotes');
                    }
                    $forum->increment('upvotes');
                } else {
                    if ($forum->upvotes > 0) {
                        $forum->decrement('upvotes');
                    }
                    $forum->increment('downvotes');
                }
                $existingVote->vote = $voteType;
                $existingVote->save();
                $forum->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Vote updated successfully',
                    'upvotes' => $forum->upvotes,
                    'downvotes' => $forum->downvotes
                ]);
            } else {
                return response()->json(['error' => 'You have already voted.'], 400);
            }
        } else {
            if ($voteType === 'upvote') {
                $forum->increment('upvotes');
            } else {
                $forum->increment('downvotes');
            }
            $forum->save();

            $u_vote = new ForumUserVote();
            $u_vote->user_id = $user->id;
            $u_vote->forum_topic_id = $forumId;
            $u_vote->vote = $voteType;
            $u_vote->save();

            return response()->json([
                'success' => true,
                'message' => 'Vote counted successfully',
                'upvotes' => $forum->upvotes,
                'downvotes' => $forum->downvotes
            ]);
        }
    }

    public function commentStore (Request $request)
    {
        $request->validate([
            'forum_topic_id' => 'required|exists:forum_topics,id',
            'comment' => 'required|string',
        ]);

        $user = Auth::user();

        $comment = new ForumComment();
        $comment->forum_topic_id = $request->forum_topic_id;
        $comment->comment        = $request->comment;
        $comment->user_id        = $user->id;
        $comment->school_id      = $user->school_id;
        $comment->created_at     = now();

        $forumSetting = forumSetting();
        if ($forumSetting && $forumSetting->comment_auto_approve == 1 || $user->role_id == 1) {
            $comment->is_approved = 1;
            Toastr::success('Operation Success', 'Success');
        } else {
            $comment->is_approved = 0;
            Toastr::success('Your comment is awaiting approval', 'Success');
        }
        $comment->save();
        return redirect()->back();
    }

    public function commentReplyStore(Request $request)
    {
        $request->validate([
            'forum_topic_id'        => 'required|exists:forum_topics,id',
            'comment_id'            => 'required',
            'reply'                 => 'required|string'
        ]);

        $user = Auth::user();
        $reply = new ForumCommentReply();
        $reply->forum_topic_id  = $request->forum_topic_id;
        $reply->user_id         = $user->id;
        $reply->reply           = $request->reply;
        $reply->created_at      = now();
        $reply->forum_comment_id = $request->comment_id;
        
        $forumSetting = forumSetting();
        if ($forumSetting && $forumSetting->reply_auto_approve == 1 || $user->role_id == 1) {
            $reply->is_approved = 1;
            Toastr::success('Operation Success', 'Success');
        } else {
            $reply->is_approved = 0;
            Toastr::success('Your reply is awaiting approval', 'Success');
        }
        $reply->save();
        return redirect()->back();
    }

    public  function userMyTopics()
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;

        $forum_categories = ForumCategory::select('id','title')->get();
        $forums_title = ForumTitle::with([
            'forumCategory',
            'forumCategory.schools',
            'forumTopics.forumComments'
            ])
            ->orderBy('id', 'desc')
            ->get();

        $filtered_forum_items = $forums_title->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_titles = $filtered_forum_items;

        $forums = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
        ->where('created_by', auth()->user()->id)
        ->orderBy('id', 'desc')
        ->get();

        $forum_topics = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
        ->where('created_by', auth()->user()->id)
        ->orderBy('id', 'desc')
        ->get();

        $roles = InfixRole::where('is_saas', 0)
        ->where('active_status', 1)
        ->where(function ($query) use ($school_id) {
            $query->where('school_id', $school_id)
                ->orWhere('type', 'System');
        })
        ->where('id', '!=', 1)
        ->get();  

        return view('backEnd.userForum.user_forum_my_topic', compact('forums','forum_categories','forum_titles','forum_topics','roles'));
    }

    public function userTopicCommentUpdate(Request $request)
    {
        $request->validate([
            'forum_topic_id'    => 'required|exists:forum_topics,id',
            'comment'           => 'required|string',
            'comment_id'        => 'nullable',
        ]);

        $user = Auth::user();

        $comment = ForumComment::where('forum_topic_id',$request->forum_topic_id)->where('id',$request->comment_id)->first();
        $comment->forum_topic_id = $request->forum_topic_id;
        $comment->comment        = $request->comment;
        $comment->user_id        = $user->id;
        $comment->school_id      = $user->school_id;
        $comment->updated_at     = now();
        $comment->save();

        Toastr::success('Operation Success', 'Success');
        return redirect()->back();
    }

    public function userCommentReplyUpdate(Request $request)
    {
        $request->validate([
            'forum_topic_id'        => 'required|exists:forum_topics,id',
            'comment_id'            => 'required',
            'reply_id'              => 'nullable',
            'reply'                 => 'required|string'
        ]);

        $user = Auth::user();
        $reply = ForumCommentReply::where('forum_topic_id',$request->forum_topic_id)->where('forum_comment_id',$request->comment_id)->where('id',$request->reply_id)->first();
        $reply->forum_topic_id  = $request->forum_topic_id;
        $reply->user_id         = $user->id;
        $reply->reply           = $request->reply;
        $reply->updated_at      = now();
        $reply->forum_comment_id = $request->comment_id;
        $reply->save();
        
        Toastr::success('Operation Success', 'Success');
        return redirect()->back();
    }

    public function userCommentDelete($id)
    {
        $comment = ForumComment::where('id', $id)->first();

        if(!$comment) {
            Toastr::error('Data Not Found', 'Error');
            return redirect()->back();
        } else {
            $comment->delete();      
            Toastr::success('Operation Success', 'Success');
            return redirect()->back();
        }
        
    }

    public function userCommentReplyDelete($id)
    {
        $comment = ForumCommentReply::where('id', $id)->first();

        if(!$comment) {
            Toastr::error('Data Not Found', 'Error');
            return redirect()->back();
        } else {
            $comment->delete();      
            Toastr::success('Operation Success', 'Success');
            return redirect()->back();
        }
    }

    public function userMyTopicSearch(Request $request)
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;

        $forum_categories = ForumCategory::select('id', 'title')->get();
        
        $forum_titles = ForumTitle::with([
            'forumCategory',
            'forumCategory.schools',
            'forumTopics.forumComments'
        ])
        ->orderBy('id', 'desc')
        ->get()
        ->filter(function ($item) use ($role_id, $school_id) {
            $available_for = json_decode($item->available_for, true);
            $school_ids = $item->forumCategory->schools->pluck('id')->toArray();
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });

        $forum_topics = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('created_by', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $forums = ForumTopic::with(['forumTitle', 'forumTitle.forumCategory.schools'])
            ->where('created_by', $user->id)
            ->orderBy('id', 'desc')
            ->when($request->news_category_id, function ($query) use ($request) {
                $query->whereHas('forumTitle.forumCategory', function ($q) use ($request) {
                    $q->where('id', $request->news_category_id);
                });
            })
            ->when($request->forum_title_id, function ($query) use ($request) {
                $query->whereHas('forumTitle', function ($q) use ($request) {
                    $q->where('id', $request->forum_title_id);
                });
            })
            ->when($request->forum_topic_id, function ($query) use ($request) {
                $query->where('id', $request->forum_topic_id);
            })
            ->get();

        $roles = InfixRole::where('is_saas', 0)
            ->where('active_status', 1)
            ->where(function ($query) use ($school_id) {
                $query->where('school_id', $school_id)
                    ->orWhere('type', 'System');
            })
            ->where('id', '!=', 1)
            ->get();

        return view('backEnd.userForum.user_forum_my_topic', compact('forums', 'forum_categories', 'forum_titles', 'forum_topics', 'roles'));
    }

    public function userCommentVote(Request $request)
    {
        $commentId = $request->commentId;
        $voteType = $request->voteType;
        $user = Auth::user();
        $forum = ForumComment::find($commentId);

        if (!$forum) {
            return response()->json(['error' => 'Forum Comment not found.'], 404);
        }

        $existingVote = CommentVote::where('user_id', $user->id)
                                    ->where('forum_comment_id', $commentId)
                                    ->first();

        if ($existingVote) {
            if ($existingVote->vote != $voteType) {
                if ($voteType === 'upvote') {
                    if ($forum->downvotes > 0) {
                        $forum->decrement('downvotes');
                    }
                    $forum->increment('upvotes');
                } else {
                    if ($forum->upvotes > 0) {
                        $forum->decrement('upvotes');
                    }
                    $forum->increment('downvotes');
                }
                $existingVote->vote = $voteType;
                $existingVote->save();
                $forum->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Vote updated successfully',
                    'upvotes' => $forum->upvotes,
                    'downvotes' => $forum->downvotes
                ]);
            } else {
                return response()->json(['error' => 'You have already voted.'], 400);
            }
        } else {
            if ($voteType === 'upvote') {
                $forum->increment('upvotes');
            } else {
                $forum->increment('downvotes');
            }
            $forum->save();

            $u_vote = new CommentVote();
            $u_vote->user_id = $user->id;
            $u_vote->forum_comment_id = $commentId;
            $u_vote->vote = $voteType;
            $u_vote->save();

            return response()->json([
                'success' => true,
                'message' => 'Vote counted successfully',
                'upvotes' => $forum->upvotes,
                'downvotes' => $forum->downvotes
            ]);
        }
    }

    public function userReplyVote(Request $request)
    {
        $replyId = $request->replyId;
        $voteType = $request->voteType;
        $user = Auth::user();
        $forum = ForumCommentReply::find($replyId);

        if (!$forum) {
            return response()->json(['error' => 'Forum comment reply not found.'], 404);
        }

        $existingVote = ReplyVote::where('user_id', $user->id)
                                    ->where('forum_comment_reply_id', $replyId)
                                    ->first();

        if ($existingVote) {
            if ($existingVote->vote != $voteType) {
                if ($voteType === 'upvote') {
                    if ($forum->downvotes > 0) {
                        $forum->decrement('downvotes');
                    }
                    $forum->increment('upvotes');
                } else {
                    if ($forum->upvotes > 0) {
                        $forum->decrement('upvotes');
                    }
                    $forum->increment('downvotes');
                }
                $existingVote->vote = $voteType;
                $existingVote->save();
                $forum->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Vote updated successfully',
                    'upvotes' => $forum->upvotes,
                    'downvotes' => $forum->downvotes
                ]);
            } else {
                return response()->json(['error' => 'You have already voted.'], 400);
            }
        } else {
            if ($voteType === 'upvote') {
                $forum->increment('upvotes');
            } else {
                $forum->increment('downvotes');
            }
            $forum->save();

            $u_vote = new ReplyVote();
            $u_vote->user_id = $user->id;
            $u_vote->forum_comment_reply_id = $replyId;
            $u_vote->vote = $voteType;
            $u_vote->save();

            return response()->json([
                'success' => true,
                'message' => 'Vote counted successfully',
                'upvotes' => $forum->upvotes,
                'downvotes' => $forum->downvotes
            ]);
        }
    }
}
