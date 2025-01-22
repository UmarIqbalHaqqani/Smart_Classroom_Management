@extends(config('pagebuilder.site_layout'), ['edit' => false])

@section(config('pagebuilder.site_section'))
{{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>
                            {{ __('edulia.event_details') }} 
                            <span>
                                <a href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.event_details') }}
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding events">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="events_details">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="events_details_sidebar">
                                    <div class="events_details_sidebar_info">
                                        <h6>{{__('edulia.start_date')}}</h6>
                                        <p>{{dateConvert($event->from_date)}}</p>
                                    </div>
                                    <div class="events_details_sidebar_info">
                                        <h6>{{__('edulia.end_date')}}</h6>
                                        <p>{{dateConvert($event->to_date)}}</p>
                                    </div>
                                    <div class="events_details_sidebar_info">
                                        <h6>{{__('edulia.location')}}</h6>
                                        <p>{{$event->event_location}}</p>
                                    </div>
                                    <div class="events_details_sidebar_info">
                                        <h6>{{__('edulia.organizer')}}</h6>
                                        <p>{{$event->user->full_name}}</p>
                                    </div>
                                    <div class="events_details_sidebar_info">
                                        <h6>{{__('edulia.email_address')}}</h6>
                                        <p>{{$event->user->email}}</p>
                                    </div>
                                    @if ($event->user->phone_number)
                                        <div class="events_details_sidebar_info">
                                            <h6>{{__('edulia.phone_number')}}</h6>
                                            <p>{{$event->user->phone_number}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="events_details_content">
                                    @if (file_exists($event->uplad_image_file))
                                        <div class="events_details_content_img">
                                            <img src="{{asset($event->uplad_image_file)}}" alt="{{$event->event_title}}">
                                        </div>
                                    @endif
                                    <h3>{{$event->event_title}}</h3>
                                    <p>
                                        {!! $event->event_des !!}
                                    </p>
                                    @if ($event->url)
                                        <a href="{{$event->url}}" target="__blank"></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{footerContent()}}
@endsection
