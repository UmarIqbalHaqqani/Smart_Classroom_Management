@php
    $variable = substr($video->youtube_link, 32, 11);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <iframe width="560" height="315"
                src="https://www.youtube.com/embed/{{ $variable }}"
                frameborder="0"
                allowfullscreen>
            </iframe>
        </div>
        <div class="col-lg-4">
            <div class="col-lg-12 mb-20">
                <p class="font-weight-bold">@lang('downloadCenter.class')</p>
                <p class="font-weight-light">
                    {{ $video->class->class_name }}</p>
            </div>
            <div class="col-lg-12 mb-20">
                <p class="font-weight-bold">@lang('downloadCenter.section')</p>
                <p class="font-weight-light">
                    {{ $video->section->section_name }}</p>
            </div>
            <div class="col-lg-12 mb-20">
                <p class="font-weight-bold">@lang('downloadCenter.title')</p>
                <p class="font-weight-light">{{ $video->title }}</p>
            </div>
            <div class="col-lg-12 mb-20">
                <p class="font-weight-bold">@lang('downloadCenter.description')</p>
                <p class="font-weight-light">{{ $video->description }}
                </p>
            </div>
            <div class="col-lg-12 mb-20">
                <p class="font-weight-bold">@lang('downloadCenter.created_by')</p>
                <p class="font-weight-light">
                    {{ $video->user->full_name }}</p>
            </div>
        </div>
    </div>
</div>
