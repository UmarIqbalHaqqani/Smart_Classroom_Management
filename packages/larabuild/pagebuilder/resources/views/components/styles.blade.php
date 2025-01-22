<div class="pb-advanced">
    <div class="accordion pb-advanceaccordion" id="pb-advanceaccordion">
        <div class="op-accordion-item">
            <div class="pb-advancedtitle" data-bs-toggle="collapse" data-bs-target="#content-width-accord"
                aria-expanded="false">
                <h2>{{__('pagebuilder::pagebuilder.content_width')}}</h2>
                <span>{{__('pagebuilder::pagebuilder.content_width_description')}}</span>
            </div>
            <div id="content-width-accord" class="accordion-collapse collapse" data-bs-parent="#pb-advanceaccordion">

                <div class="pb-advancedtitle">
                    <div class="pb-layout-input  pb-select">
                        <select data-minimum-results-for-search="Infinity" id="content_width" name="content_width"
                            class="form-select op-input-field op-selectoption"
                            aria-label="{{__('pagebuilder::pagebuilder.select') }}">
                            <option value=''>{{__('pagebuilder::pagebuilder.select') }}</option>
                            <option value="boxed" {{ ($styles['content_width']??'')=='boxed' ?'selected':'' }}>
                                {{__('pagebuilder::pagebuilder.boxed') }}</option>
                            <option value="full_width" {{ ($styles['content_width']??'')=='full_width' ?'selected':''}}>
                                {{__('pagebuilder::pagebuilder.full_width') }}</option>
                        </select>
                    </div>
                </div>

                <div
                    class="pb-rangslider boxed_slider {{ empty($styles['content_width']) || $styles['content_width'] == 'full_width' ? 'd-none':'' }}">
                    <div class="slider-styled" id="boxed_slider"></div>
                    <div class="pb-layout-input">
                        <input type="number" value="{{ ($styles['boxed_slider_input']??'') }}" class="form-control"
                            name="boxed_slider_input" id="boxed_slider_input" placeholder="">
                    </div>
                </div>
            </div>
        </div>
        <div class="op-accordion-item">
            <div class="pb-advancedtitle" data-bs-toggle="collapse" data-bs-target="#widths-accord"
                aria-expanded="false">
                <h2>{{__('pagebuilder::pagebuilder.section_width_height')}}</h2>
                <span>{{__('pagebuilder::pagebuilder.section_width_height_description')}}</span>
            </div>
            <div id="widths-accord" class="accordion-collapse collapse" data-bs-parent="#pb-advanceaccordion">

                <div class="pb-radio-wrap pb-layoutinfo pb-background-nav">
                    <div class="pb-radiobtn">
                        <input type="radio" id="wh-px" {{($styles['width-height-type'] ?? '' )=='px'
                            ?'checked':(empty($styles['width-height-type'])?'checked':'')}} name="width-height-type"
                            value="px">
                        <label for="wh-px">{{__('pagebuilder::pagebuilder.px')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="wh-em" {{($styles['width-height-type'] ?? '' )=='em' ?'checked':''}}
                            name="width-height-type" value="em">
                        <label for="wh-em">{{__('pagebuilder::pagebuilder.em')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="wh-%" {{($styles['width-height-type'] ?? '' )=='%' ?'checked':''}}
                            name="width-height-type" value="%">
                        <label for="wh-%">{{__('pagebuilder::pagebuilder.%')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="wh-rem" {{($styles['width-height-type'] ?? '' )=='rem' ?'checked':''}}
                            name="width-height-type" value="rem">
                        <label for="wh-rem">{{__('pagebuilder::pagebuilder.rem')}}</label>
                    </div>
                </div>

                <ul class="pb-themeform__wrap pb-section-wh">
                    <li class="pb-form-group-wrap">
                        <div class="pb-form-group-half">
                            <input type="number" name="width" value="{{$styles['width']??''}}" class="form-control"
                                placeholder="{{__('pagebuilder::pagebuilder.width')}}">
                        </div>
                        <div class="pb-form-group-half">
                            <input type="number" name="height" value="{{$styles['height']??''}}" class="form-control"
                                placeholder="{{__('pagebuilder::pagebuilder.height')}}">
                        </div>
                    </li>
                    <li class="pb-form-group-wrap">
                        <div class="pb-form-group-half">
                            <input type="number" name="min-width" value="{{$styles['min-width']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.min_width')}}">
                        </div>
                        <div class="pb-form-group-half">
                            <input type="number" name="max-width" value="{{$styles['max-width']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.max_width')}}">
                        </div>
                    </li>
                    <li class="pb-form-group-wrap">
                        <div class="pb-form-group-half">
                            <input type="number" name="min-height" value="{{$styles['min-height']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.min_height')}}">
                        </div>
                        <div class="pb-form-group-half">
                            <input type="number" name="max-height" value="{{$styles['max-height']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.max_height')}}">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="op-accordion-item">
            <div class="pb-advancedtitle" data-bs-toggle="collapse" data-bs-target="#margins-accord"
                aria-expanded="false">
                <h2>{{__('pagebuilder::pagebuilder.section_margins')}}</h2>
                <span>{{__('pagebuilder::pagebuilder.section_margins_description')}}</span>
            </div>
            <div id="margins-accord" class="accordion-collapse collapse" data-bs-parent="#pb-advanceaccordion">

                <div class="pb-radio-wrap pb-layoutinfo pb-background-nav">
                    <div class="pb-radiobtn">
                        <input type="radio" id="m-px" {{($styles['margin-type'] ?? '' )=='px'
                            ?'checked':(empty($styles['margin-type'])?'checked':'')}} name="margin-type" value="px">
                        <label for="m-px">{{__('pagebuilder::pagebuilder.px')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="m-em" {{($styles['margin-type'] ?? '' )=='em' ?'checked':''}}
                            name="margin-type" value="em">
                        <label for="m-em">{{__('pagebuilder::pagebuilder.em')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="m-%" {{($styles['margin-type'] ?? '' )=='%' ?'checked':''}}
                            name="margin-type" value="%">
                        <label for="m-%">{{__('pagebuilder::pagebuilder.%')}}</label>
                    </div>
                    <div class="pb-radiobtn">
                        <input type="radio" id="m-rem" {{($styles['margin-type'] ?? '' )=='rem' ?'checked':''}}
                            name="margin-type" value="rem">
                        <label for="m-rem">{{__('pagebuilder::pagebuilder.rem')}}</label>
                    </div>
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active">
                        <div class="lb-spaceing">
                            <span class="pb-addvalue"><i class="icon-plus"></i>
                                <div class=" pb-value pb-top-value">
                                    <input type="number" name="margin-top" class="form-control"
                                        value="{{$styles['margin-top']??''}}"
                                        placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                                </div>
                                <div class="pb-value pb-right-value">
                                    <input type="number" name="margin-right" class="form-control"
                                        value="{{$styles['margin-right']??''}}"
                                        placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                                </div>
                                <div class="pb-value pb-bottom-value">
                                    <input type="number" name="margin-bottom" class="form-control"
                                        value="{{$styles['margin-bottom']??''}}"
                                        placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                                </div>
                                <div class="pb-value pb-left-value">
                                    <input type="number" name="margin-left" class="form-control"
                                        value="{{$styles['margin-left']??''}}"
                                        placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="op-accordion-item">
            <div class="pb-advancedtitle" data-bs-toggle="collapse" data-bs-target="#paddings-accord"
                aria-expanded="false">
                <h2>{{__('pagebuilder::pagebuilder.section_paddings')}}</h2>
                <span>{{__('pagebuilder::pagebuilder.section_paddings_description')}}</span>
            </div>
            <div id="paddings-accord" class="accordion-collapse collapse" data-bs-parent="#pb-advanceaccordion">

                <nav class="pb-background-nav">
                    <div class="pb-radio-wrap pb-layoutinfo ">
                        <div class="pb-radiobtn">
                            <input type="radio" id="p-px" {{($styles['padding-type'] ?? '' )=='px'
                                ?'checked':(empty($styles['padding-type'])?'checked':'')}} name="padding-type"
                                value="px">
                            <label for="p-px">{{__('pagebuilder::pagebuilder.px')}}</label>
                        </div>
                        <div class="pb-radiobtn">
                            <input type="radio" id="p-em" {{($styles['padding-type'] ?? '' )=='em' ?'checked':''}}
                                name="padding-type" value="em">
                            <label for="p-em">{{__('pagebuilder::pagebuilder.em')}}</label>
                        </div>
                        <div class="pb-radiobtn">
                            <input type="radio" id="p-%" {{($styles['padding-type'] ?? '' )=='%' ?'checked':''}}
                                name="padding-type" value="%">
                            <label for="p-%">{{__('pagebuilder::pagebuilder.%')}}</label>
                        </div>
                        <div class="pb-radiobtn">
                            <input type="radio" id="p-rem" {{($styles['padding-type'] ?? '' )=='rem' ?'checked':''}}
                                name="padding-type" value="rem">
                            <label for="p-rem">{{__('pagebuilder::pagebuilder.rem')}}</label>
                        </div>
                    </div>
                </nav>

                <div class="lb-spaceing">
                    <span class="pb-addvalue"><i class="icon-plus"></i>
                        <div class=" pb-value pb-top-value">
                            <input type="number" name="padding-top" value="{{$styles['padding-top']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                        </div>
                        <div class="pb-value pb-right-value">
                            <input type="number" name="padding-right" value="{{$styles['padding-right']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                        </div>
                        <div class="pb-value pb-bottom-value">
                            <input type="number" name="padding-bottom" value="{{$styles['padding-bottom']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                        </div>
                        <div class="pb-value pb-left-value">
                            <input type="number" name="padding-left" value="{{$styles['padding-left']??''}}"
                                class="form-control" placeholder="{{__('pagebuilder::pagebuilder.value')}}" />
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div class="op-accordion-item">
            <div class="pb-advancedtitle" data-bs-toggle="collapse" data-bs-target="#others-accord"
                aria-expanded="false">
                <h2>{{ __('pagebuilder::pagebuilder.advanced_settings') }}</h2>
                <span>{{ __('pagebuilder::pagebuilder.advanced_settings_description') }}</span>
            </div>
            <div id="others-accord" class="op-others-accord accordion-collapse collapse"
                data-bs-parent="#pb-advanceaccordion">

                <div class="pb-advancedtitle">
                    <h2>{{__('pagebuilder::pagebuilder.z-index')}}</h2>
                    <span>{{__('pagebuilder::pagebuilder.z-index_description')}}</span>
                    <div class="pb-layout-input">
                        <input type="number" value="{{$styles['z-index']??''}}" class="form-control" name="z-index"
                            placeholder="{{__('pagebuilder::pagebuilder.z-index_placeholder')}}">
                    </div>
                </div>
                <div class="pb-advancedtitle">
                    <h2>{{__('pagebuilder::pagebuilder.css_classes')}}</h2>
                    <span>{{__('pagebuilder::pagebuilder.css_classes_description')}}</span>
                    <div class="pb-layout-input">
                        <input type="text" value="{{$styles['classes']??''}}" name="classes" class="form-control"
                            placeholder="{{__('pagebuilder::pagebuilder.css_classes_placeholder')}}">
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{__('pagebuilder::pagebuilder.custom_attributes')}}</h2>
                        <span>{{__('pagebuilder::pagebuilder.custom_attributes_description')}}</span>
                        <div class="pb-layout-input">
                            <input type="text" value="{{$styles['custom_attributes']??''}}" name="custom_attributes" class="form-control"
                                placeholder="{{__('pagebuilder::pagebuilder.custom_attributes_placeholder')}}">
                        </div>
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{__('pagebuilder::pagebuilder.background_image')}}</h2>
                        <span>{{__('pagebuilder::pagebuilder.background_image_description')}}</span>
                        <div class="pb-layout-input">
                            <div class="op-textcontent">
                                <ul class="op-upload-img" id="background_image">
                                    <li class="op-upload-img-info">
                                        <div class="op-uploads-img-data">
                                            <label> <em><i class="icon-plus"></i></em>
                                                <input type="file" data-id="image" data-max_size="3" data-ext="jpg,png"
                                                    accept=".jpg,.png" data-multi_items="false">
                                            </label>
                                        </div>
                                    </li>
                                    <li class="op-upload-img-info op-img-thumbnail d-none">
                                        <div class="op-upload-data">
                                            <figure>
                                                <img src="#">
                                            </figure>
                                            <div class="op-overlay-icon op-remove-file"><i class="icon-trash-2"></i></div>
                                            <input type="hidden">
                                        </div>
                                    </li>
                                    @if( !empty($styles['image'][0]) )
                                    @php
                                    $bg = json_decode($styles['image'][0],true);
                                    @endphp
                                    <li class="op-upload-img-info op-img-thumbnail">
                                        <div class="op-upload-data">
                                            <figure>
                                                @php
                                                $path = 'storage/'.$bg['path'];
                                                if($bg['type'] == 'file'){
                                                $path = 'public/vendor/optionbuilder/images/file-preview.png';
                                                }
                                                @endphp
                                                <img src="{{ asset( $path ) }}">
                                            </figure>
                                            <div class="op-overlay-icon op-remove-file"><i class="icon-trash-2"></i></div>
                                            <input type="hidden" name="image[]" value="{{json_encode($bg)}}" />
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                                <span
                                    class="pb-bgimg-info">{{__('pagebuilder::pagebuilder.background_image_notice')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{__('pagebuilder::pagebuilder.background_image_size')}}</h2>
                        <span>{{__('pagebuilder::pagebuilder.background_image_size_description')}}</span>
                        <div class="pb-layout-input  pb-select">
                            <select data-minimum-results-for-search="Infinity" name="background-size"
                                class="form-select op-input-field op-selectoption"
                                aria-label="{{__('pagebuilder::pagebuilder.select') }}">
                                <option value='' selected>{{__('pagebuilder::pagebuilder.select') }}</option>
                                <option value="default" {{ $styles['background-size']??''=='default' ?'selected':'' }}>{{
                                    __('pagebuilder::pagebuilder.default') }}</option>
                                <option value="auto" {{ $styles['background-size']??''=='auto' ?'selected':'' }}>{{
                                    __('pagebuilder::pagebuilder.auto') }}</option>
                                <option value="cover" {{ $styles['background-size']??''=='cover' ?'selected':'' }}>{{
                                    __('pagebuilder::pagebuilder.cover') }}</option>
                                <option value="contain" {{ $styles['background-size']??''=='contain' ?'selected':'' }}>{{
                                    __('pagebuilder::pagebuilder.contain') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{__('pagebuilder::pagebuilder.background_image_position')}}</h2>
                        <span>{{__('pagebuilder::pagebuilder.background_image_position_description')}}</span>
                        <div class="pb-layout-input pb-select">
                            <select name="background-position" data-minimum-results-for-search="Infinity"
                                class="form-select op-input-field form-control op-selectoption"
                                aria-label="{{__('pagebuilder::pagebuilder.select') }}">
                                <option value=''>{{ __('pagebuilder::pagebuilder.select') }}</option>
                                <option value='left top' {{ $styles['background-position']??''=='left top' ?'selected':''
                                    }}>{{
                                    __('pagebuilder::pagebuilder.left_top') }}
                                </option>
                                <option value='left center' {{ $styles['background-position']??''=='left center'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.left_center') }}
                                </option>
                                <option value='left bottom' {{ $styles['background-position']??''=='left bottom'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.left_bottom') }}
                                </option>
                                <option value='right top' {{ $styles['background-position']??''=='right top' ?'selected':''
                                    }}>
                                    {{ __('pagebuilder::pagebuilder.right_top') }}
                                </option>
                                <option value='right center' {{ $styles['background-position']??''=='right center'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.right_center') }}
                                </option>
                                <option value='right bottom' {{ $styles['background-position']??''=='right bottom'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.right_bottom') }}
                                </option>
                                <option value='center top' {{ $styles['background-position']??''=='center top'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.center_top') }}
                                </option>
                                <option value='center center' {{ $styles['background-position']??''=='center center'
                                    ?'selected':'' }}>
                                    {{ __('pagebuilder::pagebuilder.center_center') }}
                                </option>
                                <option value='center bottom' {{ $styles['background-position']??''=='center bottom'
                                    ?'selected':'' }}>
                                    {{__('pagebuilder::pagebuilder.center_bottom')}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{ __('pagebuilder::pagebuilder.background_color') }}</h2>
                        <span>{{ __('pagebuilder::pagebuilder.background_color_description') }}</span>
                        <div class="op-colorpicker">
                            <div class="op-inputbtn-wrapper colorPicker pb-colorpicker">
                                <input type="text" data-id="colorpicker"
                                    data-value="{{ $styles['background-color'] ?? '' }}" name="background-color"
                                    value="{{ $styles['background-color'] ?? '' }}"
                                    class="op-input-field form-control getcolor">
                                <span class="pb-inputbtn"><span class="colorPicker--preview"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="pb-advancedtitle">
                        <h2>{{ __('pagebuilder::pagebuilder.background_overlay_color') }}</h2>
                        <span>{{ __('pagebuilder::pagebuilder.background_overlay_color_description') }}</span>
                        <div class="op-colorpicker">
                            <div class="op-inputbtn-wrapper colorPicker pb-colorpicker">
                                <input type="text" data-id="colorpicker"
                                    data-value="{{ $styles['background-overlay-color'] ?? '' }}"
                                    name="background-overlay-color" value="{{ $styles['background-overlay-color'] ?? '' }}"
                                    class="op-input-field form-control getcolor">
                                <span class="pb-inputbtn"><span class="colorPicker--preview"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-advancedtitle">
                    <h2>{{__('pagebuilder::pagebuilder.background_image_size')}}</h2>
                    <span>{{__('pagebuilder::pagebuilder.background_image_size_description')}}</span>
                    <div class="pb-layout-input  pb-select">
                        <select data-minimum-results-for-search="Infinity" name="background-size"
                            class="form-select op-input-field op-selectoption"
                            aria-label="{{__('pagebuilder::pagebuilder.select') }}">
                            <option value='' selected>{{__('pagebuilder::pagebuilder.select') }}</option>
                            <option value="default" {{ $styles['background-size']??''=='default' ?'selected':'' }}>{{
                                __('pagebuilder::pagebuilder.default') }}</option>
                            <option value="auto" {{ $styles['background-size']??''=='auto' ?'selected':'' }}>{{
                                __('pagebuilder::pagebuilder.auto') }}</option>
                            <option value="cover" {{ $styles['background-size']??''=='cover' ?'selected':'' }}>{{
                                __('pagebuilder::pagebuilder.cover') }}</option>
                            <option value="contain" {{ $styles['background-size']??''=='contain' ?'selected':'' }}>{{
                                __('pagebuilder::pagebuilder.contain') }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="pb-advancedtitle">
                    <h2>{{__('pagebuilder::pagebuilder.background_image_position')}}</h2>
                    <span>{{__('pagebuilder::pagebuilder.background_image_position_description')}}</span>
                    <div class="pb-layout-input pb-select">
                        <select name="background-position" data-minimum-results-for-search="Infinity"
                            class="form-select op-input-field form-control op-selectoption"
                            aria-label="{{__('pagebuilder::pagebuilder.select') }}">
                            <option value=''>{{ __('pagebuilder::pagebuilder.select') }}</option>
                            <option value='left top' {{ $styles['background-position']??''=='left top' ?'selected':''
                                }}>{{
                                __('pagebuilder::pagebuilder.left_top') }}
                            </option>
                            <option value='left center' {{ $styles['background-position']??''=='left center'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.left_center') }}
                            </option>
                            <option value='left bottom' {{ $styles['background-position']??''=='left bottom'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.left_bottom') }}
                            </option>
                            <option value='right top' {{ $styles['background-position']??''=='right top' ?'selected':''
                                }}>
                                {{ __('pagebuilder::pagebuilder.right_top') }}
                            </option>
                            <option value='right center' {{ $styles['background-position']??''=='right center'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.right_center') }}
                            </option>
                            <option value='right bottom' {{ $styles['background-position']??''=='right bottom'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.right_bottom') }}
                            </option>
                            <option value='center top' {{ $styles['background-position']??''=='center top'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.center_top') }}
                            </option>
                            <option value='center center' {{ $styles['background-position']??''=='center center'
                                ?'selected':'' }}>
                                {{ __('pagebuilder::pagebuilder.center_center') }}
                            </option>
                            <option value='center bottom' {{ $styles['background-position']??''=='center bottom'
                                ?'selected':'' }}>
                                {{__('pagebuilder::pagebuilder.center_bottom')}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="pb-advancedtitle">
                    <h2>{{ __('pagebuilder::pagebuilder.background_color') }}</h2>
                    <span>{{ __('pagebuilder::pagebuilder.background_color_description') }}</span>
                    <div class="op-colorpicker">
                        <div class="op-inputbtn-wrapper colorPicker pb-colorpicker">
                            <input type="text" data-id="colorpicker"
                                data-value="{{ $styles['background-color'] ?? '' }}" name="background-color"
                                value="{{ $styles['background-color'] ?? '' }}"
                                class="op-input-field form-control getcolor">
                            <span class="pb-inputbtn"><span class="colorPicker--preview"></span></span>
                        </div>
                    </div>
                </div>


                <div class="pb-advancedtitle">
                    <h2>{{ __('pagebuilder::pagebuilder.background_overlay_color') }}</h2>
                    <span>{{ __('pagebuilder::pagebuilder.background_overlay_color_description') }}</span>
                    <div class="op-colorpicker">
                        <div class="op-inputbtn-wrapper colorPicker pb-colorpicker">
                            <input type="text" data-id="colorpicker"
                                data-value="{{ $styles['background-overlay-color'] ?? '' }}"
                                name="background-overlay-color" value="{{ $styles['background-overlay-color'] ?? '' }}"
                                class="op-input-field form-control getcolor">
                            <span class="pb-inputbtn"><span class="colorPicker--preview"></span></span>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    var boxed_slider = document.getElementById('boxed_slider');

    noUiSlider.create(boxed_slider, {
        start: [{{ (empty($styles['boxed_slider_input'])?'1320':$styles['boxed_slider_input']) }}],
        range: {
            'min': [0],
            'max': [1320]
        }
    });

    boxed_slider.noUiSlider.on('update', function (value, handle) {
        $('#boxed_slider_input').val(Math.round(value));
        if($('#content_width').val() == 'boxed'){
            setTimeout(() => {
                manageContentWidth('boxed', Math.round(value));
                setPageSettings();
            }, 300);
        }
	});

    $(document).on('change', '#content_width', function(event){
       let content_width = $(this).val();
       if(content_width == 'boxed'){
        $('.boxed_slider').removeClass('d-none');
       }
       else{
        $('.boxed_slider').addClass('d-none');
       }
    });

</script>