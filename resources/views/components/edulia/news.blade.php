<style>
    .blog_item_inner {
        background: #ffffff;
    }
</style>
@if ($news->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/news') }}">@lang('edulia.news')</a></p>
@else
    @foreach ($news as $article)
        <div class="col-lg-{{ $colum }}">
            <div class="blog_item">
                <div class="blog_item_img">
                    <img src="{{ asset($article->image) }}" alt="{{ $article->news_title }}">
                </div>
                <div class="blog_item_inner">
                    <span class="blog_item_meta">{{ dateConvert($article->publish_date) }}</span>
                    <a href="{{ route('frontend.news-details', $article->id) }}"
                        class='blog_item_title'>{{ $article->news_title }}</a>
                    <a href="{{ route('frontend.news-details', $article->id) }}" class='blog_item_readmore'><i
                            class="fa fa-plus-circle"></i> {{ $readmore }}</a>
                </div>
            </div>
        </div>
    @endforeach
@endif
