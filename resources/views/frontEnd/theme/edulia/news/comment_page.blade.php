@foreach($newsComments as $comment)
    <div class="blog_comments_list">
        <div class="blog_comments_list_wrapper">
            <div class="blog_comments_list_left">
                <div class="blog_comments_list_img text-uppercase">
                    {{latterAvater($comment->user->full_name)}}
                </div>
            </div>
            <div class="blog_comments_list_right">
                <div class="blog_comments_list_info">
                    <div class="blog_comments_list_info_head">
                        <h5>
                            @if (auth()->check() && auth()->user()->role_id == 1 && $comment->user->role_id != 3)
                                <a href="{{ ($comment->user->role_id == 2) ? route('student_view', $comment->user->student->id): route('viewStaff', $comment->user->staff->id) }}" class="ps-0" target="balnk"rel="noreferrer noopener" >
                                    <h5>
                                        {{ auth()->check() ? str_replace(auth()->user()->full_name, __('common.you'), $comment->user->full_name) : $comment->user->full_name}}
                                    </h5>
                                </a>
                            @else
                                {{ auth()->check() ? str_replace(auth()->user()->full_name, __('common.you'), $comment->user->full_name) : $comment->user->full_name}}
                            @endif
                            <span class="d-flex cmt-gap">
                                @if (auth()->check())
                                    @if (userPermission('news-comment-status'))
                                        <span>
                                            <a href="{{ route('news-comment-status',['id' => $comment->id,'news_id' => $news->id, 'type' => 'frontend']) }}" class='site_btn' data-comment-id="{{$comment->id}}">
                                                {{ $comment->status == 1 ? __('common.unapprove') : __('common.approve')}}
                                            </a>
                                        </span>
                                    @endif
                                    @if (userPermission('news-comment-delete'))
                                        {{ Form::open(['route' => 'news-comment-delete', 'method' => 'POST']) }}
                                            <input type="hidden" name="news_id" value="{{$news->id}}">
                                            <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                            <input type="hidden" name="type" value="frontend">
                                            <span>
                                                <button class="site_btn cmt_rp_fornt" type="submit">{{__('edulia.delete')}}</button>
                                            </span>
                                        {{ Form::close() }}
                                    @endif
                                    <span><a href="#" class='site_btn newsReplyBtn' data-comment-id="{{$comment->id}}">{{__('edulia.reply')}}</a></span>
                                @endif
                            </span>
                        </h5>
                        <p>
                            <span>{{\Carbon\Carbon::parse($comment->created_at)->diffForhumans()}}</span>
                        </p>
                    </div>
                    <p>{{$comment->message}}</p>
                    @if (auth()->check())
                        @include('frontEnd.theme.edulia.news.comment_reply_form')
                    @endif
                </div>
            </div>
        </div>

    @if($comment->onlyChildrenFrontend->count())
        @include('frontEnd.theme.edulia.news.comment_page', ['newsComments' => $comment->onlyChildrenFrontend, 'level' => $level+1])
    @endif
    </div>
@endforeach