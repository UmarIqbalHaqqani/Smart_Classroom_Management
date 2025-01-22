<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.video-list-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" id="url" value="{{ url('/') }}">
            <div class="row">
                <div class="col-lg-6 mb-20" id="common_select_class_div">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="">{{ __('common.class') }}
                            <span class="text-danger"> *</span>
                        </label>
                        <select class="primary_select form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                            name="class_id"
                            id="common_select_class_video">
                            <option data-display="@lang('common.select_class') *"
                                value="">
                                {{ __('common.select_class') }} *</option>
                            @if (isset($classes))
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ isset($video) ? ($video->class_id == $class->id ? 'selected' : '') : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="pull-right loader loader_style" id="common_select_class_loader">
                            <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                alt="loader">
                        </div>
                        <span class="text-danger">{{ $errors->first('class_id') }}</span>
                    </div>
                </div>
                <div class="col-lg-6 mb-20" id="common_select_section_div">
                    <label class="primary_input_label" for="">{{ __('common.section') }}
                        <span class="text-danger"> *</span>
                    </label>
                    <select
                        class="primary_select form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }} select_section"
                        id="common_select_section_video" name="section_id">
                        <option data-display="@lang('common.select_section') *"
                            value="">
                            @lang('common.select_section') *</option>
                        @isset($video)
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ isset($video) ? ($video->section_id == $section->id ? 'selected' : '') : '' }}>
                                    {{ $section->section_name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <div class="pull-right loader loader_style" id="common_select_section_loader"
                        style="margin-top: -30px;padding-right: 21px;">
                        <img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt=""
                            style="width: 28px;height:28px;">
                    </div>
                    @if ($errors->has('section_id'))
                        <span class="text-danger">
                            {{ $errors->first('section_id') }}
                        </span>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="primary_input">
                        <label class="primary_input_label"
                            for="">@lang('downloadCenter.title')<span
                                class="text-danger">
                                *</span></label>
                        <input
                            class="primary_input_field read-only-input form-control form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                            type="text"
                            name="title" id="title"
                            value="{{ @$video ? $video->title : '' }}">
                        <span class="text-danger"
                            id="titleError">
                        </span>
                    </div>
                    @if ($errors->has('title'))
                        <span class="text-danger">
                            {{ $errors->first('title') }}
                        </span>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="primary_input">
                        <label class="primary_input_label"
                            for="">@lang('downloadCenter.video_link')<span
                                class="text-danger">
                                *</span></label>
                        <input
                            class="primary_input_field read-only-input form-control form-control{{ $errors->has('video_link') ? ' is-invalid' : '' }}"
                            type="text"
                            name="video_link" id="video_link"
                            value="{{ @$video ? $video->youtube_link : '' }}">
                        <span class="text-danger"
                            id="videoLinkError">
                        </span>
                    </div>
                    @if ($errors->has('video_link'))
                        <span class="text-danger">
                            {{ $errors->first('video_link') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-25">
            <div class="row">
                <div class="col-lg-12">
                    <div class="primary_input">
                        <label class="primary_input_label"
                            for="">@lang('downloadCenter.description')<span></span>
                        </label>
                        <textarea class="primary_input_field form-control" cols="0" rows="3"
                            name="description" id="description">{{ @$video ? $video->description : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="video_id"
            value="{{ @$video->id }}">
        <div class="col-lg-12 text-center mt-25">
            <div class="d-flex justify-content-between">
                <button type="button"
                    class="primary-btn tr-bg"
                    data-dismiss="modal">@lang('downloadCenter.cancel')</button>
                <button class="primary-btn fix-gr-bg submit"
                    id="save_button_query"
                    type="submit">@lang('downloadCenter.save')</button>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<script>
    $(document).ready(function() {
        $('.primary_select').niceSelect();
        $(document).on("change", "#common_select_class_video", function() {
            var url = $("#url").val();
            var i = 0;
            var formData = {
                id: $(this).val(),
            };
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",
                beforeSend: function() {
                    $('#common_select_section_loader').addClass('pre_loader').removeClass(
                        'loader');
                },
                success: function(data) {
                    $("#common_select_section_video").empty().append(
                        $("<option>", {
                            value: '',
                            text: window.jsLang('select_section') +
                                '*',
                        })
                    );
                    if (data[0].length) {
                        $.each(data[0], function(i, section) {
                            console.log(section);
                            $("#common_select_section_video").append(
                                $("<option>", {
                                    value: section.id,
                                    text: section.section_name,
                                })
                            );
                        });
                    }
                    $('#common_select_section_video').niceSelect('update');
                    $('#common_select_section_video').trigger('change');
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#common_select_section_loader').removeClass('pre_loader')
                            .addClass('loader');
                    }
                }
            });
        });
    });
</script>
