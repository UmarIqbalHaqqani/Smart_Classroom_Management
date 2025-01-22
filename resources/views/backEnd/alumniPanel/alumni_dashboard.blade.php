@extends('backEnd.master')
@section('title')
    @lang('alumni::al.alumni_dashboard')
@endsection

@push('css')
    <style>
        table#table_id thead tr th:not(:first-child) {
            padding-left: 30px !important;
        }

        table#table_id tbody tr td:not(:first-child),
        table#table_id tbody tr td:nth-child(2) {
            padding-left: 30px !important;
        }

        .leave_table {
            overflow: hidden;
        }

        .table tbody tr:last-child td,
        .table tbody tr:last-child th {
            border-bottom: none;
        }

        .QA_section .QA_table .table.school-table-style-parent-fees thead th,
        .QA_section .QA_table .table.school-table-style-parent-fees thead td {
            padding: 16px 30px !important;
        }

        .fc th {
            padding: 0 !important;
        }
    </style>
@endpush

@section('mainContent')
    @php
        @$setting = generalSetting();
        if (!empty(@$setting->currency_symbol)) {
            @$currency = @$setting->currency_symbol;
        } else {
            @$currency = '$';
        }
    @endphp

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('student.welcome_to') <strong> {{ @$student_detail->full_name }}</strong> </h3>
                    </div>
                </div>
                <div class="row row-gap-30">

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_noticeboard') }}" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.notice')</h3>
                                        <p class="mb-0">@lang('student.total_notice')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($totalNotices))
                                            {{ count(@$totalNotices) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_noticeboard') }}" class="d-block">
                            <div class="white-box single-summery fuchsia">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('edulia.event')</h3>
                                        <p class="mb-0">@lang('alumni::al.upcoming_events')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($smEvents))
                                            {{ count(@$smEvents) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_book_issue') }}" class="d-block">
                            <div class="white-box single-summery cyan">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('student.issued_book')</h3>
                                        <p class="mb-0">@lang('student.total_issued_book')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($issueBooks))
                                            {{ count(@$issueBooks) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('student_book_issue') }}" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('common.weekend')</h3>
                                        <p class="mb-0">
                                            @lang('alumni::al.weekends_on')
                                            @if(isset($sm_weekends))
                                                @foreach ($sm_weekends as $key => $weekend)
                                                    <strong>{{ @$weekend->name }}@unless($loop->last),@endunless</strong>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        @if (isset($sm_weekends))
                                            {{ count(@$sm_weekends) }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="white-box mt-40">
                <div class="row">
                    <div class="col-lg-6">
                        @include('backEnd.alumniPanel.inc.noticeboard')
                    </div>
                    <div class="col-lg-6">
                        @include('backEnd.alumniPanel.inc.commonUpcomingEvent')
                    </div>
                </div>
            </div>
            <div class="white-box mt-40">
                <div class="row">
                    <div class="col-lg-6">
                        @include('backEnd.communicate.commonAcademicCalendar')
                    </div>
                    <div class="col-lg-6 col-xl-6">
                        @include('backEnd.alumniPanel.inc.commonDocument')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('backEnd.communicate.academic_calendar_css_js')
