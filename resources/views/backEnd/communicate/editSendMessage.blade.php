@extends('backEnd.master')
@section('title') 
    @lang('common.edit_notice')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.edit_notice')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.edit_notice')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('communicate.edit_notice') </h3>
                </div>
            </div>
            <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                <a href="{{route('notice-list')}}" class="primary-btn small fix-gr-bg">
                    @lang('communicate.notice_board')
                </a>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'route' => 'update-notice-data', 'method' => 'POST']) }}
        <div class="row">
            <div class="col-lg-12">
              <div class="white-box">
                <div class="">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <input type="hidden" name="notice_id"  value="{{ @$noticeDataDetails->id}}">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="primary_input mb-30">
                                <label class="primary_input_label" for="">@lang('common.title')<span class="text-danger"> *</span> </label>
                                <input class="primary_input_field form-control{{ $errors->has('notice_title') ? ' is-invalid' : '' }}"
                                type="text" name="notice_title" autocomplete="off" value="{{isset($noticeDataDetails)? $noticeDataDetails->notice_title : ''}}">
                                @if ($errors->has('notice_title'))
                                    <span class="text-danger" >
                                        {{ $errors->first('notice_title') }}
                                    </span>
                                @endif
                            </div>
                            <div class="primary_input mt-0">
                                <label class="primary_input_label" for="">@lang('communicate.notice') <span></span> </label>
                                <textarea class="primary_input_field form-control" cols="0" rows="5" name="notice_message" id="notice_message">{!! (isset($noticeDataDetails)) ? $noticeDataDetails->notice_message : '' !!}</textarea>
                            </div>
                                <div class="primary_input mt-40"> 
                                    <input type="checkbox" id="is_published" class="common-checkbox" value="1" @if(@$noticeDataDetails->is_published == 1) checked @endif name="is_published">
                                    <label for="is_published">@lang('communicate.is_published_web_site')</label> 
                                </div>
                            
                        </div>
                        <div class="col-lg-5">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="">
                                    @lang('communicate.notice_date')<span class="text-danger"> *</span> 
                                </label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('notice_date') ? ' is-invalid' : '' }}"
                                                    id="notice_date" type="text" autocomplete="off"
                                                    name="notice_date" value="{{(isset($noticeDataDetails)) ? date('m/d/Y', strtotime($noticeDataDetails->notice_date)) : ' ' }}">
                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#notice_date" type="button">
                                            <label class="m-0 p-0" for="notice_date">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('notice_date'))
                                    <span class="text-danger">
                                        {{ $errors->first('notice_date') }}
                                    </span>
                                @endif
                            </div>

                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="">
                                    @lang('communicate.publish_on')<span class="text-danger"> *</span> 
                                </label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('publish_on') ? ' is-invalid' : '' }}"
                                                    id="publish_on" type="text" autocomplete="off"
                                                    name="publish_on" value="{{(isset($noticeDataDetails)) ? date('m/d/Y', strtotime($noticeDataDetails->publish_on)) : ' ' }}">
                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#notice_date" type="button">
                                            <label class="m-0 p-0" for="publish_on">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('publish_on'))
                                    <span class="text-danger">
                                        {{ $errors->first('publish_on') }}
                                    </span>
                                @endif
                            </div>

                        <div class="col-lg-12 mt-50">
                            <label class="primary_input_label" for="">@lang('communicate.message_to') </label><br>
                                @if(isset($noticeDataDetails))
                                    @php 
                                        $inform_to = json_decode($noticeDataDetails->inform_to);
                                    @endphp
                                @endif                            
                                @foreach($roles as $role)
                                    <div class="">
                                        <input type="checkbox" id="role{{$role->id}}" class="common-checkbox" name="role[]" value="{{$role->id}}" {{in_array($role->id, $inform_to != null ? $inform_to : [])? 'checked' : ''}}>
                                        <label for="role{{ @$role->id }}"> {{ @$role->name }}</label>
                                    </div>
                                @endforeach
                                @if($errors->has('role'))
                                    <span class="text-danger" >
                                        {{ $errors->first('role') }}
                                    </span>
                                @endif
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('communicate.update_content')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>

@endsection
@include('backEnd.partials.date_picker_css_js')
@push('script')
<script>
    CKEDITOR.replace( 'notice_message' );
</script>
@endpush