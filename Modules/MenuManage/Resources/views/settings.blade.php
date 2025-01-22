@extends('backEnd.master')
@section('title')
    @lang('menumanage::menuManage.role_based_sidebar_settings')
@endsection

@section('mainContent')
    <div class="role_permission_wrap">
        <div class="permission_title d-flex flex-wrap justify-content-between mb_10">
            <h4>{{ trans('menumanage::menuManage.role_based_sidebar_settings') }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb_20">
            <div class="white-box">
                {!! Form::open(['route'=>'menumanage.settings', 'method'=>'POST']) !!}
                @if(moduleStatusCheck('Saas'))
                    @foreach($schools as $school)
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 d-flex pt-10">
                                    <p class="text-uppercase fw-500 mb-10">{{ $school->school_name }} </p>
                                </div>
                                <div class="col-lg-9">

                                    <div class="radio-btn-flex ml-20">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="">
                                                    <input type="radio" name="role_based_sidebar[{{ $school->id }}]"
                                                           id="role_based_sidebar_{{ $school->id }}_enable" value="1"
                                                           class="common-radio relationButton"
                                                           @if($school->settings->role_based_sidebar) checked @endif>
                                                    <label for="role_based_sidebar_{{ $school->id }}_enable">{{ __('common.enable') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="">
                                                    <input type="radio" name="role_based_sidebar[{{ $school->id }}]"
                                                           id="role_based_sidebar_{{ $school->id }}_disable" value="0"
                                                           class="common-radio relationButton"
                                                           @if(!$school->settings->role_based_sidebar) checked @endif>
                                                    <label for="role_based_sidebar_{{ $school->id }}_disable">{{ __('common.disable') }}</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg small white_space" type="submit" >
                            <span class="ti-check"></span>
                            {{ __('common.save') }}
                        </button>

                        {{--<a class="primary-btn fix-gr-bg small white_space" href="{{ route('menumanage.all', ['type' => 'enable']) }}" style="overflow: unset;">
                            <span class="ti-check"></span>
                            {{ __('common.enable_all') }}
                        </a>

                        <a class="primary-btn fix-gr-bg small white_space" href="{{ route('menumanage.all', ['type' => 'enable']) }}" style="overflow: unset;">
                            <span class="ti-check"></span>
                            {{ __('common.disable_all') }}
                        </a>--}}


                    </div>
                @endif
                {!! Form::close() !!}
            </div>
        </div>

    </div>

@endsection


