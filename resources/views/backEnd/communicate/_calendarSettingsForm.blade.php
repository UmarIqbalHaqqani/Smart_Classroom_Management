<div class="showAndHideSettings" style="display:none">
    <div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-5">
                <div class="main-title">
                    <h3 class="mb-15">@lang('communicate.calendar_settings')</h3>
                </div>
            </div>
        </div>
        <div class="row mb-40">
              <div class="col-lg-12">
                <div>
                    {{ Form::open(['route' => 'store-academic-calendar-settings', 'method' => 'POST']) }}
                    <div class="row">
                        @foreach($settings as $setting)
                            <div class="col-lg-6">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <p class="text-uppercase fw-500 mb-10" style="width: 130px;">@lang('communicate.'.$setting->menu_name)</p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-30">
                                                    <input type="radio" name="setting[{{$setting->menu_name}}][status]" id="settingsY{{$setting->id}}" value="1" class="common-radio" {{$setting->status == 1 ? 'checked' : ''}}>
                                                    <label for="settingsY{{$setting->id}}">@lang('common.yes')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input type="radio" name="setting[{{$setting->menu_name}}][status]" id="settingsN{{$setting->id}}" value="0" class="common-radio" {{$setting->status == 0 ? 'checked' : ''}}>
                                                    <label for="settingsN{{$setting->id}}">@lang('common.no')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input">
                                                <label class="primary_input_label">@lang('communicate.font_color')<span class="text-danger"> *</span></label>
                                                <input type="color" name="setting[{{$setting->menu_name}}][font_color]" class="primary_input_field color-input color_field" required value="{{$setting->font_color}}">
                                                @error('font_color')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input">
                                                <label class="primary_input_label">@lang('communicate.background_color')<span class="text-danger"> *</span></label>
                                                <input type="color" name="setting[{{$setting->menu_name}}][bg_color]" class="primary_input_field color-input color_field" required value="{{$setting->bg_color}}">
                                                @error('bg_color')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(userPermission('store-academic-calendar-settings'))
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit">
                                    <span class="ti-check"></span>@lang('common.update')
                                </button>
                            </div>
                        </div>
                    @endif
                    {{ Form::close() }}
              </div>
          </div>
        </div>
    </div>
</div>