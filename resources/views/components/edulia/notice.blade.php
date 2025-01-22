@if ($notices->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/notice-list') }}">@lang('edulia.notice_list')</a></p>
@else
    @foreach ($notices as $notice)
        <div class="noticeboard_inner_item">
            <a href="{{ route('frontend.notice-details', $notice->id) }}"
                class='noticeboard_inner_item_title'>{{ $notice->notice_title }}</a>
            <p>{{ __('edulia.published') }}: {{ date('d M, Y', strtotime($notice->notice_date)) }}</p>
            <a href="{{ route('frontend.notice-details', $notice->id) }}" class="site_btn_border"><i
                    class="fa fa-plus-circle"></i> {{ __('edulia.read_more') }}</a>
        </div>
    @endforeach
@endif
