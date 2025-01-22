<div class="owl-carousel">
    @if ($speechSliders->isNotEmpty())
        @foreach ($speechSliders as $speechSlider)
            <div class="single_speech_item">
                <div class="speaker_photo">
                    <img src="{{ asset(@$speechSlider->image) }}" alt="photo">
                </div>
                <div class="speaker_info">{{ @$speechSlider->designation }}@lang('edulia.\'s_speech')</div>

                <div class="speech">
                    {{ @$speechSlider->title }}
                </div>

                <a href="{{ route('frontend.speech-slider', $speechSlider->id) }}" class="speech_details">@lang('edulia.details')</a>
            </div>
        @endforeach
    @endif
</div>
