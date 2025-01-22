@if ($noticeBoards->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-light">@lang('edulia.no_data_available_please_go_to') <a class="text-warning" target="_blank"
            href="{{ URL::to('/notice-list') }}">@lang('edulia.homepage_noticeboard')</a></p>
@else
    @foreach ($noticeBoards as $notice)
        <li>
            <div class="noticeboard_inner_item">
                <p><span>{{ __('edulia.published') }}:</span> {{ dateConvert($notice->notice_date) }}</p>
                <a href="{{route('frontend.notice-details',$notice->id )}}" class='noticeboard_inner_item_title'>
                    {{ $notice->notice_title }}
                </a>
            </div>
        </li>
    @endforeach
@endif
