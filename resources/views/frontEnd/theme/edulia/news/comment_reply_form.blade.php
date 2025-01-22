<div class="blog_leave_comment replyDiv_{{$comment->id}}" style="display: none">
    {{ Form::open(['route' => 'frontend.store-news-comment', 'method' => 'post', 'class' => 'replyNewForm']) }}
        <h3>{{__('edulia.reply')}}</h3>
        <div class="input-control">
            <input type="hidden" name="news_id" value="{{ $news->id }}">
            <input type="hidden" class="parentId" name="parent_id" value="">
            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
            <textarea class="input-control-input" name="message" cols="30" rows="10" placeholder='{{__('edulia.write_reply')}}'></textarea>
        </div>
        <div class="input-control">
            <button type="submit" class="input-control-input">{{__('edulia.post_reply')}}</button>
        </div>
    {{ Form::close() }}
</div>