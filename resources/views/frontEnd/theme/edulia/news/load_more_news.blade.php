@foreach($news as $value)
    <div class="blog_card_wrapper">
        @if (file_exists($value->image))
            <div class="blog_card_wrapper_img"><img src="{{asset($value->image)}}" alt="{{$value->news_title}}"></div>
        @endif
        <div class="blog_card_wrapper_content">
            <a href="{{route('frontend.news-details',$value->id)}}" class='blog_card_wrapper_content_title'>{{$value->news_title}}</a>
            <p class="blog_card_wrapper_content_meta">{{dateConvert($value->publish_date)}}</p>
            <p>{!! $value->news_body !!}</p>
            <a href="{{route('frontend.news-details',$value->id)}}">+ {{__('edulia.read_more')}}</a>
        </div>
    </div>
@endforeach
<input type="hidden" value="{{$skip+count($news)}}" id="hide-button-new-news">
<input type="hidden" value="{{$count}}" id="count-news-new-news">
