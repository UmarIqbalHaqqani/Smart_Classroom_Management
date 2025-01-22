
<a href="{{userPermission('edit-news') ? route('edit-news',$comment->news_id): '#'}}" target=" ">{{$comment->news->news_title}}</a>
<div>
    <a href="{{route('frontend.news-details', $comment->news_id)}}" target=" ">{{__('front_settings.view_post')}}</a>
</div>
<div class="d-flex align-items-center">
    <a href="#" class="cmtUnapprove" data-news-id="{{$comment->news_id}}">
        {{$comment->count_approve_comment}}
    </a>
    <a href="#" class="cmtapprove" data-news-id="{{$comment->news_id}}">
     - {{$comment->count_unaprove_comment}}
    </a>
    <input type="hidden" id="commentNewsId" value="">
    <input type="hidden" id="commentFilterType" value="">
</div>