@extends('backEnd.master')
@section('title')
    @lang('communicate.calendar')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('communicate.calendar')</h1>
                <div class="bc-pages">
                    <input type="hidden" id="system_url" value="{{ url('/') }}">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('communicate.communicate')</a>
                    <a href="#">@lang('communicate.calendar')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-40 sms-accordion">
        <div class="container-fluid p-0">
            <div>
                @include('backEnd.communicate.commonAcademicCalendar')
            </div>
        </div>
    </section>
@endsection
@include('backEnd.communicate.academic_calendar_css_js')
