@foreach ($events as $event)
    <div class="col-lg-{{$column}}">
        <div class="events_item">
            @if (file_exists(asset($event->uplad_image_file)))
                <div class="events_item_img">
                    <img src="{{ asset($event->uplad_image_file) }}" alt="{{ $event->event_title }}">
                </div>
            @endif
            <div class="events_item_inner">
                <div class="events_item_inner_meta">
                    @if ($dateshow == 1)
                        <span>
                            <i class="fal fa-clock"></i>
                            {{ dateConvert($event->from_date) . ' ' . __('common.to') . ' ' . dateConvert($event->to_date) }}
                        </span>
                    @endif
                    @if ($enevtlocation == 1)
                        <span>
                            <i class="fal fa-map-marker-alt"></i>
                            {{ $event->event_location }}
                        </span>
                    @endif
                </div>
                @if ($event->event_title)
                    <a href="{{ route('frontend.event-details', $event->id) }}" class="events_item_inner_title">
                        {{ $event->event_title }}
                    </a>
                @endif
                <a href="{{ route('frontend.event-details', $event->id) }}">
                    <i class="fa fa-plus-circle"></i>{{ $button }}
                </a>
            </div>
        </div>
    </div>
@endforeach
