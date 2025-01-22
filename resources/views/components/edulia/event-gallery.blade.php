<style>
    .event_short_title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@php 
    $events_count = App\SmEvent::where('school_id', app()->bound('school') ? app('school')->id : 1)->count();
@endphp
@if ($events->isEmpty())
    <p class="text-center text-danger">@lang('edulia.no_data_available')</p>
@endif

<div id="dynamicLoadMoreData" class="row">
    @foreach ($events as $event)
        <div class="col-lg-{{$column}}">
            <div class="events_item">
                @if (file_exists(public_path($event->uplad_image_file)))
                    <div class="events_item_img">
                        <img src="{{asset($event->uplad_image_file)}}" alt="{{$event->event_title}}">
                    </div>
                @endif
                <div class="events_item_inner">
                    <div class="events_item_inner_meta">
                        @if($dateshow == 1)
                            <span><i class="fal fa-clock"></i>
                                {{dateConvert($event->from_date).' '.__('common.to').' '.dateConvert($event->to_date)}}
                            </span>
                        @endif
                        @if ($enevtlocation == 1)
                            <span>
                                <i class="fal fa-map-marker-alt"></i>
                                {{$event->event_location}}
                            </span>
                        @endif
                    </div>
                    @if ($event->event_title)
                        <a href="{{route('frontend.event-details', $event->id)}}" class="events_item_inner_title event_short_title">
                            {{$event->event_title}}
                        </a>
                    @endif
                    <a href="{{route('frontend.event-details', $event->id)}}">
                        <i class="fa fa-plus-circle"></i>{{$button}}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if (Request::is('events'))
    @if ($count < $events_count)
        <div class="row text-center">
            <div class="col-md-12">
                <div class="load_more section_padding_top">
                    <a href="#" class="site_btn load_more_event_btn" data-skip="{{$count}}">{{ __('edulia.load_more') }}</a>
                </div>
            </div>
        </div>
    @endif
@endif

@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $(document).on('click', '.load_more_event_btn', function (e) {
            e.preventDefault();
            var skip = $(this).data('skip');
            var take = {{ $count }};
            var row_each_column = {{ $column }};
            var sorting = "{{ $sorting }}";
            var dateshow = "{{ $dateshow }}";
            var enevtlocation = "{{ $enevtlocation }}";
            var button = "{{ $button }}";
            
            $.ajax({
                url: "{{ route('frontend.load-more-events') }}",
                method: "POST",
                data: {
                    skip: skip,
                    row_each_column: row_each_column,
                    take: take,
                    sorting: sorting,
                    dateshow: dateshow,
                    enevtlocation: enevtlocation,
                    button: button,
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        $('#dynamicLoadMoreData').append(response.html);
                        $('.load_more_event_btn').data('skip', skip + take);

                        if (!response.has_more) {
                            $('.load_more_event_btn').hide();
                        }
                    } else {
                        console.error('Failed to load more events.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        });
    </script>
@endpushonce