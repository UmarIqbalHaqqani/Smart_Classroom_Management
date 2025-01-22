<div class="events_schedule">
    <table>
        @if ($events->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
            <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                    href="{{ URL::to('/event') }}">@lang('edulia.add_event')</a></p>
        @else
            @foreach ($events as $event)
                <tr>
                    <td>
                        <div class="events_schedule_date">
                            <h3>{{ date('d', strtotime($event->from_date)) }}</h3>
                            <p>{{ date('M', strtotime($event->from_date)) }}</p>
                        </div>
                    </td>
                    <td><a class="event_title" href="{{route('frontend.event-details', $event->id)}}">
                            <h4>{{ $event->event_title }}</h4>
                        </a></td>
                    <td>
                        <p>
                            <i class="far fa-clock"></i>
                            {{ date('d/m/y', strtotime($event->from_date)) }}-{{ date('d/m/y', strtotime($event->to_date)) }}
                        </p>
                    </td>
                    <td>
                        <p><i class="far fa-map-marker-alt"></i>{{ $event->event_location }}</p>
                    </td>
                    <td><a target="_blank" href="{{route('frontend.event-details', $event->id)}}"><i class="far fa-long-arrow-right"></i></a></td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
