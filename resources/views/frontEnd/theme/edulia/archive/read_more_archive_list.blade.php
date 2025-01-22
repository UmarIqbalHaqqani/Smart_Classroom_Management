@foreach ($archives as $item)
    <div class="archive_card_wrapper no-img searchArchiveContent">
        <div class="archive_card_wrapper_content">
            <a href="{{ route('frontend.news-details', $item->id) }}"
                class='archive_card_wrapper_content_title'>{{ $item->news_title }}</a>
            <p class="archive_card_wrapper_content_meta">{{ dateConvert($item->publish_date) }} /
                {{ $item->category->category_name }}</p>
            <p>{!! $item->news_body !!}</p>
            <a href="{{ route('frontend.news-details', $item->id) }}">+ {{ __('edulia.read_more') }}</a>
        </div>
    </div>
@endforeach
