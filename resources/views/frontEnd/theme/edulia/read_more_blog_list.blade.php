@foreach ($blogs as $item)
    <div class="blog_card_wrapper searchBlogContent">
        <div class="blog_card_wrapper_img">
            <img src="{{ asset($item->image) }}" alt="{{ $item->news_title }}">
        </div>
        <div class="blog_card_wrapper_content">
            <a href="{{ route('frontend.news-details', $item->id) }}" class='blog_card_wrapper_content_title'>{{ $item->news_title }}</a>
            <p class="blog_card_wrapper_content_meta">{{ dateConvert($item->publish_date) }} / {{$item->category->category_name}}</p>
            <p>{!! mb_strimwidth($item->news_body, 0, 200, "...") !!}</p>
            <a href="{{ route('frontend.news-details', $item->id) }}">{{__('edulia.read_more')}}</a>
        </div>
    </div>
@endforeach




