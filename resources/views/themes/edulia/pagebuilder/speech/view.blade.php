<div class="mt-md-5 px-4">
    @if (!empty(pagesetting('speech_heading')))
        <h1 class="text-center mb-3 mb-md-5 mt-5 speech-title">{{ pagesetting('speech_heading') }}</h1>
    @endif
    <div class="row align-items-center single-speech">
        <div class="col-md-12">
            @if (!empty(pagesetting('speech_user_image')))
                <div class="about_us_img">
                    <div style="width: 40%; height: 100%;" class="about_us_img_inner">
                        <img src="{{ pagesetting('speech_user_image')[0]['thumbnail'] }}"
                            alt="{{ pagesetting('speech_user_name') }}">
                    </div>
                </div>
            @endif
            <div class="speech_of mb-4">
                @if (!empty(pagesetting('speech_heading')))
                    <h3 class="text-center">{{ pagesetting('speech_user_name') }}</h3>
                @endif
                @if (!empty(pagesetting('speech_user_designation')))
                    <p class="text-center">{{ pagesetting('speech_user_designation') }}</p>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="speech_inner">
                @if (!empty(pagesetting('speech_description')))
                    <p class="mb-4">{!! pagesetting('speech_description') !!}</p>
                @endif
            </div>
        </div>
    </div>
</div>
