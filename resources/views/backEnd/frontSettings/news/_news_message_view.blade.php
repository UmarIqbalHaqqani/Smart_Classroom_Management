@if ($comment->parent_id)
<p>{{ __('front_settings.in_reply_to')}} <a href="{{route('frontend.news-details', $comment->news_id)}}" target=" ">{{$comment->reply_to($comment->parent_id)}}</a></p>

@endif
<div>
    {{$comment->message}}
</div>
<div class="action_button_for_news mt-10">
    @if (userPermission('news-comment-status'))
        <a href="#" class="approvunappro" data-comment-id="{{$comment->id}}">
            {{ $comment->status == 1 ? __('common.unapprove') : __('common.approve')}}
        </a> |
    @endif
    <a href="#" class="quickReplyNewsComnt" data-comment-id="{{$comment->id}}" data-news-id="{{$comment->news_id}}">{{__('common.quick_reply')}}</a>
    | <a href="#" class="quickReplyNewsEdit" data-comment-id="{{$comment->id}}" data-comment-message="{{$comment->message}}">{{__('common.edit')}}</a>
    @if (userPermission('news-comment-delete'))
        | <a href="#" onclick="deleteNewsComment({{$comment->id}}, {{$comment->news_id}})">{{__('common.delete')}}</a>
    @endif
</div>

<div class="divActiveReply reply_to_comment_div_{{$comment->id}}"></div>

<div class="divActiveEdit edit_to_comment_div_{{$comment->id}}"></div>
