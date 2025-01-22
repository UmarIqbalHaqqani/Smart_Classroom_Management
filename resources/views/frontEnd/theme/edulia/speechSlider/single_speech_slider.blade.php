@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
    {{ headerContent() }}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.speech_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.speech_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding teacher">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-md-4 col-sm-12 mb-3 mb-md-0">
                    <div class="teacher_details">
                        <div class="teacher_details_img">
                            <div class="teacher_details_img_wrapper">
                                <img src="{{ url(@$singleSpeechSlider->image) }}" alt="">
                            </div>
                            <div class="teacher_details_img_wrapper_inner">
                                <h4>{{ $singleSpeechSlider->name }}</h4>
                                <p>{{ $singleSpeechSlider->designation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 offset-xl-1 col-md-8 col-sm-12">
                    <div class="teacher_details">
                        {{-- <h4 class="mb-3">@lang('edulia.message_from') {{ $singleSpeechSlider->designation }}</h4> --}}
                        <h4 class="mb-3">
                            {{ $singleSpeechSlider->title }}
                        </h4>
                        <div class="teacher_details_content">
                            <p>{!! $singleSpeechSlider->speech !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ footerContent() }}
@endsection
