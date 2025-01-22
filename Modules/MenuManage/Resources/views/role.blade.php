@extends('backEnd.master')
@section('title')
    @lang('menumanage::menuManage.assign_sidebar_by_role')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('menumanage::menuManage.assign_sidebar_by_role') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">{{ __('common.dashboard') }}</a>
                    <a href="#">{{ __('common.sidebar_manager') }}</a>
                    <a href="#">{{ __('menumanage::menuManage.assign_sidebar_by_role') }}</a>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="main-title">
                            <h3 class="mb-15 ">{{ __('common.select_criteria') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pt-0"></div>
                    <div id="row_element_div_role">
                        {!! Form::open(['route'=>'menumanage.index', 'method'=>'GET']) !!}
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="role_id">@lang('hr.role')</label>
                                    <select class="primary_select form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" id="sidebar_role_id">
                                        <option data-display="@lang('hr.role')" value="">@lang('common.select')</option>
                                        @foreach ($roles as $key => $value)
                                            <option value="{{ $value->id }}" {{ old('role_id') == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('role_id'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('role_id') }}
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="col-lg-3 text-center mt-4" >
                                <button type="submit" class="primary-btn fix-gr-bg" style="margin-top: 10px">
                                    <span class="ti-check"></span>
                                    {{ __('common.search') }}
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


