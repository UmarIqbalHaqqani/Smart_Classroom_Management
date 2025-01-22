<div class="blog_leave_comment normalComment">
    {{ Form::open(['route' => 'frontend.store-news-comment', 'method' => 'post']) }}
        <div class="blog_leave_comment_flex">
            <input type="hidden" name="type" value="comment">
            <input type="hidden" name="news_id" value="{{ $news->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        </div>
        <div class="input-control">
            <textarea class="input-control-input" name="message" cols="30" rows="10" placeholder='{{__('edulia.write_comment')}}' required></textarea>
        </div>
        <div class="input-control">
            <button type="submit" class="input-control-input">{{__('edulia.post_comment')}}</button>
        </div>
    {{ Form::close() }}
</div>